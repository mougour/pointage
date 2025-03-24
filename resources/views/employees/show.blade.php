@extends('layouts.app')

@section('title', 'Employee Details')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    :root {
        --primary: #1E3A8A; /* Dark blue */
        --primary-light: rgba(30, 58, 138, 0.1);
        --secondary: #3B82F6; /* Medium blue */
        --secondary-light: rgba(59, 130, 246, 0.1);
        --accent: #60A5FA; /* Light blue */
        --accent-light: rgba(96, 165, 250, 0.1);
        --success: #047857; /* Dark green */
        --success-light: rgba(4, 120, 87, 0.1);
        --danger: #B91C1C; /* Dark red */
        --danger-light: rgba(185, 28, 28, 0.1);
        --warning: #B45309; /* Dark amber */
        --warning-light: rgba(180, 83, 9, 0.1);
        --dark-bg: rgba(15, 23, 42, 0.5); /* Very dark blue with opacity */
        --input-bg: rgba(59, 130, 246, 0.1); /* Lighter blue input background */
        --input-hover-bg: rgba(15, 23, 42, 0.8); /* Darker hover background */
        --border-color: rgba(30, 58, 138, 0.2); /* Dark blue border */
        --text-light: rgba(255, 255, 255, 0.7);
        --card-bg: rgba(15, 23, 42, 0.6); /* Very dark blue card background */
    }
    
    .employee-details-container {
        background-color: white;
        color: #333333;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 1600px;
        margin: 0 auto 2rem;
    }
    
    .employee-details-container:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    }
    
    .employee-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .employee-header h2 {
        font-size: 2rem;
        color: var(--accent);
        font-weight: 600;
        letter-spacing: -0.5px;
        margin: 0;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: teal !important;
        color: white !important;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease !important;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 128, 128, 0.25) !important;
        position: relative;
        overflow: hidden;
    }

    .btn::after {
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
    
    .btn:hover {
        background-color: #006666 !important;
        border-color: #006666 !important;
        box-shadow: 0 6px 16px rgba(0, 128, 128, 0.3) !important;
        transform: translateY(-2px);
    }
    
    .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(0, 128, 128, 0.2) !important;
    }
    
    .info-section {
        margin-bottom: 1.5rem;
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        padding: 1.25rem;
    }
    
    .info-section h3 {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.6rem;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: var(--accent);
        font-weight: 600;
    }
    
    .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
        align-items: center;
    }
    
    .info-label {
        width: 180px;
        font-weight: 600;
        color: var(--text-light);
    }
    
    .info-value {
        flex: 1;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background-color: var(--success-light);
        color: var(--success);
    }
    
    .status-inactive {
        background-color: var(--danger-light);
        color: var(--danger);
    }
    
    .contract-card {
        background-color: white;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(0, 128, 128, 0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .contract-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }
    
    .attendance-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1.25rem;
        font-size: 0.95rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .attendance-table th,
    .attendance-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .attendance-table th {
        background-color: rgba(15, 23, 42, 0.5);
        font-weight: 600;
        color: var(--text-light);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .attendance-table tr:hover {
        background-color: rgba(30, 58, 138, 0.1);
    }
    
    .tab-container {
        margin-top: 2rem;
    }
    
    .tabs {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.25rem;
    }
    
    .tab {
        padding: 0.75rem 1.25rem;
        cursor: pointer;
        margin-right: 0.75rem;
        border-bottom: 2px solid transparent;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .tab:hover {
        color: var(--accent);
    }
    
    .tab.active {
        border-bottom: 2px solid var(--secondary);
        color: var(--accent);
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.1);
        border-top: none;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .tab-content.active {
        display: block;
    }

    .attendance-actions {
        background-color: white;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0, 128, 128, 0.2);
    }

    .attendance-actions h3 {
        margin-bottom: 1.25rem;
        color: var(--accent);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .attendance-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .attendance-buttons .btn {
        flex: 1;
        width: 100%;
        justify-content: center;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }

    .btn-success {
        background-color: teal !important;
        box-shadow: 0 4px 12px rgba(0, 128, 128, 0.25) !important;
    }

    .btn-success:hover {
        background-color: #006666 !important;
        box-shadow: 0 6px 16px rgba(0, 128, 128, 0.3) !important;
    }

    .btn-warning {
        background-color: #f59e0b !important;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25) !important;
    }

    .btn-warning:hover {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3) !important;
    }

    .btn-danger {
        background-color: #dc2626 !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
    }

    .btn-danger:hover {
        background-color: #b91c1c !important;
        border-color: #b91c1c !important;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3) !important;
    }

    .attendance-status {
        margin-top: 1.75rem;
        padding: 1.25rem;
        border-radius: 10px;
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.2);
    }

    .current-status {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        color: #555555 !important;
    }

    .timer {
        font-size: 1.5rem;
        font-weight: bold;
        color: teal !important;
        margin-bottom: 0.75rem;
        background: none !important;
    }

    #pauseTimeLog {
        margin-top: 1.25rem;
    }
    
    #pauseTimeLog h4 {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        color: var(--text-light);
    }

    .pause-entry {
        background: rgba(15, 23, 42, 0.5);
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Responsive styling */
    @media (max-width: 992px) {
        .employee-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .employee-details-container {
            padding: 1.5rem;
        }
        
        .attendance-buttons {
            grid-template-columns: 1fr 1fr;
            row-gap: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        .tabs {
            flex-wrap: wrap;
        }
        
        .tab {
            flex-grow: 1;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .attendance-buttons {
            grid-template-columns: 1fr;
        }
    }

    /* Toast notification styling */
    .toast {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        padding: 8px 12px;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        z-index: 9999;
        max-width: 250px;
        font-size: 0.9rem;
    }
    
    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    
    .toast-success {
        border-left: 4px solid var(--success);
    }
    
    .toast-error {
        border-left: 4px solid var(--danger);
    }
    
    .toast-icon {
        margin-right: 8px;
        font-size: 1rem;
    }
    
    .toast-success .toast-icon {
        color: var(--success);
    }
    
    .toast-error .toast-icon {
        color: var(--danger);
    }
    
    .toast-message {
        flex: 1;
    }
    
    .status-update {
        animation: pulse 0.5s ease;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .personal-info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(2, auto);
        gap: 0.8rem;
        margin-top: 1.25rem;
    }
    
    .info-card {
        display: flex;
        background-color: white;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 128, 128, 0.1);
        height: 100%;
    }
    
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 128, 128, 0.15) !important;
        background-color: rgba(0, 128, 128, 0.02) !important;
        border-color: rgba(0, 128, 128, 0.3) !important;
    }
    
    .info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background-color: rgba(0, 128, 128, 0.08) !important;
        color: teal !important;
        margin-right: 0.8rem;
        font-size: 1rem;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-content .info-label {
        font-size: 0.75rem;
        color: var(--text-light);
        margin-bottom: 0.2rem;
        font-weight: 500;
        width: auto;
    }
    
    .info-content .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: white;
    }
    
    @media (max-width: 1200px) {
        .personal-info-grid {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto;
        }
    }
    
    @media (max-width: 992px) {
        .personal-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .personal-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--border-color);
        color: white;
        border-radius: 0.375rem;
        padding: 0.65rem 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        width: 100%;
    }
    
    .form-control:hover {
        background-color: rgba(59, 130, 246, 0.2); /* Slightly darker on hover */
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        background-color: var(--input-hover-bg); /* Darker when focused */
    }

    /* Global color updates */
    .employee-details-container {
        background-color: white;
        color: #333333;
    }
    
    /* Card and section styling */
    .employee-card, 
    .employee-stats-card,
    .attendance-history-card,
    .salary-info-card {
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.2);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }
    
    /* Headers and titles */
    .employee-card-header,
    .card-header,
    .section-header {
        background-color: rgba(0, 128, 128, 0.05);
        color: teal;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .page-title,
    .employee-name,
    .card-title,
    .section-title,
    h2, h3, h4 {
        color: teal;
    }
    
    /* Text colors */
    .employee-position,
    .employee-department,
    .detail-label,
    p, span {
        color: #666666;
    }
    
    .detail-value,
    .stats-value,
    .strong-text {
        color: #333333;
    }
    
    /* Buttons */
    .btn-primary,
    .action-button,
    .edit-button,
    .back-button {
        background-color: teal !important;
        border-color: teal !important;
        color: white !important;
    }
    
    .btn-primary:hover,
    .action-button:hover,
    .edit-button:hover,
    .back-button:hover {
        background-color: #006666 !important;
        border-color: #006666 !important;
    }
    
    /* Tables */
    table th {
        background-color: rgba(0, 128, 128, 0.05);
        color: teal;
        border-bottom: 2px solid rgba(0, 128, 128, 0.2);
    }
    
    table td {
        border-bottom: 1px solid rgba(0, 128, 128, 0.1);
        color: #333333;
    }
    
    table tr:hover {
        background-color: rgba(0, 128, 128, 0.02);
    }
    
    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.85rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .status-present {
        background-color: rgba(0, 128, 128, 0.1);
        color: teal;
    }
    
    .status-absent {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .status-paused {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    /* Tabs and navigation */
    .nav-tabs .nav-link {
        color: #666666;
    }
    
    .nav-tabs .nav-link.active {
        color: teal;
        border-bottom-color: teal;
    }
    
    .nav-tabs .nav-link:hover {
        color: teal;
    }
    
    /* Progress bars */
    .progress-bar {
        background-color: teal;
    }
    
    /* Links */
    a:not(.btn):not(.nav-link) {
        color: teal;
    }
    
    a:hover:not(.btn):not(.nav-link) {
        color: #006666;
    }
    
    /* Form elements */
    input, select, textarea {
        border: 1px solid rgba(0, 128, 128, 0.2);
        background-color: white;
        color: #333333;
    }
    
    input:focus, select:focus, textarea:focus {
        border-color: teal;
        box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
    }

    /* Text color updates */

    /* Headers and section titles */
    .info-section h3,
    .card-title,
    .attendance-actions h3,
    .tab-pane h3,
    h2, h3, h4 {
        color: teal !important;
    }

    /* Regular text */
    .info-value,
    .tab-content p,
    .pause-entry,
    .form-text,
    .contract-card p {
        color: #333333 !important;
    }

    /* Labels and secondary text */
    .info-label,
    .text-muted,
    .form-label,
    #pauseTimeLog h4,
    .info-content .info-label {
        color: #666666 !important;
    }

    /* Tab navigation */
    .tab {
        color: #666666 !important;
    }

    .tab.active {
        color: teal !important;
        border-bottom-color: teal !important;
    }

    .tab:hover {
        color: teal !important;
    }

    /* Status badges updated */
    .status-active {
        background-color: rgba(0, 128, 128, 0.1) !important;
        color: teal !important;
    }

    .status-inactive {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
    }

    /* Info card values */
    .info-content .info-value {
        color: #333333 !important;
    }

    /* Table text */
    .attendance-table th {
        color: teal !important;
        background-color: rgba(0, 128, 128, 0.05) !important;
    }

    .attendance-table td {
        color: #333333 !important;
    }

    /* Attendance table hover */
    .attendance-table tr:hover {
        background-color: rgba(0, 128, 128, 0.03) !important;
    }

    /* Pause logs */
    .pause-entry {
        border-color: rgba(0, 128, 128, 0.2) !important;
        background-color: rgba(0, 128, 128, 0.02) !important;
    }

    /* Remove conflicting button after effect if it exists */
    .btn::after {
        display: none !important;
    }

    /* Add styles for attendance status panel */
    .attendance-status-panel {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background-color: rgba(0, 128, 128, 0.05);
        border: 1px solid rgba(0, 128, 128, 0.1);
        border-radius: 8px;
    }
    
    .attendance-status, .attendance-time, .attendance-pause {
        flex: 1;
        min-width: 200px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .status-label, .time-label, .pause-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6B7280;
        letter-spacing: 0.05em;
        margin-bottom: 5px;
    }
    
    .status-value, .time-value, .pause-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    /* Style for the status update animation */
    .status-update {
        animation: fadeIn 0.5s ease;
    }
    
    /* Pause log styling */
    .pause-log {
        margin-top: 1.5rem;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
    }
    
    .pause-log h4 {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        color: var(--accent);
    }
    
    .pause-entries-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid rgba(0, 128, 128, 0.1);
        border-radius: 8px;
        padding: 0.5rem;
        background-color: rgba(0, 128, 128, 0.02);
    }
    
    .pause-entry {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.1);
        border-radius: 6px;
        font-size: 0.9rem;
    }
    </style>
@endsection

@section('content')
<div class="employee-details-container">
    <div class="employee-header">
        <h2>Employee Details</h2>
        <div>
            <a href="{{ route('employees.index') }}" class="btn">
                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Back to List
            </a>
        </div>
    </div>

    <div class="info-section">
        <h3><i class="fas fa-user-circle" style="margin-right: 8px;"></i>Personal Information</h3>
        
        <div class="personal-info-grid">
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                <div class="info-content">
                    <div class="info-label">Employee ID (CIN)</div>
                    <div class="info-value">{{ $employee->cin }}</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div class="info-content">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $employee->fullName }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                <div class="info-content">
                    <div class="info-label">Gender</div>
                    <div class="info-value">{{ ucfirst($employee->gender) ?: 'Not specified' }}</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-birthday-cake"></i></div>
                <div class="info-content">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">{{ $employee->date_of_birth ? date('F d, Y', strtotime($employee->date_of_birth)) : 'Not specified' }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div class="info-content">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $employee->email }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon"><i class="fas fa-phone"></i></div>
                <div class="info-content">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $employee->phone }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-content">
                    <div class="info-label">Address</div>
                    <div class="info-value">{{ $employee->address ?: 'Not specified' }}</div>
                </div>
            </div>

            <div class="info-card" style="visibility: hidden;">
                <!-- Empty card to maintain the grid layout -->
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3><i class="fas fa-briefcase" style="margin-right: 8px;"></i>Employment Information</h3>
        
        <div class="personal-info-grid">
            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Position</div>
                    <div class="info-value">{{ $employee->position }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-building"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Department</div>
                    <div class="info-value">{{ $employee->department }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Hire Date</div>
                    <div class="info-value">{{ $employee->hireDate }}</div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Employment Status</div>
                    <div class="info-value">
                        <span class="status-badge {{ $employee->isActive ? 'status-active' : 'status-inactive' }}">
                            {{ $employee->isActive ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Salary</div>
                    <div class="info-value">{{ number_format($employee->salary, 2) }} DH</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Working Hours</div>
                    <div class="info-value">Standard (40h/week)</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-id-badge"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Employee Type</div>
                    <div class="info-value">Full-Time</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Access Level</div>
                    <div class="info-value">Standard</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Actions Section -->
    <div class="attendance-actions">
        <h3><i class="fas fa-clock" style="margin-right: 8px;"></i>Attendance Management</h3>
        <div class="attendance-status-panel">
            <div class="attendance-status">
                <div class="status-label">Current Status:</div>
                <div id="currentStatus" class="status-value">Not Checked In</div>
            </div>
            <div class="attendance-time">
                <div class="time-label">Work Time:</div>
                <div id="timeCounter" class="time-value">00:00:00</div>
            </div>
            <div class="attendance-pause">
                <div class="pause-label">Total Pause Time:</div>
                <div id="totalPauseTime" class="pause-value">00:00:00</div>
            </div>
        </div>
        <div class="attendance-buttons">
            <button id="checkInBtn" class="btn btn-success">
                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Check In
            </button>
            <button id="startPauseBtn" class="btn btn-warning" disabled>
                <i class="fas fa-pause" style="margin-right: 6px;"></i> Start Pause
            </button>
            <button id="endPauseBtn" class="btn btn-warning" disabled>
                <i class="fas fa-play" style="margin-right: 6px;"></i> End Pause
            </button>
            <button id="checkOutBtn" class="btn btn-danger" disabled>
                <i class="fas fa-sign-out-alt" style="margin-right: 6px;"></i> Check Out
            </button>
        </div>
        <div class="pause-log">
            <h4>Today's Work Summary</h4>
            <div id="pauseEntries" class="pause-entries-container">
                <!-- Pause entries will be added here dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance functionality
        const checkInBtn = document.getElementById('checkInBtn');
        const startPauseBtn = document.getElementById('startPauseBtn');
        const endPauseBtn = document.getElementById('endPauseBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');
        const currentStatusEl = document.getElementById('currentStatus');
        const timeCounterEl = document.getElementById('timeCounter');
        const totalPauseTimeEl = document.getElementById('totalPauseTime');
        const pauseEntriesEl = document.getElementById('pauseEntries');
        
        let checkInTime = null;
        let checkOutTime = null;
        let pauseStartTime = null;
        let pauseEndTime = null;
        let pauseTimes = [];
        let timerInterval = null;
        let totalWorkTime = 0;
        let totalPauseTime = 0;
        
        // Initialize based on current attendance status
        function initializeAttendance() {
            // Check if employee already has an attendance record for today
            @if(isset($todayAttendance))
                // Load check-in time if available
                @if(isset($todayAttendance->check_in))
                    // Parse check-in time with validation
                    try {
                        // Format date in ISO format to ensure proper parsing
                        const checkInISO = '{{ date("Y-m-d\TH:i:s", strtotime($todayAttendance->check_in)) }}';
                        checkInTime = new Date(checkInISO);
                        
                        // Check if date is valid
                        if (isNaN(checkInTime.getTime())) {
                            console.error('Invalid check-in date');
                            checkInTime = null;
                        }
                    } catch (e) {
                        console.error('Error parsing check-in date:', e);
                        checkInTime = null;
                    }
                    
                    // Check if employee has already checked out today
                    @if(isset($todayAttendance->check_out) && $todayAttendance->check_out)
                        // Parse check-out time with validation
                        try {
                            // Format date in ISO format to ensure proper parsing
                            const checkOutISO = '{{ date("Y-m-d\TH:i:s", strtotime($todayAttendance->check_out)) }}';
                            checkOutTime = new Date(checkOutISO);
                            
                            // Check if date is valid
                            if (isNaN(checkOutTime.getTime())) {
                                console.error('Invalid check-out date');
                                checkOutTime = null;
                            }
                        } catch (e) {
                            console.error('Error parsing check-out date:', e);
                            checkOutTime = null;
                        }
                        
                        // When both check in and check out exist, ALL buttons should be disabled
                        checkInBtn.disabled = true;
                        startPauseBtn.disabled = true;
                        endPauseBtn.disabled = true;
                        checkOutBtn.disabled = true;
                        updateStatus('Completed Today');
                        
                        // Calculate total time with validation
                        let totalTimeSeconds = 0;
                        try {
                            // Ensure dates are valid before calculation
                            if (!isNaN(checkInTime.getTime()) && !isNaN(checkOutTime.getTime())) {
                                totalTimeSeconds = Math.floor((checkOutTime - checkInTime) / 1000);
                                totalTimeSeconds = Math.max(0, totalTimeSeconds); // Ensure positive value
                            }
                        } catch (e) {
                            console.error('Error calculating time:', e);
                        }
                        totalWorkTime = totalTimeSeconds;
                        
                        @if(isset($todayAttendance->total_pause_time))
                            totalPauseTime = {{ $todayAttendance->total_pause_time * 60 }}; // convert minutes to seconds
                        @endif
                        
                        updateTimeCounter();
                        updateTotalPauseTime();
                        
                        // Format dates with validation
                        const checkInFormatted = isNaN(checkInTime.getTime()) ? 
                            'N/A' : checkInTime.toLocaleTimeString();
                        const checkOutFormatted = isNaN(checkOutTime.getTime()) ? 
                            'N/A' : checkOutTime.toLocaleTimeString();
                            
                        // Show summary with validated values
                        const summaryEntry = document.createElement('div');
                        summaryEntry.className = 'pause-entry';
                        summaryEntry.style.backgroundColor = 'rgba(79, 70, 229, 0.1)';
                        summaryEntry.style.borderColor = 'rgba(79, 70, 229, 0.2)';
                        summaryEntry.innerHTML = `
                            Check-in: ${checkInFormatted}<br>
                            Check-out: ${checkOutFormatted}<br>
                            Total Time: ${formatTime(totalWorkTime)}<br>
                            Total Pause: ${formatTime(totalPauseTime)}<br>
                            Net Work Time: ${formatTime(Math.max(0, totalWorkTime - totalPauseTime))}
                        `;
                        pauseEntriesEl.prepend(summaryEntry);
                    @else
                        // Only checked in, not checked out
                checkInBtn.disabled = true;
                startPauseBtn.disabled = false;
                checkOutBtn.disabled = false;
                
                @if(isset($isPaused) && $isPaused)
                            try {
                                // Format date in ISO format to ensure proper parsing
                                const pauseStartISO = '{{ isset($activePause->start_time) ? date("Y-m-d\TH:i:s", strtotime($activePause->start_time)) : "" }}';
                                pauseStartTime = new Date(pauseStartISO);
                                
                                // Check if date is valid
                                if (isNaN(pauseStartTime.getTime())) {
                                    console.error('Invalid pause start date');
                                    pauseStartTime = null;
                                }
                            } catch (e) {
                                console.error('Error parsing pause start date:', e);
                                pauseStartTime = null;
                            }
                            
                            if (pauseStartTime) {
                    startPauseBtn.disabled = true;
                    endPauseBtn.disabled = false;
                    updateStatus('On Pause');
                            } else {
                                updateStatus('Checked In');
                            }
                @else
                    updateStatus('Checked In');
                @endif
                
                        // Calculate elapsed time with validation
                        let calculatedWorkTime = 0;
                        if (checkInTime && !isNaN(checkInTime.getTime())) {
                const now = new Date();
                            calculatedWorkTime = Math.floor((now - checkInTime) / 1000);
                            calculatedWorkTime = Math.max(0, calculatedWorkTime); // Ensure positive value
                        }
                        totalWorkTime = calculatedWorkTime;
                
                @if(isset($todayAttendance->total_pause_time))
                    totalPauseTime = {{ $todayAttendance->total_pause_time * 60 }}; // convert minutes to seconds
                @endif
                
                updateTimeCounter();
                updateTotalPauseTime();
                startTimer();
                    @endif
            @else
                    // Attendance record exists but no check-in (unusual case)
                    updateStatus('Not Checked In');
                @endif
            @else
                // No attendance record today, enable only check-in
                checkInBtn.disabled = false;
                startPauseBtn.disabled = true;
                endPauseBtn.disabled = true;
                checkOutBtn.disabled = true;
                updateStatus('Not Checked In');
            @endif
        }
        
        // Initialize on page load
        initializeAttendance();
        
        checkInBtn.addEventListener('click', function() {
            if (!checkInTime) {
                checkInTime = new Date();
                updateStatus('Checked In');
                startPauseBtn.disabled = false;
                checkOutBtn.disabled = false;
                checkInBtn.disabled = true;
                
                // Start timer
                startTimer();
                
                // Save check-in to database via AJAX
                saveAttendance('check_in', '{{ $employee->cin }}');
            }
        });
        
        startPauseBtn.addEventListener('click', function() {
            if (!pauseStartTime) {
                pauseStartTime = new Date();
                updateStatus('On Pause');
                startPauseBtn.disabled = true;
                endPauseBtn.disabled = false;
                
                // Save pause start to database via AJAX
                saveAttendance('pause_start', '{{ $employee->cin }}');
            }
        });
        
        endPauseBtn.addEventListener('click', function() {
            if (pauseStartTime) {
                pauseEndTime = new Date();
                const pauseDuration = (pauseEndTime - pauseStartTime) / 1000; // in seconds
                
                // Add to pause times array
                pauseTimes.push({
                    start: pauseStartTime,
                    end: pauseEndTime,
                    duration: pauseDuration
                });
                
                // Update total pause time
                totalPauseTime += pauseDuration;
                updateTotalPauseTime();
                
                // Add pause entry to log
                addPauseEntry(pauseStartTime, pauseEndTime, pauseDuration);
                
                // Reset pause times
                pauseStartTime = null;
                pauseEndTime = null;
                
                // Update status
                updateStatus('Checked In');
                startPauseBtn.disabled = false;
                endPauseBtn.disabled = true;
                
                // Save pause end to database via AJAX
                saveAttendance('pause_end', '{{ $employee->cin }}');
            }
        });
        
        checkOutBtn.addEventListener('click', function() {
            if (checkInTime) {
                checkOutTime = new Date();
                
                // If there's an active pause, end it
                if (pauseStartTime) {
                    endPauseBtn.click();
                }
                
                // Update status
                updateStatus('Checked Out');
                
                // Disable buttons
                checkInBtn.disabled = false;
                startPauseBtn.disabled = true;
                endPauseBtn.disabled = true;
                checkOutBtn.disabled = true;
                
                // Stop timer
                clearInterval(timerInterval);
                
                // Save check-out to database via AJAX
                saveAttendance('check_out', '{{ $employee->cin }}');
                
                // Show summary
                const totalWorkMinutes = Math.floor(totalWorkTime / 60);
                const totalPauseMinutes = Math.floor(totalPauseTime / 60);
                const netWorkMinutes = totalWorkMinutes - totalPauseMinutes;
                
                // Add summary to pause log
                const summaryEntry = document.createElement('div');
                summaryEntry.className = 'pause-entry';
                summaryEntry.style.backgroundColor = 'rgba(79, 70, 229, 0.1)';
                summaryEntry.style.borderColor = 'rgba(79, 70, 229, 0.2)';
                summaryEntry.innerHTML = `
                    Check-in: ${checkInTime.toLocaleTimeString()}<br>
                    Check-out: ${checkOutTime.toLocaleTimeString()}<br>
                    Total Time: ${formatTime(totalWorkTime)}<br>
                    Total Pause: ${formatTime(totalPauseTime)}<br>
                    Net Work Time: ${formatTime(totalWorkTime - totalPauseTime)}
                `;
                pauseEntriesEl.prepend(summaryEntry);
                
                // Reset counters
                checkInTime = null;
                totalWorkTime = 0;
                totalPauseTime = 0;
                pauseTimes = [];
            }
        });
        
        function startTimer() {
            timerInterval = setInterval(function() {
                if (!pauseStartTime) {
                    totalWorkTime++;
                    updateTimeCounter();
                }
            }, 1000);
        }
        
        function updateTimeCounter() {
            timeCounterEl.textContent = formatTime(totalWorkTime);
        }
        
        function updateTotalPauseTime() {
            totalPauseTimeEl.textContent = formatTime(totalPauseTime);
        }
        
        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            
            return [
                h.toString().padStart(2, '0'),
                m.toString().padStart(2, '0'),
                s.toString().padStart(2, '0')
            ].join(':');
        }
        
        function updateStatus(status) {
            currentStatusEl.textContent = status;
            
            // Add visual indicator with animation
            currentStatusEl.classList.add('status-update');
            setTimeout(() => {
                currentStatusEl.classList.remove('status-update');
            }, 500);
        }
        
        function addPauseEntry(start, end, duration) {
            const entry = document.createElement('div');
            entry.className = 'pause-entry';
            
            const startTime = start.toLocaleTimeString();
            const endTime = end.toLocaleTimeString();
            const durationFormatted = formatTime(duration);
            
            entry.innerHTML = `
                <div>Pause: ${startTime} - ${endTime}</div>
                <div>Duration: ${durationFormatted}</div>
            `;
            
            pauseEntriesEl.prepend(entry);
        }
        
        function saveAttendance(action, employeeCin) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/attendance/log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    action: action,
                    cin: employeeCin,
                    timestamp: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                // Handle both response formats (success or status)
                if (data.success === true || data.status === 'success') {
                    // Display a brief success message using a toast notification
                    showToast(`Successfully recorded ${action.replace('_', ' ')}`, 'success');
                } else {
                    // Use the message property if it exists, otherwise use a default error message
                    const errorMessage = data.message || 'Unknown error occurred';
                    showToast('Error: ' + errorMessage, 'error');
                    console.error('Error:', errorMessage);
                }
            })
            .catch(error => {
                showToast('Error communicating with server', 'error');
                console.error('Error:', error);
            });
        }
        
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                </div>
                <div class="toast-message">${message}</div>
            `;
            
            // Append to body
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Auto-dismiss
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    });
    </script>
@endsection
