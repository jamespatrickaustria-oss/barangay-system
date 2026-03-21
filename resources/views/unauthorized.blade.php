<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
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
            box-shadow: 0 8px 40px rgba(26, 111, 204, 0.12);
        }

        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-10deg);
            }
            75% {
                transform: rotate(10deg);
            }
        }

        .shake-icon {
            animation: shake 0.5s ease-in-out infinite;
            display: block;
            font-size: 64px;
            margin-bottom: 24px;
        }

        .card h1 {
            font-size: 28px;
            font-weight: 800;
            color: #dc3545;
            margin-bottom: 12px;
        }

        .card p {
            color: #5a7a9a;
            font-size: 15px;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .back-btn {
            border: 2px solid #dc3545;
            color: #dc3545;
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
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="shake-icon">🔒</span>
        <h1>Access Denied</h1>
        <p>You do not have permission to access this page.</p>

        <a href="javascript:history.back()" class="back-btn">← Go Back</a>
    </div>
</body>
</html>
