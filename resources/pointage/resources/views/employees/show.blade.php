@extends('layouts.app')

@section('content')
<div class="container">
    <div class="employee-details">
        <div class="header">
            <h1>{{ $employee->name }}</h1>
            <div class="header-actions">
                <button onclick="showHoursModal()" class="btn btn-primary">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Show Stats
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="info-section">
            <h2>Employee Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ $employee->email }}</span>
                </div>
                <div class="info-item">
                    <label>Department:</label>
                    <span>{{ $employee->department }}</span>
                </div>
                <div class="info-item">
                    <label>Position:</label>
                    <span>{{ $employee->position }}</span>
                </div>
                <div class="info-item">
                    <label>Hire Date:</label>
                    <span>{{ $employee->hire_date }}</span>
                </div>
            </div>
        </div>

        <div class="attendance-section">
            <h2>Today's Attendance</h2>
            <div class="attendance-status">
                @if($attendance)
                    <div class="time-info">
                        <p>Check In: {{ $attendance->check_in->format('h:i A') }}</p>
                        @if($attendance->check_out)
                            <p>Check Out: {{ $attendance->check_out->format('h:i A') }}</p>
                        @endif
                    </div>
                    @if(!$attendance->check_out)
                        <form action="{{ route('employees.checkout', $employee) }}" method="POST" class="attendance-form">
                            @csrf
                            <button type="submit" class="btn btn-danger">Check Out</button>
                        </form>
                    @endif
                @else
                    <p class="status-absent">Not Present Today</p>
                    <form action="{{ route('employees.checkin', $employee) }}" method="POST" class="attendance-form">
                        @csrf
                        <button type="submit" class="btn btn-success">Check In</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="activity-list">
                @forelse($recentActivity as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            @if($activity->check_out)
                                <span class="icon-checkout">✓</span>
                            @else
                                <span class="icon-checkin">✓</span>
                            @endif
                        </div>
                        <div class="activity-details">
                            <p>{{ $activity->check_out ? 'Checked Out' : 'Checked In' }}</p>
                            <span class="activity-time">{{ $activity->check_out ? $activity->check_out->format('M d, Y h:i A') : $activity->check_in->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="no-activity">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Hours Calculation Modal -->
<div id="hoursModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h2>Employee Statistics</h2>
            <button class="close-button" onclick="closeHoursModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="calculation-section">
                <h3>Select Period</h3>
                <form id="hoursForm" onsubmit="calculateHours(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Start Date:</label>
                            <input type="date" id="startDate" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date:</label>
                            <input type="date" id="endDate" name="end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Show Statistics</button>
                </form>
            </div>

            <div id="hoursResult" class="hours-result">
                <div class="employee-info">
                    <h3>Informations de l'Employé</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nom:</label>
                            <span>{{ $employee->name }}</span>
                        </div>
                        <div class="info-item">
                            <label>Poste:</label>
                            <span>{{ $employee->position }}</span>
                        </div>
                        <div class="info-item">
                            <label>Département:</label>
                            <span>{{ $employee->department }}</span>
                        </div>
                        <div class="info-item">
                            <label>Période:</label>
                            <span id="periodDate">-</span>
                        </div>
                    </div>
                </div>

                <div class="salary-calculation">
                    <h3>Salary Calculation</h3>
                    <div class="position-info">
                        <p><strong>Position:</strong> {{ $employee->position }}</p>
                        <p><strong>Department:</strong> {{ $employee->department }}</p>
                        <p><strong>Regular Hours per Day:</strong> 8 hours</p>
                        <p><strong>Hourly Rate:</strong> {{ $salaryData['hourly_rate'] }} DH</p>
                        <p><strong>Overtime Rate:</strong> {{ $salaryData['overtime_rate'] }} DH</p>
                    </div>
                </div>

                <div id="salaryResult" class="salary-result" style="display: none;">
                    <h3>Détail du Salaire</h3>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="label">Heures Normales:</span>
                            <span class="value" id="regularHoursTotal">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Heures Supplémentaires:</span>
                            <span class="value" id="overtimeHours">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Salaire Normal:</span>
                            <span class="value" id="regularPay">0 DH</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Heures Supplémentaires:</span>
                            <span class="value" id="overtimePay">0 DH</span>
                        </div>
                        <div class="summary-item total">
                            <span class="label">Salaire Total:</span>
                            <span class="value" id="totalSalary">0 DH</span>
                        </div>
                    </div>
                </div>

                <div class="daily-breakdown">
                    <h3>Détail Journalier</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Heures</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.daily_breakdown.map(day => `
                                    <tr>
                                        <td>${day.date}</td>
                                        <td>${day.hours}</td>
                                        <td>${day.hours > 8 ? 'Heures Supp.' : 'Normal'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.employee-details {
    background: #fff;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-width: 1000px;
    margin: 40px auto;
}

.header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 40px;
    text-align: center;
    padding-bottom: 30px;
    border-bottom: 2px solid #f0f2f5;
}

.header h1 {
    color: #1a237e;
    margin: 0 0 20px 0;
    font-size: 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.header-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background-color: #1a237e;
    color: white;
}

.btn-secondary {
    background-color: #455a64;
    color: white;
}

.btn-success {
    background-color: #2e7d32;
    color: white;
}

.btn-danger {
    background-color: #c62828;
    color: white;
}

.info-section, .attendance-section, .recent-activity {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid #f0f2f5;
}

h2 {
    color: #1a237e;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f2f5;
    font-size: 20px;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.info-item {
    display: flex;
    flex-direction: column;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f0f2f5;
    transform: translateY(-2px);
}

.info-item label {
    color: #455a64;
    font-size: 0.9em;
    margin-bottom: 8px;
    font-weight: 500;
}

.info-item span {
    color: #1a237e;
    font-weight: 600;
    font-size: 1.1em;
}

.attendance-status {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    margin-top: 20px;
    border: 1px solid #e0e0e0;
}

.time-info {
    margin-bottom: 20px;
}

.time-info p {
    margin: 8px 0;
    color: #1a237e;
    font-size: 1.1em;
    font-weight: 500;
}

.activity-list {
    margin-top: 20px;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f0f2f5;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #f8f9fa;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 18px;
}

.icon-checkin {
    background-color: #e3f2fd;
    color: #1a237e;
}

.icon-checkout {
    background-color: #f3e5f5;
    color: #6a1b9a;
}

.activity-details {
    flex: 1;
}

.activity-details p {
    margin: 0;
    color: #1a237e;
    font-weight: 500;
    font-size: 1.1em;
}

.activity-time {
    font-size: 0.9em;
    color: #455a64;
}

.status-absent {
    color: #c62828;
    font-weight: 600;
    font-size: 1.1em;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 1000;
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background-color: #fff;
    margin: 2% auto;
    padding: 30px;
    width: 95%;
    max-width: 1000px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    color: #333;
    font-size: 24px;
    margin: 0;
    padding: 0;
    font-weight: 500;
}

.close-button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 5px;
    line-height: 1;
}

.close-button:hover {
    color: #333;
}

/* Form styles */
.calculation-section {
    margin-bottom: 30px;
}

.calculation-section h3 {
    color: #333;
    font-size: 18px;
    margin-bottom: 20px;
    font-weight: 500;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #666;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
}

.form-group input:focus {
    outline: none;
    border-color: #666;
}

/* Payroll statement styles */
.payroll-statement {
    background: #fff;
    padding: 30px;
}

.payroll-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.payroll-header h1 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
    font-weight: 500;
}

.payroll-header p {
    font-size: 14px;
    color: #666;
}

.payroll-section {
    margin-bottom: 25px;
}

.payroll-section h3 {
    font-size: 16px;
    color: #333;
    margin-bottom: 15px;
    font-weight: 500;
}

.payroll-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.payroll-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.payroll-item .label {
    color: #666;
    font-size: 14px;
}

.payroll-item .value {
    color: #333;
    font-weight: 500;
}

.payroll-total {
    font-size: 16px;
    font-weight: 500;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    color: #333;
}

.payroll-footer {
    margin-top: 30px;
    text-align: center;
    font-size: 12px;
    color: #666;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.print-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
    font-size: 14px;
}

.print-button:hover {
    background-color: #444;
}

/* Table styles */
.table-responsive {
    margin-top: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #f5f5f5;
    color: #333;
    font-weight: 500;
    padding: 12px;
    text-align: left;
    font-size: 14px;
    border-bottom: 1px solid #eee;
}

td {
    padding: 12px;
    color: #666;
    font-size: 14px;
    border-bottom: 1px solid #eee;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background-color: #f9f9f9;
}

/* Loading state */
.loading {
    text-align: center;
    padding: 30px;
    color: #666;
    font-size: 14px;
}

/* Error state */
.error-message {
    text-align: center;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #eee;
    border-radius: 4px;
    margin: 20px 0;
}

.error-message p {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-content {
        margin: 5% auto;
        padding: 20px;
    }
    
    .payroll-grid {
        grid-template-columns: 1fr;
    }
    
    .payroll-header h1 {
        font-size: 20px;
    }
}

/* Updated Modal styles */
.modal-content.large {
    width: 95%;
    max-width: 900px;
    margin: 5% auto;
    max-height: 90vh;
    overflow-y: auto;
}

.calculation-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.hours-summary, .salary-calculation, .salary-result, .daily-breakdown {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.position-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.position-info p {
    margin: 5px 0;
    color: #333;
}

.position-info strong {
    color: #1a237e;
}

/* Add these new styles for the payslip */
.employee-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.employee-info h3 {
    color: #1a237e;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.employee-info .info-item label {
    color: #333;
}

.employee-info .info-item span {
    color: #1a237e;
    font-weight: 600;
}

.salary-calculation h3 {
    color: #1a237e;
    margin-bottom: 15px;
}

.salary-result .summary-item.total {
    background: #e3f2fd;
    border: 2px solid #1976d2;
    font-size: 1.2em;
}

.daily-breakdown table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #1a237e;
}

.daily-breakdown table td {
    color: #1a237e;
}

#periodDate {
    font-weight: 500;
}

/* Print-specific styles */
@media print {
    .modal-content {
        box-shadow: none;
        margin: 0;
        padding: 0;
        width: 100%;
        max-width: none;
    }

    .modal-header {
        border-bottom: 2px solid #000;
        margin-bottom: 20px;
    }

    .modal-header h2 {
        font-size: 24px;
        color: #000;
    }

    .close-button {
        display: none;
    }

    .calculation-section {
        display: none;
    }

    .payroll-statement {
        padding: 20px;
    }

    .payroll-header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
    }

    .payroll-header h1 {
        font-size: 28px;
        color: #000;
        margin-bottom: 10px;
    }

    .payroll-header p {
        font-size: 16px;
        color: #000;
    }

    .payroll-section {
        margin-bottom: 25px;
    }

    .payroll-section h3 {
        font-size: 18px;
        color: #000;
        border-bottom: 1px solid #000;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    .payroll-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .payroll-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px dotted #000;
    }

    .payroll-item .label {
        font-weight: 500;
    }

    .payroll-item .value {
        font-weight: 600;
    }

    .payroll-total {
        font-size: 18px;
        font-weight: bold;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 2px solid #000;
    }

    .payroll-footer {
        margin-top: 50px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .no-print {
        display: none;
    }
}

/* Non-print styles for the payroll statement */
.payroll-statement {
    display: none;
    background: white;
    padding: 20px;
    margin-top: 20px;
}

.payroll-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #000;
    padding-bottom: 20px;
}

.payroll-header h1 {
    font-size: 28px;
    color: #000;
    margin-bottom: 10px;
}

.payroll-header p {
    font-size: 16px;
    color: #000;
}

.payroll-section {
    margin-bottom: 25px;
}

.payroll-section h3 {
    font-size: 18px;
    color: #000;
    border-bottom: 1px solid #000;
    padding-bottom: 5px;
    margin-bottom: 15px;
}

.payroll-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.payroll-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px dotted #000;
}

.payroll-item .label {
    font-weight: 500;
}

.payroll-item .value {
    font-weight: 600;
}

.payroll-total {
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
    padding-top: 10px;
    border-top: 2px solid #000;
}

.payroll-footer {
    margin-top: 50px;
    text-align: center;
    font-size: 14px;
    color: #666;
}

.print-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}

.print-button:hover {
    background-color: #45a049;
}
</style>

<script>
let currentHoursData = null;
let hoursModal, hoursResult, salaryResult;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DOM elements
    hoursModal = document.getElementById('hoursModal');
    hoursResult = document.getElementById('hoursResult');
    salaryResult = document.getElementById('salaryResult');
    
    // Set default dates
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (startDateInput && endDateInput) {
        startDateInput.value = firstDayOfMonth.toISOString().split('T')[0];
        endDateInput.value = today.toISOString().split('T')[0];
    }
});

function showHoursModal() {
    if (hoursModal && hoursResult && salaryResult) {
        hoursModal.style.display = 'block';
        hoursResult.style.display = 'none';
        salaryResult.style.display = 'none';
    }
}

function closeHoursModal() {
    if (hoursModal && hoursResult && salaryResult) {
        hoursModal.style.display = 'none';
        hoursResult.style.display = 'none';
        salaryResult.style.display = 'none';
    }
}

function calculateHours(event) {
    event.preventDefault();
    
    if (!hoursResult) {
        console.error('hoursResult element not found');
        return;
    }
    
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (!startDateInput || !endDateInput) {
        console.error('Date inputs not found');
        return;
    }
    
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;
    
    // Show loading state
    hoursResult.style.display = 'block';
    hoursResult.innerHTML = '<div class="loading">Calculating...</div>';
    
    fetch(`/employees/{{ $employee->id }}/calculate-hours?start_date=${startDate}&end_date=${endDate}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            if (data.error) {
                throw new Error(data.error);
            }
            
            currentHoursData = data;
            
            // Calculate salary automatically
            const hourlyRate = {{ $salaryData['hourly_rate'] }};
            const overtimeRate = {{ $salaryData['overtime_rate'] }};
            const regularHoursPerDay = 8;
            
            let regularHours = 0;
            let overtimeHours = 0;
            
            data.daily_breakdown.forEach(day => {
                if (day.hours <= regularHoursPerDay) {
                    regularHours += day.hours;
                } else {
                    regularHours += regularHoursPerDay;
                    overtimeHours += (day.hours - regularHoursPerDay);
                }
            });
            
            const regularPay = regularHours * hourlyRate;
            const overtimePay = overtimeHours * overtimeRate;
            const totalSalary = regularPay + overtimePay;
            
            // Create the payroll statement HTML
            const hoursResultHTML = `
                <div class="payroll-statement">
                    <div class="payroll-header">
                        <h1>Payroll Statement</h1>
                        <p>Period: ${new Date(startDate).toLocaleDateString('fr-FR')} - ${new Date(endDate).toLocaleDateString('fr-FR')}</p>
                    </div>

                    <div class="payroll-section">
                        <h3>Employee Information</h3>
                        <div class="payroll-grid">
                            <div class="payroll-item">
                                <span class="label">Name:</span>
                                <span class="value">{{ $employee->name }}</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Position:</span>
                                <span class="value">{{ $employee->position }}</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Department:</span>
                                <span class="value">{{ $employee->department }}</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Employee ID:</span>
                                <span class="value">{{ $employee->id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="payroll-section">
                        <h3>Salary Details</h3>
                        <div class="payroll-grid">
                            <div class="payroll-item">
                                <span class="label">Regular Hours:</span>
                                <span class="value">${regularHours.toFixed(2)}</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Overtime Hours:</span>
                                <span class="value">${overtimeHours.toFixed(2)}</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Regular Rate:</span>
                                <span class="value">{{ $salaryData['hourly_rate'] }} DH</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Overtime Rate:</span>
                                <span class="value">{{ $salaryData['overtime_rate'] }} DH</span>
                            </div>
                        </div>
                    </div>

                    <div class="payroll-section">
                        <h3>Earnings</h3>
                        <div class="payroll-grid">
                            <div class="payroll-item">
                                <span class="label">Regular Pay:</span>
                                <span class="value">${regularPay.toFixed(2)} DH</span>
                            </div>
                            <div class="payroll-item">
                                <span class="label">Overtime Pay:</span>
                                <span class="value">${overtimePay.toFixed(2)} DH</span>
                            </div>
                        </div>
                        <div class="payroll-total">
                            <span class="label">Total Earnings:</span>
                            <span class="value">${totalSalary.toFixed(2)} DH</span>
                        </div>
                    </div>

                    <div class="payroll-section">
                        <h3>Daily Breakdown</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.daily_breakdown.map(day => `
                                        <tr>
                                            <td>${day.date}</td>
                                            <td>${day.hours}</td>
                                            <td>${day.hours > 8 ? 'Overtime' : 'Regular'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="payroll-footer">
                        <p>This is an official payroll statement. Please keep it for your records.</p>
                        <p>Generated on: ${new Date().toLocaleDateString('fr-FR')}</p>
                    </div>

                    <button onclick="window.print()" class="print-button no-print">Print Payroll Statement</button>
                </div>
            `;
            
            // Update the hours result section
            hoursResult.innerHTML = hoursResultHTML;
            
            // Show the payroll statement
            const payrollStatement = document.querySelector('.payroll-statement');
            if (payrollStatement) {
                payrollStatement.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hoursResult.innerHTML = `
                <div class="error-message">
                    <p>Error calculating hours: ${error.message}</p>
                    <button onclick="calculateHours(event)" class="btn btn-primary">Try Again</button>
                </div>
            `;
        });
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (hoursModal && event.target == hoursModal) {
        closeHoursModal();
    }
}
</script>
@endsection 