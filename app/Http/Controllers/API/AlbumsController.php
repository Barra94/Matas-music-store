<?php

namespace App\Http\Controllers\API;

use App\Album;
use App\Artist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlbumsController extends Controller
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
            'artist_name'=> 'required|string',
            'artwork' =>'image|mimes:jpeg,png,jpg,JPG',
            'genre'=> 'required|string',
        ]);

        $artist = Artist::query()->where('name','=',$request->artist_name)->get()->first();

        if($artist == null)
        {
            return response()->json([
                'message'=> 'There is no artist with name ' . $request->artist_name
            ],400);
        }

        $album = new Album([
            'title'=> $request->title,
            'artist_id' => $artist->id,
            'genre'=> $request->genre,
        ]);
        $album->save();

        if($request->file('artwork') != null)
        {
            //uploading the image
            $image = $request->file('artwork');
            $new_name = $album->id . '_' . $request->title . '.png';
            $path = $request->file('artwork')->move(public_path("/images/albums_artwork"),$new_name);
            $photoUrl = '/images/albums_artwork/' . $new_name;

            //saving the artwork url in the database
            $album->artwork = $photoUrl;
            $album->save();
        }

        return response()->json([
            'message'=> 'The album was created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = Album::find($id);
        if($album != null) {return $album;}
        else
        {
            return response()->json([
                'message'=> 'There is no album with an ID ' . $id
            ],400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $albumToUpdate = Album::find($id);
        $request->validate([
            'title'=> 'required|string',
            'artist_id'=> 'required|string',
            'artwork'=> 'string',
            'genre'=> 'required|string',

        ]);

        if($albumToUpdate == null)
        {
            return response()->json([
                'message'=> 'There is no album with an ID ' . $id
            ],400);
        }

        if(Artist::find($request->artist_id) == null)
        {
            return response()->json([
                'message'=> 'There is no artist with an ID ' . $request->artist_id
            ],400);
        }

        $albumToUpdate->title = $request->title;
        $albumToUpdate->artist_id = $request->artist_id;
        $albumToUpdate->artwork = $request->artwork;
        $albumToUpdate->genre = $request->genre;

        $albumToUpdate->save();

        return $albumToUpdate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $album = Album::find($id);

        if($album != null)
        {
            if($album->delete())
            {
                return response()->json([
                    'message'=> 'The album has been successfully deleted!'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message'=> 'There is no artist with an ID ' . $id
            ],400);
        }
    }

    //$id is album id
    public function ShowAlbumArtist($id)
    {
        $album = Album::find($id);
        if($album != null)
        {
            return $album->artist;
        }
        else
        {
            return response()->json([
                'message'=> 'There is no album with an ID ' . $id
            ],400);
        }
    }

    public function ShowAlbumSongs($id)
    {
        $album = Album::find($id);

        if($album != null)
        {
            return $album->songs;
        }
        else
        {
            return response()->json([
                'message'=> 'There is no album with an ID ' . $id
            ],400);
        }
    }

    public function Search($searchTerm)
    {
        //return $searchTerm;
        $album = Album::query()->where('title','like',"%" . $searchTerm . "%")->get();

        if($album != null)
        {
            return $album;
        }
    }

    public function GetAlbumByTitle($title)
    {
        //return $searchTerm;
        $album = Album::query()->where('title',$title)->get();

        if($album != null)
        {
            return $album;
        }
    }

    public function test(Request $request)
    {
        $user = $request->user();
        $album = Album::join('artists','albums.artist_id','artists.id')->join('users_albums','albums.id','users_albums.album_id')->where('users_albums.user_id', $user->id)
            ->select(
                'albums.id',
                'albums.title',
                'albums.artist_id',
                'albums.artwork',
                'albums.genre',
                'albums.created_at',
                'albums.updated_at',
                'artists.name as artist_name'
            )->get();
        return $album;
    }

    public function filterUserAlbums(Request $request,$filter,$value)
    {
        $user = $request->user();
        $album = Album::join('artists','albums.artist_id','artists.id')->join('users_albums','albums.id','users_albums.album_id')->where('users_albums.user_id', $user->id)->where($filter,$value)
            ->select(
                'albums.id',
                'albums.title',
                'albums.artist_id',
                'albums.artwork',
                'albums.genre',
                'albums.created_at',
                'albums.updated_at',
                'artists.name as artist_name'
            )->get();
        return $album;
    }

    public function sortUserAlbums(Request $request,$order_column,$order_type)
    {
        $user = $request->user();
        $album = Album::join('artists','albums.artist_id','artists.id')->join('users_albums','albums.id','users_albums.album_id')->where('users_albums.user_id', $user->id)->orderBy($order_column,$order_type)
            ->select(
                'albums.id',
                'albums.title',
                'albums.artist_id',
                'albums.artwork',
                'albums.genre',
                'albums.created_at',
                'albums.updated_at',
                'artists.name as artist_name'
            )->get();
        return $album;
    }

    public function GetAllAlbums()
    {
        $album = Album::join('artists','albums.artist_id','artists.id')
            ->select(
                'albums.id',
                'albums.title',
                'albums.artist_id',
                'albums.artwork',
                'albums.genre',
                'albums.created_at',
                'albums.updated_at',
                'artists.name as artist_name'
            )->get();
        return $album;
    }

    public function GetAllGenres()
    {
        //$query = DB::table('albums')->select('genre');
    }
}
