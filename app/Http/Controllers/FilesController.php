<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;



class FilesController extends Controller
{
    public function UploadAlbumArtwork(Request $request)
    {
        $this->validate($request, [
            'artwork' =>
                'required|image|mimes:jpeg,png,jpg,JPG'
        ]);

        $image = $request->file('artwork');
        $new_name = '1.png';
        $path = $request->file('artwork')->move(public_path("/images"),$new_name);

        $photoUrl = url('/images/'.$new_name);
        return response()->json(['url' => $photoUrl],200);
    }
}
