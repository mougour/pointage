<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Figtree', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #00897B 0%, #26A69A 100%);
                color: #ffffff;
            }

            .container {
                width: 100%;
                max-width: 420px;
                margin: 0 auto;
            }

            .logo {
                margin-bottom: 2rem;
                text-align: center;
            }

            .glass-card {
                position: relative;
                width: 100%;
                max-width: 450px;
                padding: 2rem;
                border-radius: 15px;
                background-color: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="glass-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html> 