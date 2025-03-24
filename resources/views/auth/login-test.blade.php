<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PointagePro') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        :root {
            --primary: teal; /* Changed to teal */
            --primary-light: rgba(0, 128, 128, 0.1); /* Adjusted for teal */
            --secondary: #6C757D; /* Muted Gray */
            --secondary-light: rgba(108, 117, 125, 0.1);
            --accent: #008080; /* Changed to teal */
            --accent-light: rgba(0, 128, 128, 0.1); /* Adjusted for teal */
            --success: #28A745; /* Green */
            --success-light: rgba(40, 167, 69, 0.1);
            --danger: #DC3545; /* Red */
            --danger-light: rgba(220, 53, 69, 0.1);
            --warning: #FFC107; /* Yellow */
            --warning-light: rgba(255, 193, 7, 0.1);
            --dark-bg: rgba(52, 58, 64, 0.5); /* Dark gray with opacity */
            --input-bg: rgba(255, 255, 255, 0.1); /* White input background */
            --input-hover-bg: rgba(255, 255, 255, 0.2); /* Lighter hover background */
            --border-color: rgba(0, 137, 123, 0.2); /* Teal border */
            --text-light: rgba(255, 255, 255, 0.7);
            --card-bg: white; /* Changed to solid white */
        }
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white; /* Changed to solid white background */
            color: #333333; /* Changed text color for better contrast on white */
            margin: 0;
            padding: 0;
        }

        .login-container {
            background-color: white; /* Solid white background */
            padding: 1.75rem 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
            max-height: 100vh;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-container:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
            transform: translateY(-3px);
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 1rem;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: teal; /* Changed to teal for better visibility on white */
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #666666; /* Darker text for better visibility on white */
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #666666; /* Darker text for better visibility on white */
            margin-bottom: 0.5rem;
        }

        .input-container {
            position: relative;
        }

        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            padding-left: 2.5rem;
            background-color: #f5f5f5; /* Light gray background for inputs */
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            color: #333333; /* Dark text for better visibility */
            transition: all 0.3s ease;
            font-family: 'Instrument Sans', sans-serif;
        }

        input[type="email"]:hover,
        input[type="password"]:hover {
            background-color: rgba(59, 130, 246, 0.2); /* Slightly darker on hover */
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background-color: var(--input-hover-bg); /* Darker when focused */
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin: 0.75rem 0 1.25rem;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            accent-color: var(--secondary);
        }

        .remember-me label {
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #666666; /* Changed from var(--text-light) to match other text */
        }

        button {
            width: 100%;
            background-color: teal; /* Changed to teal */
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.35);
            position: relative;
            overflow: hidden;
            font-family: 'Instrument Sans', sans-serif;
        }
        
        button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(100%);
            transition: transform 0.2s ease;
        }

        button:hover {
            background-color: #006666; /* Darker teal on hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.5);
        }
        
        button:hover::after {
            transform: translateY(0);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        button i {
            margin-right: 8px;
            color: var(--accent);
        }

        .error-message {
            color: white;
            background-color: var(--danger-light);
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid rgba(185, 28, 28, 0.3);
            font-size: 0.85rem;
        }
        
        .error-message ul {
            list-style-position: inside;
            margin-top: 0.3rem;
        }

        .success-message {
            color: white;
            background-color: var(--success-light);
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid rgba(4, 120, 87, 0.3);
            font-size: 0.85rem;
        }

        .button-container {
            margin-top: 0.75rem;
        }
        
        @media (max-height: 600px) {
            .login-container {
                padding: 1.25rem 1.5rem;
            }
            
            .brand-logo {
                margin-bottom: 0.75rem;
                font-size: 1.25rem;
            }
            
            .login-header {
                margin-bottom: 1rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
                margin-bottom: 0.3rem;
            }
            
            .form-group {
                margin-bottom: 0.75rem;
            }
            
            .remember-me {
                margin: 0.5rem 0 0.75rem;
            }
        }

        .login-button, 
        .submit-button {
            background-color: teal;
            color: white;
        }

        .login-button:hover,
        .submit-button:hover {
            background-color: #006666; /* Darker teal for hover state */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <span class="fs-4 fw-bold text-primary">PointagePro</span>
        </div>
        
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please sign in to your account</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>
                <span>Please correct the following errors:</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-container">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <div class="button-container">
                <button type="submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
