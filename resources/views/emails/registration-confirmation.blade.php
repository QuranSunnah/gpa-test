@extends('layouts.email')

@section('title', 'Registration Confirmation')

@section('content')
<p style="text-align: left; font-size: 14px; font-weight: 400;">Dear {{$emailData['userInfo']?->full_name ?? 'User'}},</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">
    Your registration is complete.
</p>
<p style="text-align: left; font-size: 14px; font-weight: 400; margin-top:48px;">Best regards,</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">The GP Academy Team</p>
@endsection
