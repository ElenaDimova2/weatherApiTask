<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function tests_registers_successfully()
    {
        $payload = [
            'name' => 'Vasil',
            'email' => 'vasil@vasil.com',
            'password' => 'password',
        ];

        $this->json('post', 'api/register', $payload)
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
    }

    public function tests_requires_password_email_and_name()
    {
        $this->json('post', '/api/register')
            ->assertStatus(422)
            ->assertJson([
                "errors"=>[
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }
}
