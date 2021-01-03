<?php

namespace App\Http\Controllers\API;

use App\Album;
use App\Song;
use App\User;
use falahati\PHPMP3\MpegAudio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SongsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->user()->is_admin == 0)
        {
            return response()->json([
                'message'=> 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'title'=> 'required|string',
            'album_title' => 'required|string',
            //'song_file' => 'required|mimeTypes:mp3'
        ]);

        //To find the album id from the album title.
        $album = Album::query()->where('title','=',$request->album_title)->get()->first();
        if($album == null)
        {
            return response()->json([
                'message'=> 'There is no album with title ' . $request->album_title
            ],400);
        }

        //generate track_number for the song
        $track_number_string = Song::query()->select('track_number')->where('album_id','=',$album->id)->get()->max();
        if($track_number_string == null){
            $track_number_int = 0;
        }
        else{
            $track_number_int = (int)$track_number_string['track_number'];
        }
        $track_number_int++;


        $song_file_name = $album->id . '_' . $track_number_int . '_' . $request->title . '.mp3';
        $song_public_path = "albums/" . $album->title . "/";

        //upload the song
        $song_file = $request->file('song_file');

        $path = $request->file('song_file')->move(public_path($song_public_path),$song_file_name);

        $song_full_url= $song_public_path . $song_file_name;


        //-----------------Start trimming-----------------
        $trimmed_song_public_path =  "albums/" . $album->title . "/" . 'trimmed/';
        $trimmed_song_full_path = $trimmed_song_public_path . $song_file_name;

        //To create the directory trimmed if not created.
        if( File::exists($trimmed_song_public_path)== false)
        {
            File::makeDirectory($trimmed_song_public_path);
        }

        MpegAudio::fromFile($song_full_url)->trim(0,30)->saveFile($trimmed_song_full_path);
        //-----------------End trimming-----------------




        //-----------------Start calculating duration-----------------
        $song_duration_in_seconds = MpegAudio::fromFile($song_full_url)->getTotalDuration();
        $song_duration_as_time = gmdate('H:i:s', $song_duration_in_seconds);

        //-----------------End calculating duration-------------------

        $song = new Song([
            'title'=> $request->title,
            'album_id' => $album->id,
            'track_number' => $track_number_int,
            'duration' => $song_duration_as_time,
            'file' => $trimmed_song_full_path,
            'original_file' => $song_full_url,
        ]);

        $song->save();


        //-----------------Start 10s trimming-----------------
        $trimmed_song_public_path_10s =  "songs_10s/";
        $trimmed_song_full_path_10s = $trimmed_song_public_path_10s . $song->id . '.mp3';

        MpegAudio::fromFile($song_full_url)->trim(0,10)->saveFile($trimmed_song_full_path_10s);
        //-----------------End 10s trimming-----------------

        return response()->json([
            'message'=> 'The song was added'
        ], 201);
    }

    /**
     * Get a song file
     *
     * @param Request $request
     * @param $song_id
     * @return \Illuminate\Http\JsonResponse|BinaryFileResponse
     */
    public function getSongFile(Request $request,$song_id)
    {
        //check if the user bought the album.

        $song = Song::find($song_id);
        if ($song == null) {
            //Song not found
            return response()->json([
                'message' => 'There is no song with an ID ' . $song_id
            ], 400);
        }

        $guest_song_path = 'songs_10s/' . $song->id . '.mp3';

        $api_token = $request->api_token;
        if ($api_token == null) {
            //guest
            $response = new BinaryFileResponse($guest_song_path);
            BinaryFileResponse::trustXSendfileTypeHeader();

            return $response;
        }

        $user = User::All()->Where('api_token', '=', $api_token)->first();

        if ($user == null) {
            //guest
            $response = new BinaryFileResponse($guest_song_path);
            BinaryFileResponse::trustXSendfileTypeHeader();

            return $response;
        }

        $album = $user->albums()->where('user_id', '=', $user->id)->Where('album_id', '=', $song->album_id)->get();

        $user_song = $user->songs()->where('user_id', '=', $user->id)->Where('song_id', '=', $song->id)->get();

        if ($album->isEmpty() and $user_song->isEmpty()) {
            //the user is logged in.
            //the user does not has the song in his album songs
            //or in the songs list

            $response = new BinaryFileResponse($song->file);
            BinaryFileResponse::trustXSendfileTypeHeader();

            return $response;

        } else {
            //the user is logged in.
            //the user bought the song

            $response = new BinaryFileResponse($song->original_file);
            BinaryFileResponse::trustXSendfileTypeHeader();

            return $response;
        }
    }

}
