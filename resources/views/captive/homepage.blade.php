<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">
    <title>LINKED - Captive Portal</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* ══════════════════════════════════════════════════════════════════
        MODAL 
        ══════════════════════════════════════════════════════════════════ */
        .floatingPopup {
        width: 200px;
        position: fixed;
        top: 50%;
        left: 50%;
        font-family: 'DM Sans', sans-serif;
        transform: translate(-50%, -50%);
        background: white;
        backdrop-filter: blur(100px);    
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        border-radius: 10px;
        z-index: 9999;
        align-items: center;
        display: none;

        
        }

        .portal-card {
            width: 100%;
            max-width: 360px;
            background: #ffffff;
            border: 0.5px solid rgba(0, 0, 0, 0.12);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        /* Logo */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 200px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon img {
            width: 200px;
            height: auto;
            object-fit: contain;   
             
        }

        .logo-text {
            /* margin-top: 20px; */
            text-align: center;
        }

        .logo-text h1 {
            font-size: 16px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .logo-text p {
            font-size: 13px;
            color: #1a1a1a;
        }

        /* Form */
        .form-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-group label {
            font-size: 15px;
            color: #1a1a1a;
            
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 0.5px solid rgba(0, 0, 0, 0.20);
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f3;
            transition: border-color 0.15s;
        }

        .input-wrap:focus-within {
            border-color: rgba(0, 0, 0, 0.45);
            background: #ffffff;
        }

        .input-prefix {
            padding: 0 12px;
            font-size: 14px;
            color: #1a1a1a;
            border-right: 0.5px solid rgba(0, 0, 0, 0.10);
            height: 44px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            background: transparent;
        }

        .input-wrap input[type="tel"] {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0 12px;
            font-size: 15px;
            height: 44px;
            color: #1a1a1a;
            outline: none;
        }

        .input-wrap input[type="tel"]::placeholder {
            color: #bbb;
        }

        p {
            font-size: 13px;
            color: #1a1a1a;
        }

        .btn-connect {
            width: 100%;
            height: 44px;
            background: #1a1a1a;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: opacity 0.15s, transform 0.1s;
        }

        .btn-connect:hover {
            opacity: 0.88;
        }

        .btn-connect:active {
            transform: scale(0.98);
        }

        .btn-connect:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        /* Error message */
        .error-msg {
            font-size: 12px;
            color: #c0392b;
            margin-top: -4px;
        }

        /* Footer note */
        .terms {
            font-size: 11px;
            color: #aaa;
            text-align: center;
            line-height: 1.6;
        }

        .terms a {
            color: #888;
            text-decoration: underline;
        }

        .terms a:hover {
            color: #1a1a1a;
        }

        @media (max-width: 400px) {
            .portal-card {
                padding: 2rem 1.5rem;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="portal-card">

        {{-- Logo --}}
        <div class="logo-wrap">
            <div class="logo-icon">
                
                <img src="{{ asset('images/linked_logo.png') }}" alt="Linked Logo">
            </div>
            <div class="logo-text">
                <!-- <h1> LINKED </h1> -->
                <p>Public Free Wi-Fi</p>
            </div>
        </div>

        {{-- Form --}}
        <form class="form-group" method="POST" action="{{ route('captive.homepage') }}" id="portalForm">
            @csrf

            <label for="contact_number">Mobile Number</label>

            <div class="input-wrap">
                <span class="input-prefix">+63</span>
                <input
                    type="tel"
                    id="contact_number"
                    name="contact_number"
                    placeholder="9XX XXX XXXX"
                    maxlength="10"
                    inputmode="numeric"
                    pattern="[0-9]{10}"
                    value="{{ old('contact_number') }}"
                    autocomplete="tel-national"
                    required
                >
            </div>

            @error('contact_number')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <button type="submit" class="btn-connect" id="connectBtn">
                GET CODE
            </button>
        </form>

        <p style="text-align: center; font-size: 12px; color: #4d4d4d;"> 
                Connect with us on Facebook &nbsp
                <a href="https://www.facebook.com/bylocalhost" target="_blank">Click here</a>
        </p>

        <p  class="terms"> 
            <br>
                POWERED BY 
                <br>
                IMPERIAL NETWORK INC.
        </p>

    </div>

    <div id="floatingPopup" class="floatingPopup">
      <h4>SENT!</h4> <br>
      <!-- <p>
        I hereby give my consent and acknowledge the authority of Barangay San Juan I to process my personal
        information in accordance with the Data Privacy Act of 2012.
      </p> <br> -->
      <button class="btn-connect" onclick="closePopup()" style="float:right;">OK</button>
    </div>
    </div>

    <script>
        const form = document.getElementById('portalForm');
        const btn  = document.getElementById('connectBtn');
        const input = document.getElementById('contact_number');

        // Strip non-numeric characters on input
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 10);
        });

        // Disable button on submit to prevent double submission
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (!form.checkValidity()) return;

            btn.disabled = true;
            btn.textContent = 'Sending…';

            setTimeout(() => {
                document.getElementById('floatingPopup').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }, 2000);
        });

        function closePopup() {
        document.getElementById('floatingPopup').style.display = 'none';
        document.body.style.overflow = 'hidden'; 
        form.reset();
        
        btn.disabled = false;
        btn.textContent = 'GET CODE'; 
        }

      
    </script>

</body>
</html>