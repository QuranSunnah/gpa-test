@extends('layouts.email')

@section('title', 'Password Reset Confirmation')

@section('content')
<p style="text-align: left; font-size: 14px; font-weight: 400;">Dear {{$emailData['userInfo']?->full_name ?? 'User'}},</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">
    Your password has been reset succefully.
</p>
<p style="text-align: left; font-size: 14px; font-weight: 400; margin-top:48px;">Best regards,</p>
<p style="text-align: left; font-size: 14px; font-weight: 400;">The GP Academy Team</p>
@endsection