<?php

use App\Http\Middleware\CheckApiToken;
use Illuminate\Http\Request;
use App\Artist;
use App\Album;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//--------------Start artist routes----------------
Route::post('artist/create', 'API\ArtistsController@store')->middleware('auth:api');

Route::put('artist/update/{id}', 'API\ArtistsController@update');

Route::get('artist/{id}', 'API\ArtistsController@Show');

Route::delete('artist/delete/{id}', 'API\ArtistsController@delete');

Route::get('artist/search/{searchTerm}', 'API\ArtistsController@Search');

Route::get('artist/name/{name}', 'API\ArtistsController@GetArtistByName');

Route::get('artists', function() {
    return Artist::all();
});
//--------------End artist routes----------------




//--------------Start albums routes----------------
Route::post('album/create', 'API\AlbumsController@store')->middleware('auth:api');

Route::post('album/upload/uploadArt', 'FilesController@UploadAlbumArtwork');

Route::get('album/{id}', 'API\AlbumsController@Show');

Route::put('album/update/{id}', 'API\AlbumsController@update');

Route::get('album/artist/{id}', 'API\AlbumsController@ShowAlbumArtist');

Route::get('album/songs/{id}', 'API\AlbumsController@ShowAlbumSongs');

Route::get('album/search/{searchTerm}', 'API\AlbumsController@Search');

Route::get('album/name/{name}', 'API\AlbumsController@GetArtistByTitle');

Route::get('albums', 'API\AlbumsController@GetAllAlbums');

//--------------End albums routes----------------




//--------------Start Songs routes----------------
Route::post('song/create', 'API\SongsController@store')->middleware('auth:api');

Route::get('stream/{song_id}', 'API\SongsController@getSongFile');

//Route::get('song/{id}', 'API\SongsController@Show');

//Route::put('song/update/{id}', 'API\SongsController@update');

//Route::get('song/album/{id}', 'API\SongsController@ShowAlbumSongs');

//--------------End Songs routes----------------





//--------------Start User routes----------------

Route::post('login', 'API\UserController@login');

Route::post('signup', 'API\UserController@signup');

Route::get('user/isAdmin', 'API\UserController@isAdmin')->middleware('auth:api');

Route::get('user/albums', 'API\AlbumsController@test')->middleware('auth:api');

Route::get('user/albums/filter/{filter}/{value}', 'API\AlbumsController@filterUserAlbums')->middleware('auth:api');

Route::get('user/albums/order/{order_column}/{order_type}', 'API\AlbumsController@sortUserAlbums')->middleware('auth:api');

Route::get('user/info', 'API\UserController@userInfo')->middleware('auth:api');

Route::get('user/album/buy/{album_id}', 'API\UserController@buyAlbum')->middleware('auth:api');

Route::get('user/song/buy/{song_id}', 'API\UserController@buySong')->middleware('auth:api');


//--------------End User routes----------------







