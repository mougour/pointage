@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="welcome-card">
        <h2>Attendance & Performance Overview</h2>
        <p>Real-time summary of employee attendance, productivity, and department statistics</p>

        <div class="flex items-center justify-between mb-6 p-4 rounded-lg shadow-md" style="background-color: white;">
            <div class="flex items-center space-x-4">
                <div>
                    <label for="period-selector" class="block text-sm font-medium text-gray-700 mb-1">Select Period:</label>
                    <select id="period-selector" class="border border-gray-300 rounded-md px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="this_quarter">This Quarter</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="refresh-btn" class="action-button" style="background-color: teal !important; color: white !important; font-weight: 600 !important;">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
                <button id="reset-btn" class="action-button" style="background-color: teal !important; color: white !important; font-weight: 600 !important;">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
                <button id="export-btn" class="action-button" style="background-color: teal !important; color: white !important; font-weight: 600 !important;">
                    <i class="fas fa-file-export mr-2"></i>Export
                </button>
            </div>
        </div>
        
        <div class="action-row">
            <div class="right-actions">
                <button id="viewReports" class="action-button reports-btn">
                    <i class="fas fa-chart-bar"></i> Reports
                </button>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalEmployees }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $presentToday }}</div>
                <div class="stat-label">Present Today</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-danger">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $absentToday }}</div>
                <div class="stat-label">Absent Today</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($avgHoursWorked, 1) }}</div>
                <div class="stat-label">Avg Hours Worked</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $currentlyCheckedIn }}</div>
                <div class="stat-label">Currently Working</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-warning">
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
                
                <div class="quick-stats-row">
                    <span class="stat-item">
                        <i class="fas fa-user-check icon-success-text"></i> Present: {{ $presentToday }} ({{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%)
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-user-times icon-danger-text"></i> Absent: {{ $absentToday }} ({{ $totalEmployees > 0 ? round(($absentToday / $totalEmployees) * 100) : 0 }}%)
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-pause-circle icon-warning-text"></i> On Pause: {{ $onPause }} ({{ $presentToday > 0 ? round(($onPause / $presentToday) * 100) : 0 }}%)
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
                
                <div class="info-section">
                    <p class="info-text">
                        <i class="fas fa-info-circle"></i> 
                        Attendance rate today: <strong>{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</strong>
                    </p>
                    <p class="info-text">
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
                                <div class="progress-bar progress-department" style="width: {{ ($count / $totalEmployees) * 100 }}%;"></div>
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
                
                <div class="quick-stats-row">
                    <span class="stat-item">
                        <i class="fas fa-user-check icon-success-text"></i> Present: {{ $presentToday }}
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-user-times icon-danger-text"></i> Absent: {{ $absentToday }}
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-pause-circle icon-warning-text"></i> Paused: {{ $onPause }}
                    </span>
                </div>
                
                <div class="quick-stats-row">
                    <span class="stat-item">
                        <i class="fas fa-chart-line icon-info-text"></i> Productivity: 85%
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-business-time icon-purple-text"></i> Working Hours: {{ number_format($avgHoursWorked * $presentToday, 1) }}
                    </span>
                </div>
                
                <div class="info-footer">
                    <p class="footer-text">For detailed attendance information, please visit the <a href="{{ route('employees.index') }}" class="info-link">Employees section</a>.</p>
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">Attendance Highlights</h3>
                </div>
                
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon icon-success-bg">
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>IT & Technical</strong> has the highest attendance rate (92%)</p>
                            <p class="activity-time">Today</p>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon icon-danger-bg">
                            <i class="fas fa-arrow-trend-down"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Operations & Logistics</strong> has the lowest attendance (68%)</p>
                            <p class="activity-time">Today</p>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon icon-warning-bg">
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

<!-- Include dashboard JavaScript -->
<script src="{{ asset('js/dashboard.js') }}"></script>
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
