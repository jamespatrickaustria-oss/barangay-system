@extends('layouts.auth')

@section('title', 'Verify OTP')
@section('subtitle', 'Enter the 6-digit code sent to your email.')

@section('content')
    <div style="margin-bottom: 16px; color: var(--text-light); font-size: 14px;">
        Sent to: <strong>{{ session('password_reset_email') }}</strong>
    </div>

    <form method="POST" action="{{ route('password.otp.check') }}">
        @csrf

        <div class="form-group">
            <label for="otp">One-Time Password</label>
            <input
                type="text"
                id="otp"
                name="otp"
                value="{{ old('otp') }}"
                placeholder="000000"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                class="@error('otp') error @enderror"
                style="text-align: center; letter-spacing: 0.5em; font-size: 20px;"
            >
            @error('otp')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Verify OTP</button>
    </form>
@endsection

@section('auth-links')
    <div class="auth-link">
        Wrong email? <a href="{{ route('password.forgot') }}">Request Again</a>
    </div>
@endsection
