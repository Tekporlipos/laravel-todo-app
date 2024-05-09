<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
        */

    public function login(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ])->validate();

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided email address is not registered.'],
            ]);
        }

        if (!$user->email_verified_at) {
            EmailVerificationController::verificationNotification($user);
            throw ValidationException::withMessages([
                'email' => ['Account is not verified. Please check your email for verification instructions.'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $accessToken = $user->createToken($request->userAgent())->plainTextToken;

        return success_response(['user' => $user, 'access_token' => $accessToken], 'Login successful');
    }

    public function create(Request $request): JsonResponse
    {
        $validatedData = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string'],
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/|confirmed',
        ])->validated();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            EmailVerificationController::verificationNotification($user);
        } catch (\Exception $e) {
            return error_response([], $e->getMessage());
        }

        return success_response($user, 'Account created successfully. Please check your email to verify.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return success_response([], 'Successfully logged out');
    }

    public function user(Request $request): JsonResponse
    {
        return success_response($request->user(), '');
    }

    public function logoutFromAllDevice(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return success_response([], 'Successfully logged out from all devices');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validatedData = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ])->validated();

        $user = Auth::user();

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return error_response(message: 'The current password is incorrect.', code: 402);
        }

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return success_response(message: 'Password changed successfully!', code: 201);
    }
}
