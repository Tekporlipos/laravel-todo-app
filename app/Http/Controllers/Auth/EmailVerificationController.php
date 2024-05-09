<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailVerificationController extends Controller
{

    public function verify($token,$email)
    {
        $verification = DB::table('email_verifications')
            ->where('token', $token)->first();
        if (!$verification) {
            return error_response([],'Invalid or used token.');
        }
        $user = User::where('id', $verification->user_id)->where('email', $email)->first();
        if ($verification->expires_at && now()->gt($verification->expires_at)) {
            $verification->delete();
            self::verificationNotification($user);
            return error_response([],'Token has expired. A new token has been sent to your email.');
        }
        if ($user) {
            User::where('id', $user->id)->update(['email_verified_at' => now()]);
            DB::table('email_verifications')->where('id', $verification->id)->delete();
            return success_response([],'Congrats! You\'re a tech genius now - no more queues for you.');
        }
        return error_response([],'User not found.');
    }

    public static function verificationNotification(User $user)
    {
        $emailVerifications =  $user->emailVerifications();
        dispatch(new SendVerificationMail($user,$emailVerifications));
    }

    public static function isTokenActive(User $user)
    {
        $verification = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->first();
        return $verification->expires_at && now()->lt($verification->expires_at);
    }
}
