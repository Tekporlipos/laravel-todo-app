<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use function Symfony\Component\String\u;

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



    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided email address is not registered.'],
            ]);
        }

        if (!$user->email_verified_at) {
            // Send verification notification if not already sent
            if (!EmailVerificationController::isTokenActive($user)) {
                EmailVerificationController::verificationNotification($user);
            }

            throw ValidationException::withMessages([
                'email' => ['Account is not verified. Please check your email for verification instructions.'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $fullToken = $user->createToken($request->userAgent());

        $data = [
            "user" => $user,
            "access_token" => $fullToken->plainTextToken
        ];

        return success_response($data, "Login successful");
    }


    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string'],
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/|confirmed',
        ]);

        try {
            $user = User::create([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "password" => Hash::make($request->input("password"),),
            ]);
            EmailVerificationController::verificationNotification($user);
        }catch (\Exception $e){
        return error_response([], $e->getMessage());
        }
        return success_response($user, 'Account created successfully. Please check your email to verify.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return success_response([], 'Successfully logged out');
    }


    public function user()
    {

        $user = \request()->user();

        return success_response($user, "");

    }

    public function logoutFromAllDevice(Request $request)
    {
        $request->user()->tokens()->delete();

        return success_response([], 'Successfully logged out');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return error_response(message: 'The current password is incorrect.', code: 402);
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return success_response(message: 'Password changed successfully!', code: 201);
    }


}
