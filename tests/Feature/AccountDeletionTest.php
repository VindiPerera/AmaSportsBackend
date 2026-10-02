<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_deletes_account_player_and_uploads(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);
        $player = Player::create(['user_id' => $user->id]);
        Storage::disk('public')->put("players/{$player->id}/gallery/a.jpg", 'x');
        $token = $user->createToken('mobile')->plainTextToken;

        $this->withToken($token)
            ->deleteJson('/api/user', ['password' => 'Password123!'])
            ->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('players', ['id' => $player->id]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
        Storage::disk('public')->assertMissing("players/{$player->id}/gallery/a.jpg");
    }

    public function test_api_rejects_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);

        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/user', ['password' => 'wrong'])
            ->assertStatus(422);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_web_page_deletes_account(): void
    {
        $user = User::factory()->create([
            'email' => 'gone@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $this->get('/delete-account')->assertOk();

        $this->post('/delete-account', [
            'email' => 'gone@example.com',
            'password' => 'Password123!',
            'confirm' => '1',
        ])->assertRedirect('/delete-account');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_web_page_rejects_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'kept@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $this->post('/delete-account', [
            'email' => 'kept@example.com',
            'password' => 'wrong',
            'confirm' => '1',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
