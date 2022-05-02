<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_requires_email_and_password()
    {
        $this->json('POST', 'api/login')
        ->assertStatus(422)
        ->assertJson([
            "errors" => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ]);
    }

    public function test_user_logins_successfully()
    {
        $user = User::factory()->create([
            'email' => 'vasil@vasil.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'vasil@vasil.com', 'password' => 'password'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);
    }
}
