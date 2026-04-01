<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired | AttendWise</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #94a3b8;
            --secondary: #64748b;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --glass: rgba(255, 255, 255, 0.7);
            --border: rgba(148, 163, 184, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-gradient);
            color: #1e293b;
            overflow: hidden;
            position: relative;
        }

        .container {
            text-align: center;
            padding: 4rem;
            max-width: 550px;
            width: 90%;
            background: var(--glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 3rem;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.05);
            animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .error-code {
            font-size: 7.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #64748b;
            line-height: 1;
            opacity: 0.2;
        }

        .error-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .error-message {
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 3.5rem;
            color: #64748b;
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 2.5rem;
            background: #1e293b;
            color: white;
            text-decoration: none;
            border-radius: 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-5px);
            background: #334155;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(-2px);
        }

        .icon-box {
            font-size: 5rem;
            margin-bottom: 2rem;
            color: #64748b;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 5.5rem;
            }

            .error-title {
                font-size: 1.8rem;
            }

            .container {
                padding: 3rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon-box">
            <i class="fas fa-clock-rotate-left"></i>
        </div>
        <div class="error-code">419</div>
        <h1 class="error-title">Session Expired</h1>
        <p class="error-message">
            Your login session has timed out or the security token was expired.
            Simply refresh the page to continue your journey.
        </p>

        <a href="{{ url()->previous() }}" class="btn">
            <i class="fas fa-arrow-rotate-right"></i>
            Refresh & Resume
        </a>
    </div>
</body>

</html>