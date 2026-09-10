<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_api_registration_successful(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Mobile Player',
            'email' => 'player@mobile.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'user' => ['id', 'name', 'email'],
            ],
        ]);
        $this->assertDatabaseHas('users', ['email' => 'player@mobile.com']);
    }

    public function test_mobile_api_login_successful(): void
    {
        $user = User::factory()->create([
            'email' => 'mobileuser@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'mobileuser@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'user' => ['id', 'name', 'email'],
            ],
        ]);
    }

    public function test_mobile_api_login_invalid_credentials_returns_error(): void
    {
        $user = User::factory()->create([
            'email' => 'mobileuser2@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'mobileuser2@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'The provided credentials are incorrect.',
        ]);
    }
}
