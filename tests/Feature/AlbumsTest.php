<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Faker\Generator as Faker;

class AlbumsTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetching_all_the_albums()
    {
        $response = $this->get('/albums');

        $response->assertStatus(200);
    }

    public function test_adding_user_to_the_database()
    {
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

//    public function test_adding_user_to_the_database()
//    {
//        $user = factory(User::class)->create();
//
//        $this->assertDatabaseHas('users', ['email' => $user->email]);
//    }
}
