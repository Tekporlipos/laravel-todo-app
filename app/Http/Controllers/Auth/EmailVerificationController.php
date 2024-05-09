<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EmailVerificationController extends Controller
{
    /**
     * Verify email using token and email.
     */
    public function verify(string $token, string $email): JsonResponse
    {
        $verification = DB::table('email_verifications')
            ->where('token', $token)->first();

        if (!$verification) {
            return error_response([], 'Invalid or used token.');
        }

        if ($verification->expires_at && now()->gt($verification->expires_at)) {
            $verification->delete();
            $this->verificationNotification(User::findOrFail($verification->user_id));
            return error_response([], 'Token has expired. A new token has been sent to your email.');
        }

        $user = User::where('id', $verification->user_id)->where('email', $email)->first();

        if ($user) {
            $user->update(['email_verified_at' => now()]);
            DB::table('email_verifications')->where('id', $verification->id)->delete();
            return success_response([], 'Congrats! You\'re a tech genius now - no more queues for you.');
        }

        return error_response([], 'User not found.');
    }

    /**
     * Check if verification token is active.
     */
    public function isTokenActive(User $user): bool
    {
        $verification = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->first();

        return $verification->expires_at && now()->lt($verification->expires_at);
    }
}
