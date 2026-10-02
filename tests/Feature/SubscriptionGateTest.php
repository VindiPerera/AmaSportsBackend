<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureActiveSubscription;
use App\Models\Player;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * "No active plan (free trial or paid year) -> no adding/editing sport
 * data." Audits every API write route so a new sport endpoint can't ship
 * without the subscription.active middleware, then checks the real
 * responses with no plan, an expired plan, a trial and a paid year.
 */
class SubscriptionGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Write routes that are deliberately free — account/auth, payments,
     * contact/reports, and the shared profile header (name/country/photos/
     * badges), which routes/api.php documents as account info rather than
     * sport data. Anything else that writes must require a plan.
     */
    private const FREE_WRITE_ROUTES = [
        'api/auth/register', 'api/auth/login', 'api/auth/forgot-password', 'api/auth/reset-password', 'api/auth/logout',
        'api/user', 'api/user/password',
        'api/payhere/notify', 'api/subscriptions/create-order', 'api/subscriptions/start-trial',
        'api/matches/{match}/stream-access/create-order', 'api/matches/{match}/score',
        'api/contact',
        'api/player/profile', 'api/player/photos', 'api/player/photos/{photo}',
        'api/player/achievements/{playerAchievement}/post',
    ];

    /** Every sport profile save endpoint (PUT) — one per sport form in the app. */
    private const SPORT_PROFILE_ROUTES = [
        'cricket-profile', 'hockey-profile', 'base-ball-profile', 'net-ball-profile', 'racket-sport-profile',
        'kabadi-profile', 'judo-profile', 'basketball-profile', 'football-profile', 'rugby-profile',
        'boxing-profile', 'karate-profile', 'chess-profile', 'athletics-profile', 'swimming-profile',
        'volleyball-profile', 'beach-volleyball-profile', 'elle-profile', 'soft-ball-cricket-profile',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        config(['subscription.bypass' => false]);
        Storage::fake('public');
    }

    public function test_every_write_route_requires_a_plan_unless_deliberately_free(): void
    {
        $ungated = [];

        foreach (Route::getRoutes() as $route) {
            $writes = array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE']);
            if (! str_starts_with($route->uri(), 'api/') || ! $writes) {
                continue;
            }
            if (in_array($route->uri(), self::FREE_WRITE_ROUTES, true)) {
                continue;
            }
            if (! in_array(EnsureActiveSubscription::class, $route->gatherMiddleware(), true)
                && ! in_array('subscription.active', $route->gatherMiddleware(), true)) {
                $ungated[] = implode('|', $writes).' '.$route->uri();
            }
        }

        $this->assertSame([], $ungated, 'These write routes let players change data without an active plan.');
    }

    public function test_no_plan_blocks_every_sport_save_upload_and_analysis(): void
    {
        $this->actingAsPlayer();
        $this->assertEverythingBlocked();
    }

    public function test_expired_trial_blocks_everything(): void
    {
        $player = $this->actingAsPlayer();
        $this->plan($player, isTrial: true, expiresAt: now()->subDay());
        $this->assertEverythingBlocked();
    }

    public function test_expired_paid_year_blocks_everything(): void
    {
        $player = $this->actingAsPlayer();
        $this->plan($player, isTrial: false, expiresAt: now()->subDay());
        $this->assertEverythingBlocked();
    }

    public function test_cancelled_or_pending_rows_do_not_unlock(): void
    {
        $player = $this->actingAsPlayer();
        $this->plan($player, isTrial: false, expiresAt: now()->addYear(), status: Subscription::STATUS_CANCELLED);
        $this->plan($player, isTrial: false, expiresAt: null, status: Subscription::STATUS_PENDING);
        $this->assertEverythingBlocked();
    }

    public function test_active_trial_passes_the_gate(): void
    {
        $this->actingAsPlayer();
        $this->postJson('/api/subscriptions/start-trial')->assertOk();
        $this->assertGatePasses();
    }

    public function test_active_paid_year_passes_the_gate(): void
    {
        $player = $this->actingAsPlayer();
        $this->plan($player, isTrial: false, expiresAt: now()->addMonths(6));
        $this->assertGatePasses();
    }

    public function test_reading_own_data_and_account_header_stay_free_without_a_plan(): void
    {
        $this->actingAsPlayer();

        $this->getJson('/api/player/cricket-profile')->assertOk();
        $this->getJson('/api/player/hockey-profile')->assertOk();
        $this->getJson('/api/player/sports')->assertOk();
        $this->getJson('/api/player/profile')->assertOk();
    }

    private function assertEverythingBlocked(): void
    {
        foreach (self::SPORT_PROFILE_ROUTES as $route) {
            $this->putJson("/api/player/{$route}", [])
                ->assertStatus(402)
                ->assertJsonPath('error_code', 'subscription_required');
        }

        $logo = UploadedFile::fake()->image('logo.png');
        $this->postJson('/api/player/team-logo', ['logo' => $logo, 'team_name' => 'Colts'])->assertStatus(402);
        $this->deleteJson('/api/player/team-logo', ['team_name' => 'Colts'])->assertStatus(402);
        $this->postJson('/api/player/college-logo', ['logo' => $logo])->assertStatus(402);
        $this->deleteJson('/api/player/college-logo')->assertStatus(402);
        $this->postJson('/api/player/cricket-profile/college-logo', ['logo' => $logo])->assertStatus(402);
        $this->postJson('/api/player/cricket-profile/score-sheet', ['image' => $logo])->assertStatus(402);
        $this->getJson('/api/player/cricket-analysis')->assertStatus(402);
        $this->getJson('/api/player/hockey/analysis')->assertStatus(402);

        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_active', false);
    }

    /** With a plan the gate lets requests reach the controller (validation errors are fine — anything but 402). */
    private function assertGatePasses(): void
    {
        foreach (self::SPORT_PROFILE_ROUTES as $route) {
            $status = $this->putJson("/api/player/{$route}", [])->status();
            $this->assertNotSame(402, $status, "PUT /api/player/{$route} was blocked despite an active plan.");
        }
        $this->assertNotSame(402, $this->getJson('/api/player/cricket-analysis')->status());

        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_active', true);
    }

    private function actingAsPlayer(): Player
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return Player::firstOrCreate(['user_id' => $user->id]);
    }

    private function plan(Player $player, bool $isTrial, $expiresAt, string $status = Subscription::STATUS_ACTIVE): Subscription
    {
        return Subscription::create([
            'player_id' => $player->id,
            'amount' => $isTrial ? 0 : 10,
            'currency' => 'USD',
            'status' => $status,
            'is_trial' => $isTrial,
            'starts_at' => $expiresAt?->copy()->subYear(),
            'expires_at' => $expiresAt,
        ]);
    }
}
