<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' =>'Barra Abd Al Fattah',
            'email' => 'barra@gmail.com' ,
            'password' => bcrypt('123456'),
            'is_admin' => '1',
        ]);

        DB::table('users')->insert([
            'name' =>'Mara',
            'email' => 'mara@gmail.com' ,
            'password' => bcrypt('123456'),
            'is_admin' => '1',

        ]);


        DB::table('users')->insert([
            'name' =>'Johnny',
            'email' => 'johhny@gmail.com' ,
            'password' => bcrypt('123456'),
            'is_admin' => '0',
        ]);
        DB::table('users')->insert([
            'name' =>'Mary',
            'email' => 'mary@gmail.com' ,
            'password' => bcrypt('123456'),
            'is_admin' => '0',
        ]);
    }
}
