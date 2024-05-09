@extends('mails.layout.email-layout')
@section('content')
    <h1>Hello {{ $user->name }},</h1>
    <p>Welcome to our platform!</p>
    <p> We're excited to have you join us. Before you get started, please verify your email address to activate your account.</p>
    <p>If you didn't request this, you can safely ignore this email.</p>
    <a href="{{config('app.url') . '/api/verify-email/' . $token . '/' . urlencode($user->email)}}"> Verify Email</a>

    <p>
        Thanks,<br>
        The Todo Team
    </p>
@endsection
