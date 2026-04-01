<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | AttendWise</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
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
            background: rgba(255, 255, 255, 0.1);
            filter: blur(40px);
            z-index: -1;
            animation: float 10s infinite alternate ease-in-out;
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
        }

        .circle-2 {
            width: 300px;
            height: 300px;
            bottom: -50px;
            right: -50px;
            animation-delay: -2s;
        }

        .circle-3 {
            width: 200px;
            height: 200px;
            top: 20%;
            right: 10%;
            animation-delay: -5s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(30px, 30px) scale(1.1);
            }
        }

        .container {
            text-align: center;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            background: var(--glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            margin-bottom: 0;
            background: linear-gradient(to bottom, #fff, rgba(255, 255, 255, 0.4));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.95);
        }

        .error-message {
            font-size: 1.1rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: white;
            color: #6366f1;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            background: #f8fafc;
        }

        .btn:active {
            transform: translateY(0);
        }

        .icon-box {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.5rem;
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
            <i class="fas fa-map-signs"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            It looks like the path you're trying to reach doesn't exist.
            The link might be broken or the page has been moved.
        </p>

        <a href="{{ url('/') }}" class="btn">
            <i class="fas fa-home"></i>
            Back to Dashboard
        </a>
    </div>
</body>

</html>