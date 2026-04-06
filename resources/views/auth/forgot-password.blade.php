@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Enter your email and we will send you a 6-digit OTP.')

@section('content')
    <form method="POST" action="{{ route('password.otp.send') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="name@example.com"
                required
                autofocus
                class="@error('email') error @enderror"
            >
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Send OTP</button>
    </form>
@endsection

@section('auth-links')
    <div class="auth-link">
        Back to <a href="{{ route('login') }}">Sign In</a>
    </div>
@endsection
