@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    :root {
        --primary: teal;
        --primary-light: rgba(0, 128, 128, 0.1);
        --secondary: #3B82F6; /* Medium blue */
        --secondary-light: rgba(59, 130, 246, 0.1);
        --accent: #008080;
        --accent-light: rgba(96, 165, 250, 0.1);
        --success: #047857; /* Dark green */
        --success-light: rgba(4, 120, 87, 0.1);
        --danger: #B91C1C; /* Dark red */
        --danger-light: rgba(185, 28, 28, 0.1);
        --warning: #B45309; /* Dark amber */
        --warning-light: rgba(180, 83, 9, 0.1);
        --dark-bg: rgba(15, 23, 42, 0.5); /* Very dark blue with opacity */
        --border-color: rgba(30, 58, 138, 0.2); /* Dark blue border */
        --text-light: rgba(255, 255, 255, 0.7);
        --card-bg: rgba(15, 23, 42, 0.6); /* Very dark blue card background */
    }

    body {
        background-color: white;
        color: #333333;
    }
    
    .card, .container, .dashboard-container {
        background-color: white;
    }
    
    .btn-primary, 
    .nav-link.active,
    .page-item.active .page-link {
        background-color: teal !important;
        border-color: teal !important;
        color: white !important;
    }
    
    .btn-primary:hover {
        background-color: #006666 !important;
        border-color: #006666 !important;
    }
    
    .text-primary, 
    .nav-link:not(.active),
    a:not(.btn):not(.nav-link.active):not(.page-link) {
        color: teal !important;
    }
    
    .border-primary {
        border-color: teal !important;
    }
    
    .page-link {
        color: teal;
    }
    
    .page-link:hover {
        color: #006666;
    }
    
    /* If you have a sidebar or navigation */
    .sidebar, .navbar {
        background-color: white;
        color: #333333;
    }
    
    /* For any header sections */
    .header, .card-header {
        background-color: white;
        color: #333333;
        border-bottom-color: rgba(0, 128, 128, 0.2);
    }

    .dashboard-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .welcome-card {
        text-align: center;
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .welcome-card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    }
    
    .welcome-card h2 {
        font-size: 2.25rem;
        margin-bottom: 1rem;
        color: teal;
        font-weight: 600;
        letter-spacing: -0.5px;
    }
    
    .welcome-card p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        color: #666666;
    }
    
    .header-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    
    .filter-label {
        color: #666666;
        font-size: 0.95rem;
        margin-right: 0.5rem;
        font-weight: 500;
    }
    
    .control-buttons {
        display: flex;
        gap: 0.75rem;
    }
    
    .action-row {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .left-actions, .right-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .date-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .date-input {
        padding: 0.65rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        background: #f5f5f5;
        color: #333333;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .date-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(2, auto);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 14px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 4px;
        background: teal;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
    }
    
    .stat-card:hover::after {
        opacity: 1;
    }
    
    .stat-card .stat-icon {
        font-size: 1.75rem;
        color: teal;
        background: rgba(0, 128, 128, 0.1);
        height: 60px;
        width: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
        line-height: 1;
        background: linear-gradient(90deg, #333333, #006666);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card .stat-label {
        font-size: 0.95rem;
        color: #666666;
        font-weight: 500;
    }
    
    .dashboard-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .data-card {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        padding: 1.25rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .data-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .data-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
        padding-bottom: 0.75rem;
    }
    
    .data-title {
        font-size: 1.15rem;
        color: #333333;
        margin: 0;
        font-weight: 600;
    }
    
    /* Enhanced table styling for Recent Check-ins & Check-outs */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1rem;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
        background-color: white;
    }

    .data-table th {
        text-align: left;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        border-bottom: 2px solid rgba(0, 128, 128, 0.2);
        color: teal;
        background-color: rgba(0, 128, 128, 0.05);
        font-size: 0.9rem;
    }

    .data-table td {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid rgba(0, 128, 128, 0.1);
        vertical-align: middle;
        color: #444444;
    }

    /* Employee name styling */
    .data-table td:first-child {
        font-weight: 500;
        color: #333333;
    }

    /* Make the status badges match the project's color scheme */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.85rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        min-width: 100px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-present {
        background-color: rgba(0, 128, 128, 0.1);
        color: teal;
    }

    .status-paused {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status-absent {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    /* Add hover effect to rows */
    .data-table tbody tr:hover {
        background-color: rgba(0, 128, 128, 0.02);
    }

    /* Last row should not have a bottom border */
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Time column styling - make it match other teal elements */
    .data-table td:nth-child(3) {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: teal;
    }

    /* Data card header styling */
    .data-header {
        border-bottom-color: rgba(0, 128, 128, 0.2);
    }

    .data-title {
        color: teal;
    }
    
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .activity-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }
    
    .activity-item:hover {
        background-color: rgba(0, 128, 128, 0.05);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(0, 128, 128, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 0.9rem;
        color: teal;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-content p {
        margin: 0;
        font-size: 0.95rem;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #666666;
        margin-top: 0.25rem;
    }
    
    .performer-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
        transition: background-color 0.2s;
    }
    
    .performer-item:hover {
        background-color: rgba(0, 128, 128, 0.05);
    }
    
    .performer-rank {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: teal;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 1rem;
    }
    
    .performer-info {
        flex: 1;
    }
    
    .performer-name {
        font-size: 0.95rem;
        margin: 0;
        font-weight: 500;
    }
    
    .performer-position {
        font-size: 0.8rem;
        color: #666666;
        margin-top: 0.15rem;
    }
    
    .performer-value {
        font-weight: 600;
        font-size: 1rem;
        color: teal;
    }
    
    .progress-container {
        width: 100%;
        height: 8px;
        background-color: rgba(0, 128, 128, 0.1);
        border-radius: 4px;
        margin: 0.75rem 0;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease;
    }
    
    .progress-present {
        background: linear-gradient(90deg, rgba(0, 128, 128, 0.3), teal);
    }
    
    .progress-absent {
        background: linear-gradient(90deg, rgba(220, 53, 69, 0.3), #dc3545);
    }
    
    .progress-paused {
        background: linear-gradient(90deg, rgba(255, 193, 7, 0.3), #ffc107);
    }
    
    .department-item {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s;
    }
    
    .department-item:hover {
        background-color: rgba(0, 128, 128, 0.05);
    }
    
    .department-name {
        display: flex;
        justify-content: space-between;
        font-size: 0.95rem;
        margin-bottom: 0.35rem;
        font-weight: 500;
    }
    
    .department-count {
        font-weight: 600;
        color: teal;
    }
    
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: teal !important;
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        position: relative;
        overflow: hidden;
    }
    
    .action-button::after {
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
    
    .action-button:hover {
        background-color: #006666;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
    }
    
    .action-button:hover::after {
        transform: translateY(0);
    }
    
    .action-button:active {
        transform: translateY(0);
    }
    
    .action-button i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    
    .quick-stats-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        font-size: 0.95rem;
    }
    
    .refresh-btn {
        background: transparent;
        border: 1px solid rgba(0, 128, 128, 0.2);
        color: #666666;
        border-radius: 6px;
        padding: 0.35rem 0.65rem;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .refresh-btn:hover {
        background: rgba(0, 128, 128, 0.1);
        color: #333333;
        border-color: rgba(0, 128, 128, 0.3);
    }
    
    @media (max-width: 992px) {
        .dashboard-row {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .welcome-card h2 {
            font-size: 1.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .welcome-card {
            padding: 1.5rem 1rem;
        }
        
        .welcome-card h2 {
            font-size: 1.5rem;
        }
        
        .header-controls {
            flex-direction: column;
            align-items: stretch;
        }
        
        .control-buttons {
            width: 100%;
        }
        
        .action-button {
            flex: 1;
        }
    }

    /* Update any remaining action buttons with specific styles */
    #refreshDashboard, #resetDateFilter, .action-button[style] {
        background-color: teal !important;
        border-color: teal !important;
    }

    /* Enhance the last updated timestamp */
    .last-updated {
        font-size: 0.8rem;
        color: #666666;
        display: flex;
        align-items: center;
    }

    .last-updated i {
        margin-right: 0.4rem;
        color: teal;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="welcome-card">
        <h2>Attendance & Performance Overview</h2>
        <p>Real-time summary of employee attendance, productivity, and department statistics</p>

        <div class="header-controls">
            <div class="date-filter">
                <span class="filter-label"><i class="fas fa-calendar-alt"></i> Select Date:</span>
                <input type="date" id="dashboardDate" class="date-input" value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="control-buttons">
                <button id="refreshDashboard" class="action-button" style="background-color: #10b981;">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button id="resetDateFilter" class="action-button" style="background-color: #6b7280;">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
        
        <div class="action-row">
            <div class="right-actions">
                <a href="{{ route('customized.payroll') }}" class="action-button" style="background-color: #6366f1;">
                    <i class="fas fa-chart-line"></i> Payroll
                </a>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalEmployees }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.1);">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $presentToday }}</div>
                <div class="stat-label">Present Today</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.1);">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $absentToday }}</div>
                <div class="stat-label">Absent Today</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="color: #3b82f6; background: rgba(59, 130, 246, 0.1);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($avgHoursWorked, 1) }}</div>
                <div class="stat-label">Avg Hours Worked</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.1);">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $currentlyCheckedIn }}</div>
                <div class="stat-label">Currently Working</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="color: #f59e0b; background: rgba(245, 158, 11, 0.1);">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $onPause }}</div>
                <div class="stat-label">Currently On Pause</div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <div>
            <!-- Attendance Summary (Text-based representation instead of chart) -->
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Attendance Summary</h3>
                    <button class="refresh-btn" onclick="refreshAttendanceSummary()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
                <div class="quick-stats-row" style="margin-bottom: 0.75rem;">
                    <span style="font-size: 0.9rem; margin-right: 0.75rem;">
                        <i class="fas fa-user-check" style="color: #10b981;"></i> Present: {{ $presentToday }} ({{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%)
                    </span>
                    <span style="font-size: 0.9rem; margin-right: 0.75rem;">
                        <i class="fas fa-user-times" style="color: #ef4444;"></i> Absent: {{ $absentToday }} ({{ $totalEmployees > 0 ? round(($absentToday / $totalEmployees) * 100) : 0 }}%)
                    </span>
                    <span style="font-size: 0.9rem;">
                        <i class="fas fa-pause-circle" style="color: #f59e0b;"></i> On Pause: {{ $onPause }} ({{ $presentToday > 0 ? round(($onPause / $presentToday) * 100) : 0 }}%)
                    </span>
                </div>
                
                <!-- Present Progress Bar -->
                <div class="progress-container">
                    <div class="progress-bar progress-present" style="width: {{ $totalEmployees > 0 ? ($presentToday / $totalEmployees) * 100 : 0 }}%"></div>
                </div>
                
                <!-- On Pause Progress Bar (relative to present) -->
                <div class="progress-container">
                    <div class="progress-bar progress-paused" style="width: {{ $presentToday > 0 ? ($onPause / $presentToday) * 100 : 0 }}%"></div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <p style="font-size: 0.9rem; margin: 0.5rem 0;">
                        <i class="fas fa-info-circle"></i> 
                        Attendance rate today: <strong>{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</strong>
                    </p>
                    <p style="font-size: 0.9rem; margin: 0.5rem 0;">
                        <i class="fas fa-info-circle"></i> 
                        Average hours worked: <strong>{{ number_format($avgHoursWorked, 1) }} hours</strong>
                    </p>
                </div>
            </div>
            
            <!-- Department Distribution (Text-based representation instead of chart) -->
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Department Distribution</h3>
                    <button class="refresh-btn" onclick="refreshDepartmentData()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
                <div id="departmentData">
                    @foreach($departmentStats as $department => $count)
                        <div class="department-item">
                            <div class="department-name">
                                <span>{{ $department }}</span>
                                <span class="department-count">{{ $count }}</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: {{ ($count / $totalEmployees) * 100 }}%; background-color: teal;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Recent Check-ins & Check-outs</h3>
                    <span class="last-updated"><i class="fas fa-clock"></i>Last updated: <span id="lastUpdated">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span></span>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Action</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivitiesList">
                        @forelse($recentActivities ?? [] as $activity)
                            <tr>
                                <td><strong>{{ $activity->employee->name }}</strong></td>
                                <td>{{ $activity->check_out ? 'Check Out' : 'Check In' }}</td>
                                <td>{{ $activity->check_out 
                                    ? \Carbon\Carbon::parse($activity->check_out)->format('h:i A') 
                                    : \Carbon\Carbon::parse($activity->check_in)->format('h:i A') 
                                }}</td>
                                <td>
                                    @if($activity->check_out)
                                        <span class="status-badge status-paused">CHECKED OUT</span>
                                    @else
                                        <span class="status-badge status-present">PRESENT</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent activity found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div>
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">This Week's Top Performers</h3>
                    <button class="refresh-btn" onclick="refreshPerformers()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
                <div id="performersList">
                    @forelse($topPerformers ?? [] as $index => $performer)
                        <div class="performer-item">
                            <div class="performer-rank">{{ $index + 1 }}</div>
                            <div class="performer-info">
                                <p class="performer-name">{{ $performer->employee->name }}</p>
                                <p class="performer-position">{{ $performer->employee->position }}</p>
                            </div>
                            <div class="performer-value">{{ number_format($performer->hours_worked, 1) }} hrs</div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <p>No performance data available</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Department Overview</h3>
                </div>
                
                <div class="quick-stats-row" style="margin-bottom: 0.75rem;">
                    <span style="font-size: 0.9rem; margin-right: 0.75rem;">
                        <i class="fas fa-user-check" style="color: #10b981;"></i> Present: {{ $presentToday }}
                    </span>
                    <span style="font-size: 0.9rem; margin-right: 0.75rem;">
                        <i class="fas fa-user-times" style="color: #ef4444;"></i> Absent: {{ $absentToday }}
                    </span>
                    <span style="font-size: 0.9rem;">
                        <i class="fas fa-pause-circle" style="color: #f59e0b;"></i> Paused: {{ $onPause }}
                    </span>
                </div>
                
                <div class="quick-stats-row" style="margin-bottom: 0.75rem;">
                    <span style="font-size: 0.9rem; margin-right: 0.75rem;">
                        <i class="fas fa-chart-line" style="color: #3b82f6;"></i> Productivity: 85%
                    </span>
                    <span style="font-size: 0.9rem;">
                        <i class="fas fa-business-time" style="color: #8b5cf6;"></i> Working Hours: {{ number_format($avgHoursWorked * $presentToday, 1) }}
                    </span>
                </div>
                
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 0.75rem; margin-top: 0.5rem;">
                    <p style="font-size: 0.85rem; margin: 0.5rem 0;">For detailed attendance information, please visit the <a href="{{ route('employees.index') }}" style="color: #3b82f6; text-decoration: underline;">Employees section</a>.</p>
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Attendance Highlights</h3>
                </div>
                
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(16, 185, 129, 0.2); color: #10b981;">
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>IT & Technical</strong> has the highest attendance rate (92%)</p>
                            <p class="activity-time">Today</p>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">
                            <i class="fas fa-arrow-trend-down"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Operations & Logistics</strong> has the lowest attendance (68%)</p>
                            <p class="activity-time">Today</p>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(245, 158, 11, 0.2); color: #f59e0b;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="activity-content">
                            <p>Average check-in time is <strong>08:45 AM</strong></p>
                            <p class="activity-time">This week</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    document.getElementById('refreshDashboard').addEventListener('click', refreshDashboard);
    document.getElementById('resetDateFilter').addEventListener('click', resetDateFilter);
});

// Function to refresh the attendance summary
function refreshAttendanceSummary() {
    // In a real application, you would fetch updated data from the server
    // For demonstration, we'll just show a notification
    alert('Refreshing attendance data in a real application would fetch the latest data from the server.');
    updateLastUpdated();
}

// Function to refresh department data
function refreshDepartmentData() {
    // In a real application, you would fetch updated data from the server
    // For demonstration, we'll just show a notification
    alert('Refreshing department data in a real application would fetch the latest data from the server.');
    updateLastUpdated();
}

// Function to refresh performers list
function refreshPerformers() {
    // In a real application, you would fetch updated data from the server
    // For demonstration, we'll just show a notification
    alert('Refreshing performers data in a real application would fetch the latest data from the server.');
    updateLastUpdated();
}

// Function to refresh the entire dashboard
function refreshDashboard() {
    refreshAttendanceSummary();
    refreshDepartmentData();
    refreshPerformers();
    updateLastUpdated();
    alert('In a real application, all dashboard data would be refreshed from the server.');
}

// Function to reset date filter
function resetDateFilter() {
    document.getElementById('dashboardDate').value = new Date().toISOString().slice(0, 10);
    alert('Date filter reset to today. In a real application, the dashboard would reload with today\'s data.');
    updateLastUpdated();
}

// Update the last updated timestamp
function updateLastUpdated() {
    const now = new Date();
    document.getElementById('lastUpdated').textContent = now.toLocaleTimeString();
}
</script>
@endsection
