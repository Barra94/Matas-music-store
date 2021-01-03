<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Artist;

class ArtistsController extends Controller
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
            'name'=> 'required|string',
        ]);
        $artist = new Artist([
            'name'=> $request->name,
        ]);
        $artist->save();
        return response()->json([
            'message'=> 'The artist was created'
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)/////////****////////////read api recs
    {
        //
        $artist = Artist::findOrFail($id);

        if($artist != null)
        {
            return $artist;
        }
        else
        {
            return response()->json([
                'message'=> 'There is no artist with an ID ' . $id
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

        $artistToUpdate = Artist::find($id);
        $request->validate([
            'name'=> 'required|string',
        ]);

        if($artistToUpdate != null)
        {
            $artistToUpdate->name = $request->name;

            $artistToUpdate->save();

            return $artistToUpdate;
        }
        else
        {
            return response()->json([
                'message'=> 'There is no artist with an ID ' . $id
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $artist = Artist::find($id);

        if($artist != null)
        {
            if($artist->delete())
            {
                return response()->json([
                    'message'=> 'The artist has been successfully deleted!'
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

    public function Search($searchTerm)
    {
        //return $searchTerm;
        $artist = Artist::query()->where('name','like',"%" . $searchTerm . "%")->get();

        if($artist != null)
        {
            return $artist;
        }
    }

    public function GetArtistByName($name)
    {
        //return $searchTerm;
        $artist = Artist::query()->where('name',$name)->get();

        if($artist != null)
        {
            return $artist;
        }
    }
}
