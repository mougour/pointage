@extends('layouts.app')

@section('title', 'Paycheck Analysis')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payroll-analysis.css') }}">
<style>
    .analysis-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .chart-card {
        background-color: white;
        border-radius: 12px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .chart-title {
        font-size: 1.25rem;
        color: teal;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    .comparison-card {
        background-color: rgba(0, 128, 128, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(0, 128, 128, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .comparison-title {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .comparison-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: teal;
        margin-bottom: 0.5rem;
    }
    
    .comparison-change {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .change-positive {
        color: #10b981;
    }
    
    .change-negative {
        color: #ef4444;
    }
    
    .department-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 128, 128, 0.1);
    }
    
    .department-row:last-child {
        border-bottom: none;
    }
    
    .department-name {
        font-weight: 500;
        color: #333;
    }
    
    .department-cost {
        font-weight: 600;
        color: teal;
    }
    
    .bar-chart {
        height: 200px;
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 128, 128, 0.1);
    }
    
    .bar {
        flex: 1;
        background-color: teal;
        border-radius: 6px 6px 0 0;
        min-width: 30px;
        position: relative;
        transition: height 0.3s ease;
    }
    
    .bar-label {
        position: absolute;
        bottom: -25px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.8rem;
        color: #666;
        white-space: nowrap;
    }
    
    .bar-value {
        position: absolute;
        top: -25px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 500;
        color: teal;
    }
    
    .payroll-component {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 128, 128, 0.1);
    }
    
    .payroll-component:last-child {
        border-bottom: none;
    }
    
    .component-name {
        font-weight: 500;
        color: #333;
    }
    
    .component-value {
        font-weight: 600;
    }
    
    .component-value.positive {
        color: #10b981;
    }
    
    .component-value.negative {
        color: #ef4444;
    }
    
    .filter-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        background-color: white;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(0, 128, 128, 0.2);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        align-items: center;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-label {
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .filter-select {
        padding: 0.5rem 1rem;
        border: 1px solid rgba(0, 128, 128, 0.2);
        border-radius: 6px;
        color: #333;
        background-color: white;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: teal;
        box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
    }
    
    .flex-spacer {
        flex-grow: 1;
    }
    
    .action-btn {
        background-color: teal;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        background-color: #006666;
        transform: translateY(-1px);
    }
    
    .analytics-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .donut-chart {
        display: flex;
        justify-content: center;
    }
    
    .donut-placeholder {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: conic-gradient(
            teal 0% 30%, 
            #10b981 30% 52%, 
            #3b82f6 52% 65%, 
            #8b5cf6 65% 78%, 
            #ef4444 78% 100%
        );
        position: relative;
    }
    
    .donut-placeholder::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120px;
        height: 120px;
        background-color: white;
        border-radius: 50%;
    }
    
    .donut-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        z-index: 1;
    }
    
    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
        justify-content: center;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }
    
    .legend-label {
        font-size: 0.9rem;
        color: #666;
    }
</style>
@endsection

@section('content')
<div class="analysis-container">
    <h2 class="page-title mb-4">Paycheck Analysis Dashboard</h2>
    
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">View By:</span>
            <select class="filter-select">
                <option value="month">Monthly</option>
                <option value="quarter">Quarterly</option>
                <option value="year">Yearly</option>
            </select>
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Period:</span>
            <select class="filter-select">
                <option value="current">Current ({{ date('F Y') }})</option>
                <option value="previous">Previous</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Department:</span>
            <select class="filter-select">
                <option value="all">All Departments</option>
                @foreach($departments as $department)
                <option value="{{ str_replace(' ', '_', strtolower($department)) }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex-spacer"></div>
        
        <button class="action-btn">
            <i class="fas fa-file-export"></i> Export Report
        </button>
    </div>
    
    <div class="chart-card mb-4">
        <h3 class="chart-title">YTD Payroll Comparison</h3>
        <div class="comparison-card">
            <h4 class="comparison-title">Total Payroll ({{ $ytdComparison['year'] }})</h4>
            <div class="comparison-value">${{ number_format($ytdComparison['total_payroll'], 2) }}</div>
            <div class="comparison-change {{ $ytdComparison['percentage_change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                <i class="fas {{ $ytdComparison['percentage_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($ytdComparison['percentage_change']) }}% from {{ $ytdComparison['previous_year'] }}
            </div>
        </div>
    </div>
    
    <div class="summary-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Employees</div>
                <div class="stat-icon icon-primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $payrollSummary['total_employees'] }}</div>
            <div class="stat-subtitle">Receiving salary this month</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Average Salary</div>
                <div class="stat-icon icon-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-value">${{ number_format($payrollSummary['average_salary'], 2) }}</div>
            <div class="stat-subtitle">Per employee</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Overtime Cost</div>
                <div class="stat-icon icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">${{ number_format($payrollSummary['overtime_cost'], 2) }}</div>
            <div class="stat-subtitle">{{ $payrollSummary['overtime_hours'] }} hours total</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Net Payroll</div>
                <div class="stat-icon icon-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="stat-value">${{ number_format($payrollSummary['net_payroll'], 2) }}</div>
            <div class="stat-subtitle">After taxes and deductions</div>
        </div>
    </div>
    
    <div class="analytics-row">
        <div class="chart-card">
            <h3 class="chart-title">Monthly Payroll Trend</h3>
            <div class="chart-container">
                <div class="bar-chart">
                    @php
                        $max = max($monthlyTrends ?: [0]);
                    @endphp
                    
                    @foreach($monthlyTrends as $month => $amount)
                        @php
                            $shortMonth = substr($month, 0, 3);
                            $height = $max > 0 ? (($amount / $max) * 100) : 0;
                        @endphp
                        
                        <div class="bar" style="height: {{ $height }}%;">
                            <span class="bar-value">${{ number_format($amount > 0 ? ($amount / 1000) : 0, 0) }}k</span>
                            <span class="bar-label">{{ $shortMonth }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="chart-card">
            <h3 class="chart-title">Department Cost Distribution</h3>
            <div class="department-chart">
                @foreach($departmentCosts as $department => $cost)
                <div class="department-row">
                    <div class="department-name">{{ $department }}</div>
                    <div class="department-cost">${{ number_format($cost, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="analytics-row">
        <div class="chart-card">
            <h3 class="chart-title">Payroll Components</h3>
            <div class="payroll-components">
                @foreach($payrollComponents as $component => $amount)
                <div class="payroll-component">
                    <div class="component-name">{{ $component }}</div>
                    <div class="component-value {{ $amount >= 0 ? 'positive' : 'negative' }}">
                        {{ $amount >= 0 ? '+' : '' }}${{ number_format(abs($amount), 2) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="chart-card">
            <h3 class="chart-title">Tax Breakdown</h3>
            <div class="donut-chart">
                <div class="donut-placeholder">
                    <span class="donut-label">Tax Distribution</span>
                </div>
            </div>
            <div class="legend">
                @php
                    $colors = ['teal', '#10b981', '#3b82f6', '#8b5cf6', '#ef4444'];
                    $i = 0;
                @endphp
                
                @foreach($taxationData as $taxType => $amount)
                <div class="legend-item">
                    <div class="legend-color" style="background-color: {{ $colors[$i] }};"></div>
                    <div class="legend-label">{{ $taxType }}</div>
                </div>
                @php $i++; @endphp
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Top Earners</h3>
        <div class="table-responsive">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topEarners as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee['name'] }}</td>
                        <td>{{ $employee['position'] }}</td>
                        <td>{{ $employee['department'] }}</td>
                        <td>${{ number_format($employee['salary'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Recent Payments</h3>
        <div class="table-responsive">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayments as $payment)
                    <tr>
                        <td>{{ $payment['employee'] }}</td>
                        <td>{{ date('M d, Y', strtotime($payment['date'])) }}</td>
                        <td>${{ number_format($payment['amount'], 2) }}</td>
                        <td>
                            <span class="status-badge status-paid">{{ $payment['status'] }}</span>
                        </td>
                        <td>
                            <a href="#" class="action-button">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/payroll-analysis.js') }}"></script>
@endsection 