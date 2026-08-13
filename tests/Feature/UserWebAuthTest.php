<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_web_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Log In');
    }

    public function test_user_can_login_via_web_login_form(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_STUDENT,
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/user/matches');
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_access_matches_and_match_creation(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $this->actingAs($user);

        $responseMatches = $this->get('/user/matches');
        $responseMatches->assertStatus(200);

        $responseCreate = $this->get('/user/matches/create');
        $responseCreate->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_user_web_portal(): void
    {
        $response = $this->get('/user/matches');
        $response->assertRedirect('/login');

        $responseCreate = $this->get('/user/matches/create');
        $responseCreate->assertRedirect('/login');
    }

    public function test_user_can_view_web_register_page(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
    }

    public function test_user_can_register_via_web_form(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Web User',
            'email' => 'newuser@example.com',
            'phone' => '+94771234567',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/user/matches');
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
        $this->assertAuthenticated();
    }
}
