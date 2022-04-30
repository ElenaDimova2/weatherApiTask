<?php

namespace Tests\Feature;

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
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
        ]);
    }

    public function test_user_logins_successfully()
    {
        $user = factory(User::class)->create([
            'email' => 'vasil@vasil.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'vasil@vasil.com', 'password' => 'password'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type'
                ],
            ]);
    }
}
