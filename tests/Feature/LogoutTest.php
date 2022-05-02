<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_user_is_logged_out_properly()
    {
        $user = User::factory()->create(['email' => 'user@test.com']);
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('get', '/api/city', [], $headers)->assertStatus(200);
        $this->json('post', '/api/logout', [], $headers)->assertStatus(200);

        $user = User::find($user->id);

        $this->assertEquals(null, $user->api_token);
    }

    public function test_user_with_null_token()
    {
        // Simulating login
        $user = User::factory()->create(['email' => 'user@test.com']);
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        // Simulating logout
        $user->tokens()->delete();
        $user->save();

        $this->json('get', '/api/city', [], $headers)->assertStatus(401);
    }
}
