@extends('layouts.auth')

@section('title', 'Reset Password')
@section('subtitle', 'Set a new password for your account.')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="email" value="{{ session('password_reset_verified_email') }}">

        <div class="form-group">
            <label for="password">New Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••••••"
                required
                class="@error('password') error @enderror"
            >
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="••••••••••••"
                required
                class="@error('password_confirmation') error @enderror"
            >
            @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>
@endsection

@section('auth-links')
    <div class="auth-link">
        Back to <a href="{{ route('login') }}">Sign In</a>
    </div>
@endsection
