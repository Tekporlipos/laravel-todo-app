<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetMailHandler;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function passwordResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return error_response([],'Email not found');
        }
        $token = $this->generatePasswordResetToken($user);
        $this->sendPasswordResetEmail($user, $token);
        return success_response([],'Password reset email sent');
    }


    private function generatePasswordResetToken(User $user)
    {
        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->where('created_at', '>=', now()->subMinutes(config('auth.passwords.users.expire')))
            ->first();
        if ($existingToken) return null;
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );
        return $token;
    }


    private function sendPasswordResetEmail(User $user, $token)
    {
        if ($token)
            Mail::to($user->email)->send(new ResetMailHandler($user,$token,"mails.auth.reset-password", "Password Reset"));
    }

    public function changeResetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8|confirmed|regex:
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
        ]);
            Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return success_response([],"Your password has been reset!, You can use your new password now.");

    }
}
