<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PointagePro') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
        :root {
            --primary: #00897B; /* Teal */
            --primary-light: rgba(0, 137, 123, 0.1);
            --secondary: #6C757D; /* Muted Gray */
            --secondary-light: rgba(108, 117, 125, 0.1);
            --accent: #26A69A; /* Lighter Teal */
            --accent-light: rgba(38, 166, 154, 0.1);
            --success: #28A745; /* Green */
            --success-light: rgba(40, 167, 69, 0.1);
            --danger: #DC3545; /* Red */
            --danger-light: rgba(220, 53, 69, 0.1);
            --warning: #FFC107; /* Yellow */
            --warning-light: rgba(255, 193, 7, 0.1);
            --text-dark: #343A40; /* Dark Gray */
            --text-light: #6C757D; /* Secondary gray */
            --bg-light: #F8F9FA; /* Light background */
            --card-bg: #FFFFFF; /* White card background */
            --border-color: rgba(0, 137, 123, 0.2); /* Teal border */
        }

    body {
        font-family: 'Instrument Sans', sans-serif !important;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%) !important;
        color: var(--text-dark);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .welcome-header {
            margin-bottom: 3rem;
        }

        .welcome-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .welcome-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-light);
            line-height: 1.6;
        }

        .cta-section {
            margin-top: 4rem;
        }

        .cta-button {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            background-color: var(--primary-light);
            color: var(--primary);
        }

    .login-card {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        margin: 2rem auto;
        border: 1px solid var(--border-color);
    }

        .login-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--card-bg);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #006666;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 1rem;
        }

        .forgot-password a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 1rem;
            }
    }
</style>
</head>
<body>
    <div class="container">
        <div class="welcome-header">
            <h1 class="welcome-title">Welcome to PointagePro</h1>
            <p class="welcome-subtitle">Streamline your employee attendance management with our modern and efficient solution</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="feature-title">Easy Time Tracking</h3>
                <p class="feature-description">Simple and intuitive interface for tracking employee attendance and working hours</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Real-time Analytics</h3>
                <p class="feature-description">Get instant insights into attendance patterns and workforce management</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 class="feature-title">Payroll Integration</h3>
                <p class="feature-description">Seamlessly integrate attendance data with your payroll system</p>
            </div>
        </div>

        <div class="login-card">
            <h2 class="login-title">Login to Your Account</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>

        <div class="cta-section">
            <a href="{{ route('register') }}" class="cta-button">
                <i class="fas fa-user-plus"></i> Create New Account
            </a>
        </div>
    </div>
</body>
</html>
