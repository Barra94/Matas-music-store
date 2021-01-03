<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArtistTableSeeder extends Seeder
{

    public static $artists = array("Michael Jackson", "Qween", "Metallica" ,"PinkFloyd" );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 0; $i<5 ; $i++)
        {
            $dt = Carbon::now();
            $dateNow = $dt->toDateTimeString();

            DB::table('artists')->insert([
                'name' => ArtistTableSeeder::$artists[rand(0, sizeof(ArtistTableSeeder::$artists)-1)],
                'created_at' => $dateNow,
                'updated_at' => $dateNow
            ]);
        }
    }
}
