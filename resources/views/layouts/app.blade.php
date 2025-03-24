<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PointagePro') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --bg-light: white !important; /* Change from grey to white */
            --card-bg: #FFFFFF; /* White card background */
            --border-color: rgba(0, 137, 123, 0.2); /* Teal border */
        }

        html, body {
            font-family: 'Instrument Sans', sans-serif !important;
            background-color: var(--bg-light) !important;
            color: var(--text-dark) !important;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
            width: 100%;
            max-width: 100%;
            background-color: var(--bg-light) !important;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: white !important;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 128, 128, 0.1);
            padding: 1rem 0;
        }
        
        .centered-nav {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .centered-nav ul {
            display: flex;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
            gap: 1rem;
        }
        
        .centered-nav .nav-item {
            margin: 0;
        }
        
        .centered-nav .nav-link {
            color: #555555 !important;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }
        
        .centered-nav .nav-link:hover {
            color: teal !important;
            background-color: transparent !important;
        }
        
        .centered-nav .nav-link.active {
            color: teal !important;
            background-color: transparent !important;
            font-weight: 600;
        }
        
        .centered-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: teal;
            transform: scaleX(1);
            transition: transform 0.3s ease;
        }
        
        .centered-nav .nav-link:not(.active)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: teal;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .centered-nav .nav-link:hover::after {
            transform: scaleX(1);
        }
        
        .container-fluid {
            width: 100%;
            padding-right: 2rem;
            padding-left: 2rem;
            margin-right: auto;
            margin-left: auto;
            background-color: var(--bg-light) !important;
        }
        
        .full-width-content {
            background-color: var(--bg-light) !important;
            padding: 0;
        }

        /* Card styling */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
        
        .card-header {
            background-color: rgba(0, 128, 128, 0.1) !important;
            border-bottom: 1px solid var(--border-color);
            color: teal !important;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Button styling */
        .btn {
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #006666;
            border-color: #006666;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
            color: white;
        }
        
        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
            color: var(--text-dark);
        }

        /* Form controls */
        .form-control {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-control:hover {
            border-color: var(--primary);
        }

        /* Table styling */
        table {
            background-color: var(--card-bg);
            color: var(--text-dark);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        th {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: var(--primary-light);
        }

        /* Modal styling */
        .modal-content {
            background-color: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
        }

        .modal-header {
            background-color: var(--primary-light);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        /* Alert styling */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background-color: rgba(0, 128, 128, 0.1) !important;
            color: teal !important;
            border-color: rgba(0, 128, 128, 0.2) !important;
        }

        .alert-danger {
            background-color: var(--danger-light);
            color: var(--danger);
        }

        .alert-warning {
            background-color: var(--warning-light);
            color: var(--text-dark);
        }

        /* Additional component styling */
        .employee-container, .dashboard-container {
            background-color: var(--card-bg) !important;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .employee-container:hover, .dashboard-container:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        /* Override any conflicting styles */
        .bg-white {
            background-color: var(--card-bg) !important;
        }

        .bg-light {
            background-color: var(--bg-light) !important;
        }

        .text-dark {
            color: var(--text-dark) !important;
        }

        /* Dropdown styling */
        .dropdown-menu {
            background-color: white;
            border-color: rgba(0, 128, 128, 0.1);
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            color: #555555 !important;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            color: teal !important;
            background-color: rgba(0, 128, 128, 0.05) !important;
        }
        
        .dropdown-item i {
            margin-right: 0.75rem;
            color: var(--primary);
        }

        /* Search box styling */
        .search-box {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        
        .search-box:hover {
            border-color: var(--primary);
        }
        
        .search-box:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        /* Navigation bar styling for white background and teal accents */
        .navbar-brand {
            color: teal !important;
            font-weight: 600;
        }

        .navbar-nav .nav-link {
            color: #555555 !important;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-link:focus {
            color: teal !important;
        }

        .navbar-nav .nav-link.active {
            color: teal !important;
            font-weight: 500;
        }

        /* For navbar toggle button on mobile */
        .navbar-toggler {
            border-color: rgba(0, 128, 128, 0.2);
        }

        .navbar-toggler-icon {
            background-color: teal;
        }

        /* Update any navbar buttons */
        .navbar .btn-primary {
            background-color: teal !important;
            border-color: teal !important;
        }

        .navbar .btn-primary:hover,
        .navbar .btn-primary:focus {
            background-color: #006666 !important;
            border-color: #006666 !important;
        }

        /* Ensure all containers have white background */
        body, main, .container-fluid, .full-width-content {
            background-color: white !important;
        }

        /* Make sure all cards and containers are white */
        .card, .container, .dashboard-container, .employee-container {
            background-color: white !important;
        }

        /* Update the navbar-brand color */
        .navbar-brand {
            color: teal !important;
            font-weight: 600;
        }

        /* Update the dropdown menu text colors */
        .dropdown-item {
            color: #555555 !important;
        }

        .dropdown-item:hover {
            color: teal !important;
            background-color: rgba(0, 128, 128, 0.05) !important;
        }

        /* Update any icons in the navbar */
        .navbar i {
            color: teal !important;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Success notification -->
    @if(session('success'))
    <div class="alert-floating" id="successAlert">
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <!-- Logo and Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <span class="fs-4 fw-bold">PointagePro</span>
            </a>
            
            <!-- Hamburger Button for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Centered Nav Links -->
                <div class="centered-nav d-lg-block">
                    <ul>
                        <li class="nav-item">
                            <a class="nav-link{{ request()->routeIs('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        @if(auth()->user())
                            <li class="nav-item">
                                <a class="nav-link{{ request()->routeIs('customized.payroll') ? ' active' : '' }}" href="{{ route('customized.payroll') }}">
                                    <i class="fas fa-file-invoice-dollar"></i> Payroll Statement
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ request()->routeIs('employees.index') ? ' active' : '' }}" href="{{ route('employees.index') }}">
                                    <i class="fas fa-users"></i> Employees
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                
                <!-- Right Side Navigation -->
                <div class="navbar-nav ms-auto">
                    @auth
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-4">
        <div class="container-fluid full-width-content">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-dismiss alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto dismiss success alert after 3 seconds
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(successAlert.querySelector('.alert'));
                    bsAlert.close();
                }, 3000);
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html> 