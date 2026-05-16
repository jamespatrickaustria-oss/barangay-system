<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PROJECT CONNECT - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">
    <style>
        :root {
            --blue: #0f5ea8;
            --blue-dark: #0b477f;
            --green: #2f7d4a;
            --green-dark: #1f5f37;
            --green-light: #e9f6ec;
            --white: #ffffff;
            --surface: #f8fbff;
            --text: #112033;
            --text-light: #334e68;
            --text-muted: #486581;
            --blue-light: #e8f3ff;
            --border: rgba(15, 94, 168, 0.18);
            --shadow: 0 24px 60px rgba(11, 26, 51, 0.14);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            flex-wrap: wrap;
            min-height: 100vh;
            color: var(--text);
            padding-top: 32px;
            background:
                radial-gradient(circle at 12% 15%, rgba(15, 94, 168, 0.17), rgba(15, 94, 168, 0) 38%),
                radial-gradient(circle at 88% 6%, rgba(47, 125, 74, 0.16), rgba(47, 125, 74, 0) 32%),
                linear-gradient(130deg, #f5f9fe 0%, #e8f1f8 52%, #edf7f0 100%);
            
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
            max-width: 760px;
            width: 100%;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.86);
            border-radius: 28px;
            padding: 44px;
            box-shadow: var(--shadow);
        }

        .card-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-seal {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .login-seal img {
            width: 112px;
            height: 112px;
            object-fit: contain;
            filter: drop-shadow(0 10px 22px rgba(8, 35, 66, 0.2));
        }

        .badge {
            display: inline-block;
            background: var(--blue-light);
            color: var(--blue);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .card-header h2 {
            font-family: 'Source Serif 4', serif;
            font-size: 38px;
            font-weight: 800;
            color: var(--text);
            margin: 0;
            margin-bottom: 10px;
            letter-spacing: 0.01em;
            line-height: 1.1;
        }

        .card-header p {
            color: var(--text-muted);
            font-size: 18px;
            margin: 8px 0 0 0;
            line-height: 1.45;
        }

        .alert {
            border-radius: 14px;
            padding: 13px 16px;
            margin-bottom: 20px;
            font-size: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.5;
        }

        .alert-icon {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-error {
            background: #fef0f0;
            border: 1px solid #f3c9c9;
            color: #8c1d1d;
        }

        .alert-error .alert-icon {
            background: #b42318;
            color: #ffffff;
        }

        .alert-success {
            background: var(--green-light);
            border: 1px solid #b9e5c7;
            color: var(--green-dark);
        }

        .alert-success .alert-icon {
            background: var(--green);
            color: #ffffff;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: none;
            color: var(--text-light);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .form-group input {
            width: 100%;
            min-height: 56px;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 14px;
            font-size: 17px;
            font-family: inherit;
            color: var(--text);
            background: white;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
            outline: none;
        }

        .form-group input::placeholder {
            color: #7a8ca1;
        }

        .form-group input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 5px rgba(15, 94, 168, 0.15);
            transform: translateY(-1px);
        }

        .form-group input:focus-visible,
        .btn-primary:focus-visible,
        .auth-link a:focus-visible {
            outline: 3px solid rgba(15, 94, 168, 0.35);
            outline-offset: 2px;
        }

        .form-group input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 6px;
            font-weight: 600;
        }

        .btn-primary {
            width: 100%;
            min-height: 58px;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--blue), var(--green));
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 18px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            margin-top: 10px;
            transition: opacity 0.2s, transform 0.12s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.96;
            box-shadow: 0 10px 20px rgba(15, 94, 168, 0.25);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .auth-link {
            text-align: center;
            margin-top: 22px;
            font-size: 16px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .auth-link a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 800;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .site-footer {
            background: linear-gradient(130deg, #0f2237 0%, #0a1727 100%);
            color: rgba(255, 255, 255, 0.75);
            padding: 44px 28px 22px;
            width: 100%;
            margin-top: 28px;
        }

        .site-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .site-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 38px;
            margin-bottom: 30px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .footer-brand img {
            width: 38px;
            height: 38px;
            filter: brightness(0) invert(1) drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .footer-brand strong {
            display: block;
            font-size: 15px;
            color: #ffffff;
            line-height: 1.2;
        }

        .footer-brand span {
            display: block;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.56);
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .footer-summary {
            font-size: 13px;
            line-height: 1.7;
            max-width: 280px;
        }

        .footer-heading {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .footer-link {
            display: block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.64);
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer-bottom p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
        }

        @media (max-width: 992px) {
            .site-footer-grid {
                grid-template-columns: 1.5fr 1fr 1fr;
            }
        }

        @media (max-width: 768px) {

            .login-card {
                padding: 28px 20px;
                border-radius: 20px;
            }

            .login-seal img {
                width: 92px;
                height: 92px;
            }

            .card-header h2 {
                font-size: 31px;
            }

            .card-header p {
                font-size: 16px;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-group input {
                min-height: 54px;
                font-size: 16px;
            }

            .btn-primary {
                min-height: 56px;
                font-size: 17px;
            }

            .auth-link {
                font-size: 15px;
            }

            .site-footer {
                padding: 34px 18px 18px;
            }

            .site-footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
        }

        @media (max-width: 480px) {

 

            .login-card {
                padding: 20px 16px;
                border-radius: 16px;
                margin: 20px;
                
            }

            .login-seal img {
                width: 80px;
                height: 80px;
            }

            .card-header h2 {
                font-size: 28px;
            }

            .card-header p {
                font-size: 15px;
            }

            .site-footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @yield('styles')
</head>
<body>
    @php
        $sealLogo = file_exists(public_path('images/city_of_general_trias_seal.png'))
            ? asset('images/city_of_general_trias_seal.png')
            : asset('images/city_of_general_trias.png');
    @endphp

    <div class="left-panel">
        <div class="right-panel">
            <div class="login-card">
                <div class="login-seal">
                    <img src="{{ $sealLogo }}" alt="City of General Trias Seal">
                </div>

                <div class="card-header">
                    <span class="badge">Resident Portal</span>
                    <!-- <h3>@yield('title')</h3> -->
                    <p>@yield('subtitle')</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-error">
                        <span class="alert-icon" aria-hidden="true">!</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success') || session('status'))
                    <div class="alert alert-success">
                        <span class="alert-icon" aria-hidden="true">OK</span>
                        <span>{{ session('success') ?? session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <span class="alert-icon" aria-hidden="true">!</span>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
                @yield('auth-links')
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="site-footer-inner">
            <div class="site-footer-grid">
                <div>
                    <div class="footer-brand">
                        <!-- <img src="{{ asset('images/city_of_general_trias_seal.png')}}" alt="Logo"/> -->
                        <div>
                            <strong>PROJECT CONNECT</strong>
                            <span>City of General Trias</span>
                        </div>
                    </div>
                    <p class="footer-summary">Providing transparent, efficient, and accessible government services to all residents of Barangay San Juan I, City of General Trias City, Cavite.</p>

                    
                </div>
                <div>
                    <h4 class="footer-heading">Quick Access</h4>
                    <a href="{{ route('register') }}" class="footer-link">Register</a>
                    <a href="#about" class="footer-link">Services</a>
                    <a href="{{ route('contacts.page') }}" class="footer-link">Verifier</a>
                </div>
                <div>
                    <h4 class="footer-heading">Connect</h4>
                    <a href="https://www.facebook.com/profile.php?id=61577772153879" class="footer-link">Facebook</a>
                    <a href="https://maps.app.goo.gl/jb8Hb745vhcvAAjD9" class="footer-link">Google Maps</a>
                </div>

            </div>
            <div class="footer-bottom">
                <p>(c) 2026 City Government of General Trias, Cavite. All rights reserved.</p>
                
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
