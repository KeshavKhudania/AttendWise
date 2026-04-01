<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | AttendWise</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --secondary: #fbbf24;
            --bg-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
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

        /* Animated decorative elements */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.05);
            filter: blur(40px);
            z-index: -1;
            animation: float 12s infinite alternate ease-in-out;
        }

        .circle-1 {
            width: 500px;
            height: 500px;
            top: -150px;
            left: -150px;
        }

        .circle-2 {
            width: 400px;
            height: 400px;
            bottom: -100px;
            right: -100px;
            animation-delay: -3s;
        }

        .circle-3 {
            width: 300px;
            height: 300px;
            top: 30%;
            right: 15%;
            animation-delay: -6s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(40px, 40px) scale(1.15);
            }
        }

        .container {
            text-align: center;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            box-shadow: 0 50px 75px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(to bottom, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .error-title {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #fff;
        }

        .error-message {
            font-size: 1.1rem;
            font-weight: 300;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
            max-width: 440px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 2.5rem;
            background: #f59e0b;
            color: #1e293b;
            text-decoration: none;
            border-radius: 1.25rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.2);
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 30px 40px -10px rgba(245, 158, 11, 0.3);
            background: #fbbf24;
        }

        .btn:active {
            transform: translateY(-2px) scale(0.98);
        }

        .icon-box {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: #f59e0b;
            display: inline-block;
            animation: swing 3s infinite ease-in-out;
        }

        @keyframes swing {

            0%,
            100% {
                transform: rotate(-5deg);
            }

            50% {
                transform: rotate(5deg);
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.6rem;
            }

            .container {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>

    <div class="container">
        <div class="icon-box">
            <i class="fas fa-plug-circle-xmark"></i>
        </div>
        <div class="error-code">500</div>
        <h1 class="error-title">Kesavvvvvv!!!!</h1>
        <p class="error-message">
            Oops! It seems our server hit a snag. Our technical team has been notified and we're working hard to get
            things back on track.
        </p>

        <a href="{{ url('/') }}" class="btn">
            <i class="fas fa-rotate"></i>
            Refresh & Restart
        </a>y
    </div>
</body>

</html>