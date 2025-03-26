@extends('layouts.app')

@section('title', 'Payroll Statement')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/customized-payroll.css') }}">
@endsection

@section('content')
<div class="payroll-container">
    <div class="payroll-header">
        <h1>Payroll Management</h1>
        <p>Generate and view detailed payroll reports for employees</p>
    </div>

    <!-- Payroll Generation Form -->
    <div class="payroll-form">
        <h4 class="payroll-form-title">Generate Payroll Report</h4>
        <form id="payrollForm" method="GET">
            <div class="form-row">
                <div class="form-group">
                    <label for="employee">Select Employee:</label>
                    <select id="employee" name="employee_cin" class="form-control" required>
                        <option value="" selected disabled>Choose an employee</option>
                        @foreach(\App\Models\Employee::orderBy('fullName')->get() as $emp)
                            <option value="{{ $emp->cin }}" {{ isset($employee) && $employee->cin == $emp->cin ? 'selected' : '' }}>
                                {{ $emp->fullName }} - {{ $emp->position }} ({{ $emp->cin }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 0.5rem;">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.25-11.25a.75.75 0 0 1 1.5 0v4.19l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72V4.75z"/>
                </svg>
                Generate Report
            </button>
        </form>
    </div>
</div>

<!-- Loading indicator -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>

<!-- Payroll Report Modal -->
<div id="payrollModal" class="payroll-modal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close-button" id="closeModal">×</button>
            <div class="modal-actions">
                <button type="button" class="print-button" id="printReport">
                    <i class="fas fa-print"></i> Print Report
                </button>
                <button type="button" class="download-button" id="downloadReport">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>

        @if(isset($employee))
        <div class="payroll-header">
            <h1>Employee Payroll Report</h1>
            <p>Period: {{ date('F d, Y', strtotime(request('start_date'))) }} - {{ date('F d, Y', strtotime(request('end_date'))) }}</p>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <h4 class="section-title">Employee Information</h4>
            <div class="employee-details">
                <div class="employee-detail-grid">
                    <div class="detail-group">
                        <div class="detail-row">
                            <strong>Name:</strong> {{ $employee->fullName }}
                        </div>
                        <div class="detail-row">
                            <strong>ID:</strong> {{ $employee->cin }}
                        </div>
                        <div class="detail-row">
                            <strong>Gender:</strong> {{ ucfirst($employee->gender) }}
                        </div>
                        <div class="detail-row">
                            <strong>Birth Date:</strong> {{ $employee->birth_date }}
                        </div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-row">
                            <strong>Position:</strong> {{ $employee->position }}
                        </div>
                        <div class="detail-row">
                            <strong>Department:</strong> {{ $employee->department }}
                        </div>
                        <div class="detail-row">
                            <strong>Phone:</strong> {{ $employee->phone ?: 'Not provided' }}
                        </div>
                        <div class="detail-row">
                            <strong>Joined:</strong> {{ date('M d, Y', strtotime($employee->created_at)) }}
                        </div>
                    </div>
                </div>
                
                @if($employee->address)
                <div class="address-section">
                    <strong>Address:</strong> {{ $employee->address }}
                </div>
                @endif
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="payroll-summary attendance-summary">
            <h4 class="section-title">Attendance Summary</h4>
            <div class="summary-card">
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-icon bg-blue">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ $records->count() }}</span>
                            <span class="summary-label">Days Present</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon bg-green">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($totalHoursWorked ?? 0, 1) }}</span>
                            <span class="summary-label">Total Hours</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon bg-purple">
                            <i class="fas fa-coffee"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($totalPauseTime ?? 0, 1) }}</span>
                            <span class="summary-label">Break Hours</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon bg-orange">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($totalPay ?? 0, 0) }}</span>
                            <span class="summary-label">Total Pay (DH)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Details -->
        <div class="payroll-summary">
            <h4 class="section-title">Salary Details</h4>
            <div class="info-row">
                <div><strong>Total Hours:</strong> {{ number_format($totalHoursWorked ?? 0, 2) }} hours</div>
                <div><strong>Regular Hours:</strong> {{ number_format(min($totalHoursWorked ?? 0, 160), 2) }} hours</div>
            </div>
            <div class="info-row">
                <div><strong>Overtime Hours:</strong> {{ number_format(max(0, ($totalHoursWorked ?? 0) - 160), 2) }} hours</div>
                <div><strong>Hourly Rate:</strong> {{ number_format($averageWage ?? 0, 2) }} DH</div>
            </div>
            
            <div style="margin-top: 1.5rem; padding-top: 1.2rem; border-top: 1px dashed rgba(0, 128, 128, 0.3);">
                <h5 style="color: #3b82f6; font-size: 1.1rem; margin-bottom: 1rem;">Pay Period Summary</h5>
                <div class="info-row">
                    <div>
                        <div style="margin-bottom: 0.5rem;"><strong>Work Period:</strong> {{ date('M d', strtotime(request('start_date'))) }} - {{ date('M d, Y', strtotime(request('end_date'))) }}</div>
                        <div><strong>Payment Date:</strong> {{ date('F d, Y', strtotime('+3 days')) }}</div>
                    </div>
                    <div>
                        <div style="margin-bottom: 0.5rem;"><strong>Days Worked:</strong> {{ $records->count() }} day(s)</div>
                        <div><strong>Position Rate:</strong> {{ $averageWage }} DH/hour</div>
                    </div>
            </div>
        </div>

        <!-- Earnings -->
            <h4 class="section-title" style="margin-top: 1.5rem;">Earnings</h4>
            @php
                $regularHours = min($totalHoursWorked ?? 0, 160);
                $overtimeHours = max(0, ($totalHoursWorked ?? 0) - 160);
                $regularPay = $regularHours * ($averageWage ?? 0);
                $overtimePay = $overtimeHours * (($averageWage ?? 0) * 1.5);
                $totalPay = $regularPay + $overtimePay;
            @endphp
            <div class="info-row">
                <div><strong>Regular Pay:</strong> <span style="font-weight: 500;">{{ number_format($regularHours, 2) }} hrs × {{ number_format($averageWage, 2) }} DH =</span> {{ number_format($regularPay, 2) }} DH</div>
                <div><strong>Overtime Pay:</strong> <span style="font-weight: 500;">{{ number_format($overtimeHours, 2) }} hrs × {{ number_format($averageWage * 1.5, 2) }} DH =</span> {{ number_format($overtimePay, 2) }} DH</div>
            </div>

            <div class="total-section" style="margin-top: 1.2rem;">
                <strong style="font-size: 1.1rem;">Total Earnings:</strong> <span class="total-amount">{{ number_format($totalPay, 2) }} DH</span>
            </div>
        </div>

        <!-- Daily Breakdown -->
        <div>
            <h4 class="section-title">Attendance Breakdown</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                    // Using the $records variable passed from the controller
                    // Don't recreate it here as it causes issues with other sections
                    @endphp
                    
                    @forelse($records as $record)
                        <tr>
                            <td>{{ date('Y-m-d', strtotime($record->datee)) }}</td>
                            <td>{{ $record->check_in ? date('H:i:s', strtotime($record->check_in)) : '-' }}</td>
                            <td>{{ $record->check_out ? date('H:i:s', strtotime($record->check_out)) : '-' }}</td>
                            <td>
                                @if($record->check_in && $record->check_out)
                                    @php
                                        $checkIn = new DateTime($record->check_in);
                                        $checkOut = new DateTime($record->check_out);
                                        $interval = $checkIn->diff($checkOut);
                                        $hours = $interval->h + ($interval->i / 60) + ($interval->s / 3600);
                                        $pauseMinutes = $record->total_pause_time ?: 0;
                                        $hoursWorked = $hours - ($pauseMinutes / 60);
                                        $hoursWorked = max(0, $hoursWorked);
                                    @endphp
                                    {{ number_format($hoursWorked, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ ucfirst($record->status) }}</td>
                    </tr>
                    @empty
                    <tr>
                            <td colspan="5" class="text-center">No attendance records found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Detailed Attendance Log -->
        <div class="attendance-log">
            <h4 class="section-title">Attendance Log</h4>
            
            @if(isset($records) && $records->count() > 0)
                <div class="table-responsive">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Break Time</th>
                                <th>Hours Worked</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                            @php
                                $checkIn = new DateTime($record->check_in);
                                $checkOut = $record->check_out ? new DateTime($record->check_out) : null;
                                
                                if($checkOut) {
                                    $interval = $checkIn->diff($checkOut);
                                    $hoursWorked = $interval->h + ($interval->i / 60);
                                    
                                    // Subtract pause time if it exists
                                    if($record->total_pause_time) {
                                        $hoursWorked -= $record->total_pause_time / 60;
                                    }
                                } else {
                                    $hoursWorked = 0;
                                }
                            @endphp
                            <tr>
                                <td>{{ date('M d, Y', strtotime($record->check_in)) }}</td>
                                <td>{{ date('h:i A', strtotime($record->check_in)) }}</td>
                                <td>{{ $record->check_out ? date('h:i A', strtotime($record->check_out)) : 'Not checked out' }}</td>
                                <td>{{ $record->total_pause_time ? number_format($record->total_pause_time / 60, 1) . ' hrs' : '0 hrs' }}</td>
                                <td>{{ number_format($hoursWorked, 1) }} hrs</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-records">No attendance records found for this period.</p>
            @endif
        </div>

        <div class="text-muted">
            <p>This is an official payroll statement. Please keep it for your records.</p>
            <p>Generated on: {{ date('Y-m-d') }}</p>
        </div>

        <div class="footer-actions">
            <button class="btn btn-success print-btn" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 0.5rem;">
                    <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                    <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                </svg>
                Print Payroll
            </button>
            <button class="btn btn-secondary" onclick="exportToPDF()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 0.5rem;">
                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                    <path d="M3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h7v1a1 1 0 0 1-1 1H6zm7-3H6v-2h7v2z"/>
                </svg>
                Export as PDF
            </button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date range (current month)
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        document.getElementById('start_date').value = formatDate(firstDay);
        document.getElementById('end_date').value = formatDate(lastDay);
        
        // Get references to elements
        const payrollModal = document.getElementById('payrollModal');
        const closeModal = document.getElementById('closeModal');
        const payrollForm = document.getElementById('payrollForm');
        const employeeSelect = document.getElementById('employee');
        const loadingOverlay = document.getElementById('loadingOverlay');
        
        // Close modal when clicking close button
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                payrollModal.classList.remove('modal-show');
                document.body.classList.remove('modal-open');
            });
        }
        
        // Close modal when clicking outside the content
        window.addEventListener('click', function(event) {
            if (event.target === payrollModal) {
                payrollModal.classList.remove('modal-show');
                document.body.classList.remove('modal-open');
            }
        });
        
        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && payrollModal.classList.contains('modal-show')) {
                payrollModal.classList.remove('modal-show');
                document.body.classList.remove('modal-open');
            }
        });
        
        // Handle form submission
        if (payrollForm) {
            payrollForm.addEventListener('submit', function(event) {
                // Always prevent the default form submission
                event.preventDefault();
                
                const cin = employeeSelect.value;
                
                if (!cin) {
                    alert('Please select an employee');
                    return;
                }
                
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                console.log('Generating report for employee CIN:', cin);
                console.log('Date range:', startDate, 'to', endDate);
                
                // Show loading indicator
                loadingOverlay.classList.add('active');
                
                // Create the URL with all parameters
                const url = `/employees/${cin}/show-stats?start_date=${startDate}&end_date=${endDate}&show_modal=true`;
                console.log('Redirecting to:', url);
                
                // Redirect to the report URL
                window.location.href = url;
            });
        }
        
        // Show modal only if we have employee data AND there's a query parameter in URL
        // This ensures the modal only shows after an explicit user request
        @if(request()->has('show_modal') && request('show_modal') == 'true')
            // Hide loading indicator if it's still visible
            loadingOverlay.classList.remove('active');
            
            // Show the modal with payroll data
            payrollModal.classList.add('modal-show');
            document.body.classList.add('modal-open');
            window.scrollTo(0, 0);
        @endif
    });
    
    // Export to PDF function
    function exportToPDF() {
        alert('PDF export functionality would be implemented here.');
        // In a real implementation, this would use a library like jsPDF or make an AJAX call to a server-side PDF generation endpoint
    }

    // Add this JavaScript for printing and downloading functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Print functionality
        document.getElementById('printReport').addEventListener('click', function() {
            window.print();
        });
        
        // Download as PDF functionality (requires html2pdf.js library)
        document.getElementById('downloadReport').addEventListener('click', function() {
            // Alert user that they need to install html2pdf library for this feature
            alert('To enable PDF downloads, please install the html2pdf.js library and implement the download functionality.');
            
            // Uncomment below code after adding html2pdf.js to your project
            /*
            const element = document.querySelector('.modal-content');
            const options = {
                margin: 1,
                filename: 'payroll-report.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(options).from(element).save();
            */
        });
    });
</script>
@endsection
