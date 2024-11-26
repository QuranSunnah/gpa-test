@extends('layouts.email')

@section('title', 'Email OTP')

@section('content')
<p style="text-align: left; font-size: 14px; font-weight: 400;">Dear User,</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">
    Here's your GP Academy OTP:
    <span style="font-size: 20px; font-weight: 700; margin-left: 6px">{{$emailData['otp'] ?? ''}}</span>
</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">
    Use this code for verification. If you didn't request this OTP, please let us know.
</p>
<p style="text-align: left; font-size: 14px; font-weight: 400; margin-top:48px;">Best regards,</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">The GP Academy Team</p>
@endsection
