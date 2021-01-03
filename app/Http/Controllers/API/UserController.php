<?php

namespace App\Http\Controllers\API;

use App\Album;
use App\Song;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=> 'required|string|email',
            'remember_me'=> 'boolean'
        ]);
        $userCredentials = request(['email', 'password']);
        if(!Auth::attempt($userCredentials))
            return response()->json([
                'message'=> 'Unauthorized'
            ], 401);

        $user = $request->user();
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'access_token' => $token,
        ]);

    }

    public function signup(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string|email||unique:users',
            'password'=> 'required|string|confirmed'
        ]);

        $token = Str::random(60);

        $user = new User([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=> bcrypt($request->password),
            'api_token' => $token,
            'is_admin'=> 0,
        ]);
        $user->save();

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function isAdmin(Request $request)
    {
        $user = Auth::user();
        if($user->is_admin)
        {
            return 1;
        }
        return 0;
    }

    public function userInfo(Request $request)
    {
        return Auth::user();
    }

    public function buyAlbum(Request $request,$album_id)
    {


        $album = Album::find($album_id);
        if($album == null)
        {
            return response()->json([
                'message'=> 'There is no album with id ' . $album_id
            ],400);
        }

        $user = $request->user();

        //-------------Start prevent the user to but the album twice-------------
        $user_albums = $user->albums->where('id' ,$album->id);
        if(!$user_albums->isEmpty())
        {
            return response()->json([
                'message'=> 'You already bought the album ' . $album->title
            ],400);
        }
        //-------------End prevent the user to but the album twice---------------




        $user->albums()->attach($album->id);

        return response()->json([
            'message'=> 'You bought the album ' . $album->title
        ], 201);
    }

    public function buySong(Request $request,$song_id)
    {


        $song = Song::find($song_id);
        if($song == null)
        {
            return response()->json([
                'message'=> 'There is no song with id ' . $song_id
            ],400);
        }

        $user = $request->user();

        //-------------Start prevent the user to but the song if the song album is bought-------------
        $song_album = $song->album;
        $user_albums = $user->albums;
        if(!$user_albums->where('id' , $song_album->id)->isEmpty())
        {
            return response()->json([
                'message'=> 'You already bought the whole album ' . $song_album->title
            ],400);
        }
        //-------------End prevent the user to but the song if the song album is bought-------------





        //-------------Start prevent the user to buy the song twice-------------
        $user_songs = $user->songs->where('id' ,$song->id);
        if(!$user_songs->isEmpty())
        {
            return response()->json([
                'message'=> 'You already bought the song ' . $song->title
            ],400);
        }
        //-------------End prevent the user to buy the song twice---------------


        $user->songs()->attach($song->id);

        return response()->json([
            'message'=> 'You bought the song ' . $song->title
        ], 201);
    }
}
