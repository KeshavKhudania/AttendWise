<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Permission Denied | AttendWise</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #ef4444;
            --primary-dark: #b91c1c;
            --bg-gradient: linear-gradient(135deg, #450a0a 0%, #171717 100%);
            --glass: rgba(239, 68, 68, 0.05);
            --glass-border: rgba(239, 68, 68, 0.15);
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
            color: white;
            overflow: hidden;
            position: relative;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.05);
            filter: blur(50px);
            z-index: -1;
            animation: pulse 8s infinite alternate ease-in-out;
        }

        .circle-1 {
            width: 450px;
            height: 450px;
            top: -100px;
            left: -100px;
        }

        .circle-2 {
            width: 400px;
            height: 400px;
            bottom: -50px;
            right: -50px;
            animation-delay: -3s;
        }

        @keyframes pulse {
            0% {
                opacity: 0.3;
                transform: scale(1);
            }

            100% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        .container {
            text-align: center;
            padding: 4rem 3rem;
            max-width: 550px;
            width: 90%;
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            box-shadow: 0 40px 60px -15px rgba(0, 0, 0, 0.6);
            animation: emerge 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes emerge {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .error-code {
            font-size: 7.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ef4444;
            line-height: 1;
            text-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            letter-spacing: -0.5px;
        }

        .error-message {
            font-size: 1.1rem;
            font-weight: 300;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: #ef4444;
            color: white;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.2);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.4);
            background: #f87171;
        }

        .icon-box {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            color: #ef4444;
            animation: shake 2s infinite ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: scale(1);
            }

            10%,
            20% {
                transform: scale(0.9) rotate(-3deg);
            }

            30%,
            50%,
            70%,
            90% {
                transform: scale(1.1) rotate(3deg);
            }

            40%,
            60%,
            80% {
                transform: scale(1.1) rotate(-3deg);
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 5.5rem;
            }

            .error-title {
                font-size: 1.6rem;
            }

            .container {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="container">
        <div class="icon-box">
            <i class="fas fa-lock text-danger"></i>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Access Denied</h1>
        <p class="error-message">
            You don't have the necessary permissions to access this area.
            Please contact your administrator if you believe this is a mistake.
        </p>

        <a href="{{ url('/') }}" class="btn">
            <i class="fas fa-arrow-left"></i>
            Go Back Securely
        </a>
    </div>
</body>

</html>