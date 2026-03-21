<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f9ff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 24px;
            max-width: 480px;
            width: 100%;
            padding: 56px 48px;
            text-align: center;
            box-shadow: 0 8px 40px rgba(26, 110, 199, 0.12);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
        }

        .pulse-icon {
            animation: pulse 2s ease-in-out infinite;
            display: block;
            font-size: 64px;
            margin-bottom: 24px;
        }

        .card h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1a6ec7;
            margin-bottom: 12px;
        }

        .card p {
            color: #5a7a9a;
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .info-box {
            background: #d9f2fc;
            border-left: 4px solid #1a6ec7;
            border-radius: 10px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 28px;
            font-size: 14px;
            color: #1a4a8a;
            line-height: 1.6;
        }

        .back-btn {
            border: 2px solid #1a6ec7;
            color: #1a6ec7;
            background: white;
            border-radius: 12px;
            padding: 12px 28px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: #1a6ec7;
            color: white;
        }

        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 25, 47, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .popup-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(26, 110, 199, 0.18);
        }

        .popup-card h2 {
            color: #1a6ec7;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .popup-card p {
            color: #45607c;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .popup-btn {
            border: none;
            border-radius: 10px;
            background: #1a6ec7;
            color: #ffffff;
            font-weight: 700;
            padding: 10px 16px;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    @if (session('just_registered'))
        <div class="popup-overlay" id="pendingPopup" role="dialog" aria-modal="true" aria-labelledby="pendingPopupTitle">
            <div class="popup-card">
                <h2 id="pendingPopupTitle">Registration Submitted</h2>
                <p>{{ session('pending_message', 'Wait for Barangay Official approval before you can log in.') }}</p>
                <button type="button" class="popup-btn" id="closePendingPopup">OK, I Understand</button>
            </div>
        </div>
    @endif

    <div class="card">
        <span class="pulse-icon">⏳</span>
        <h1>Account Pending Approval</h1>
        <p>Your registration has been received and is currently being reviewed by a Barangay Official.</p>

        <div class="info-box">
            📧 You will receive an email notification at your registered address once your account is approved.
        </div>

        <a href="{{ route('login') }}" class="back-btn">← Back to Login</a>
    </div>

    @if (session('just_registered'))
        <script>
            const popup = document.getElementById('pendingPopup');
            const closePopupBtn = document.getElementById('closePendingPopup');

            closePopupBtn.addEventListener('click', function () {
                popup.classList.add('hidden');
            });
        </script>
    @endif
</body>
</html>
