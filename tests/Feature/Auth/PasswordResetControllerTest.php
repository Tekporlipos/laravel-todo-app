<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\PasswordResetController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_passwordResetEmail_with_valid_email()
    {
        $user = User::factory()->create();
        $request = ['email' => $user->email];

        $response = (new PasswordResetController())->passwordResetEmail($this->createRequestObject($request));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset email sent']);
    }

    public function test_passwordResetEmail_with_invalid_email()
    {
        $request = ['email' => $this->faker->email];

        $response = (new PasswordResetController())->passwordResetEmail($this->createRequestObject($request));

        $response->assertStatus(400)
            ->assertJson(['message' => 'Email not found']);
    }

    public function test_changeResetPassword_with_valid_data()
    {
        $user = User::factory()->create();
        $token = Str::random(60);
        $password = $this->faker->password;

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Password::shouldReceive('reset')
            ->once()
            ->with([
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
                'token' => $token,
            ], \Closure::class)
            ->andReturn(true);

        $request = [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = (new PasswordResetController())->changeResetPassword($this->createRequestObject($request));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Your password has been reset! You can now use your new password.']);
    }

    protected function createRequestObject(array $data)
    {
        return new \Illuminate\Http\Request($data);
    }
}
