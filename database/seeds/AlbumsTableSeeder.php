<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Artist;

class AlbumsTableSeeder extends Seeder
{

    public static $titles = array("Black", "The wall", "Thriller" ,"a night at the opera");
    public static $genres = array("Metal", "Hard Rock", "Rock" ,"Pop");
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 0; $i<5 ; $i++)
        {
            $dt = Carbon::now();
            $dateNow = $dt->toDateTimeString();

            DB::table('albums')->insert([
                'title' => AlbumsTableSeeder::$titles[rand(0, sizeof(AlbumsTableSeeder::$titles)-1)],
                'artist_id' => Artist::all()->random(1)->first()->id,
                'artwork' => 'c:\1.jpg',
                'genre' => AlbumsTableSeeder::$genres[rand(0, sizeof(AlbumsTableSeeder::$genres)-1)],
                'created_at' => $dateNow,
                'updated_at' => $dateNow

            ]);
        }
    }
}
