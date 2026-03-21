<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Management System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --blue:       #1a6ec7;
            --blue-dark:  #154f96;
            --sky:        #d9f2fc;
            --sky-mid:    #a8dff5;
            --green:      #3a7d44;
            --green-dark: #2c5f35;
            --green-light:#e7f6ea;
            --white:      #ffffff;
            --off-white:  #f4f8fb;
            --surface:    #f4f8fb;
            --text:       #1a2433;
            --text-light: #4b5e72;
            --text-muted: #6b7f93;
            --blue-light: #eaf4ff;
            --border:     rgba(26,110,199,0.12);
            --gold:       #e8b84b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            flex-wrap: wrap;
            min-height: 100vh;
            color: var(--text);
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef3 100%);
            padding: 40px 20px;
        }

        .left-panel {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right-panel {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }

        .login-card {
            max-width: 700px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        }

        .card-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .badge {
            display: inline-block;
            background: var(--blue-light);
            color: var(--blue);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            margin: 0;
            margin-bottom: 8px;
        }

        .card-header p {
            color: var(--text-muted);
            font-size: 15px;
            margin: 8px 0 0 0;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-error {
            background: #fce8e6;
            border-left: 4px solid #dc3545;
            color: #b91c1c;
        }

        .alert-success {
            background: var(--green-light);
            border-left: 4px solid var(--green);
            color: var(--green-dark);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            color: var(--text);
            background: white;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26, 111, 204, 0.1);
        }

        .form-group input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.2s;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--blue);
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .form-footer {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .divider-or {
            text-align: center;
            margin: 24px 0;
            position: relative;
            color: var(--text-muted);
            font-size: 13px;
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
            font-size: 14px;
            color: var(--text-muted);
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

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1a6ec7, #3a7d44);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            margin-top: 8px;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            opacity: 0.92;
        }

        .btn-primary:active {
            transform: scale(0.99);
        }

        @media (max-width: 768px) {
            body {
                padding: 24px 16px;
            }

            .login-card {
                padding: 28px 20px;
                border-radius: 16px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }

            .login-card {
                padding: 20px 16px;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="right-panel">
            <div class="login-card">
            <div class="card-header">
                <span class="badge">Resident Portal</span>
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

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
                        required
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
                            required
                            class="@error('password') error @enderror"
                        >
                        <span class="password-toggle" onclick="togglePassword('password')">
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
                    <label>
                        <input type="checkbox" name="remember">
                        <span style="font-size: 13px; color: var(--text-muted); margin-left: 4px;">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <div class="divider-or">
                <span>or</span>
            </div>

            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Register here →</a>
            </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer style="background: linear-gradient(135deg, #0d1b2a 0%, #0a0f18 100%); color: rgba(255,255,255,0.75); padding: 48px 28px 24px; width: 100%;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 36px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px; text-decoration: none;">
                        <img src="{{ file_exists(public_path('images/city_of_general_trias_seal.png')) ? asset('images/city_of_general_trias_seal.png') : asset('images/city_of_general_trias.png') }}" alt="Logo" style="width: 36px; height: 36px; filter: brightness(0) invert(1) drop-shadow(0 2px 4px rgba(0,0,0,0.3));"/>
                        <div style="line-height: 1.2;">
                            <strong style="display: block; font-family: 'Plus Jakarta Sans', serif; font-size: 14px; color: white; letter-spacing: 0.01em;">Barangay Management System</strong>
                            <span style="font-size: 11px; color: rgba(255,255,255,0.5); font-weight: 500; letter-spacing: 0.04em; text-transform: uppercase;">City of General Trias</span>
                        </div>
                    </div>
                    <p style="font-size: 13px; line-height: 1.7; max-width: 220px;">Empowering communities through technology-driven governance and transparent service delivery.</p>
                </div>
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: white; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 16px;">Services</h4>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Barangay Clearance</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Residency Certificate</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Business Permits</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Indigency Certificate</a>
                </div>
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: white; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 16px;">Resources</h4>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">FAQs</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Privacy Policy</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Terms of Use</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Support</a>
                </div>
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: white; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 16px;">Government</h4>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">City Hall</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">City Council</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">Barangay Officials</a>
                    <a href="#" style="display: block; font-size: 13px; color: rgba(255,255,255,.6); text-decoration: none; margin-bottom: 10px; transition: color .2s;">DRRMO</a>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <p style="font-size: 12px; color: rgba(255,255,255,0.4); margin: 0;">© 2026 City Government of General Trias, Cavite. All rights reserved.</p>
                <p style="font-size: 12px; color: rgba(255,255,255,0.4); margin: 0;">Powered by the Barangay Management System</p>
            </div>
        </div>
    </footer>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById('eye-icon-' + fieldId);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                // Change to eye-slash (hidden) icon
                toggleIcon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
            } else {
                passwordField.type = 'password';
                // Change to normal eye icon  
                toggleIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
            }
        }
    </script>
</body>
</html>
