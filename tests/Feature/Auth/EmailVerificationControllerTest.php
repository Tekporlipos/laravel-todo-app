<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_verify_with_valid_token_and_email()
    {
        $user = User::factory()->create();
        $token = $this->faker->uuid;
        $email = $user->email;

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDay(),
        ]);

        $response = (new EmailVerificationController())->verify($token, $email);

        $response->assertStatus(200)
            ->assertJson(['message' => "Congrats! You're a tech genius now - no more queues for you."]);
    }

    public function test_verify_with_invalid_or_used_token()
    {
        $token = $this->faker->uuid;
        $email = $this->faker->email;

        $response = (new EmailVerificationController())->verify($token, $email);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Invalid or used token.']);
    }

    public function test_verify_with_expired_token()
    {
        $user = User::factory()->create();
        $token = $this->faker->uuid;
        $email = $user->email;

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->subDay(),
        ]);

        $response = (new EmailVerificationController())->verify($token, $email);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Token has expired. A new token has been sent to your email.']);
    }

    public function test_isTokenActive_with_active_token()
    {
        $user = User::factory()->create();
        $token = $this->faker->uuid;

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDay(),
        ]);

        $isActive = (new EmailVerificationController())->isTokenActive($user);

        $this->assertTrue($isActive);
    }

    public function test_isTokenActive_with_inactive_token()
    {
        $user = User::factory()->create();
        $token = $this->faker->uuid;

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->subDay(),
        ]);

        $isActive = (new EmailVerificationController())->isTokenActive($user);

        $this->assertFalse($isActive);
    }
}
