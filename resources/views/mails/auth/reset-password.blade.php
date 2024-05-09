@extends('mails.layout.email-layout')

@section('content')
    <h1>{{$user->name}}</h1>
    <a href="{{config("app.url").'/user/auth/change-password?token='.$token.'&email='.$user->email}}">click on this link
        to reset your account</a>
@endsection
