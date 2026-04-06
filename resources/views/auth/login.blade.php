@extends('layouts.auth')

@section('title', 'Login')
@section('subtitle', 'Sign in to your account')

@section('styles')
<style>
    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 52px;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
        padding: 8px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--blue);
        background: var(--blue-light);
    }

    .password-toggle svg {
        width: 22px;
        height: 22px;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 26px;
        font-size: 16px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .remember-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-light);
        font-size: 15px;
    }

    .remember-wrap input {
        width: 20px;
        height: 20px;
        accent-color: var(--blue);
    }

    .forgot-link {
        font-size: 15px;
        color: var(--blue);
        text-decoration: none;
        font-weight: 700;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .divider-or {
        text-align: center;
        margin: 28px 0;
        position: relative;
        color: var(--text-muted);
        font-size: 14px;
    }

    .divider-or::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border);
        z-index: 0;
    }

    .divider-or span {
        position: relative;
        background: white;
        padding: 0 8px;
        z-index: 1;
    }

    .register-link {
        text-align: center;
        font-size: 16px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .register-link a {
        color: var(--blue);
        text-decoration: none;
        font-weight: 700;
        transition: opacity 0.2s;
    }

    .register-link a:hover {
        opacity: 0.8;
    }

    .login-tip {
        margin-top: 16px;
        text-align: center;
        font-size: 14px;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .form-footer {
            font-size: 15px;
        }

        .forgot-link,
        .remember-wrap {
            font-size: 14px;
        }

        .register-link {
            font-size: 15px;
        }
    }
</style>
@endsection

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="resident@barangay.local"
            autocomplete="email"
            required
            autofocus
            class="@error('email') error @enderror"
        >
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <div class="password-wrapper">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••••••"
                autocomplete="current-password"
                required
                class="@error('password') error @enderror"
            >
            <span
                class="password-toggle"
                role="button"
                tabindex="0"
                aria-label="Show or hide password"
                onclick="togglePassword('password')"
                onkeydown="if(event.key === 'Enter' || event.key === ' '){ event.preventDefault(); togglePassword('password'); }"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="eye-icon-password" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </span>
        </div>
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-footer">
        <label class="remember-wrap" for="remember">
            <input type="checkbox" id="remember" name="remember">
            <span>Keep me signed in</span>
        </label>
        <a href="{{ route('password.forgot') }}" class="forgot-link">Forgot password?</a>
    </div>

    <button type="submit" class="btn-primary">Sign In</button>

    <p class="login-tip">Tip: Use your registered email address to avoid login errors.</p>
</form>
@endsection

@section('auth-links')
<div class="divider-or">
    <span>or</span>
</div>

<div class="register-link">
    Don't have an account? <a href="{{ route('register') }}">Create one now</a>
</div>
@endsection

@section('scripts')
<script>
    // Browsers may restore this page from back-forward cache without a request.
    // Force a reload so middleware can redirect authenticated users to dashboard.
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = document.getElementById('eye-icon-' + fieldId);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
        } else {
            passwordField.type = 'password';
            toggleIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
        }
    }
</script>
@endsection
