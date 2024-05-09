<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_login_with_valid_credentials()
    {
        $password = $this->faker->password;
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['user', 'access_token']]);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_create_user_with_valid_data()
    {
        $password = $this->faker->password;

        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['name', 'email']]);
    }

    public function test_create_user_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            // Missing name
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'password_confirmation' => $this->faker->password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Successfully logged out']);
    }

    public function test_logout_from_all_devices()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout-from-all-devices');

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Successfully logged out from all devices']);
    }

    public function test_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/change-password', [
                'current_password' => 'oldpassword',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword',
            ]);

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Password changed successfully!']);
    }
}
