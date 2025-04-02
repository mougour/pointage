@extends('layouts.app')

@section('title', 'Payroll Statement')

@section('styles')
<style>
    /* Print-specific styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-only {
            display: block !important;
        }
        
        body {
            padding: 0;
            margin: 0;
        }
        
        .statement-container {
            padding: 20px;
            margin: 0;
            box-shadow: none;
        }
        
        .statement-header {
            page-break-after: avoid;
        }
        
        .statement-details {
            page-break-inside: avoid;
        }
        
        .statement-footer {
            page-break-before: avoid;
        }
    }

    /* Regular styles */
    .statement-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .statement-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
    }

    .company-name {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .statement-title {
        font-size: 20px;
        color: #666;
        margin-bottom: 1rem;
    }

    .statement-date {
        font-size: 14px;
        color: #999;
    }

    .statement-details {
        margin-bottom: 2rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }

    .detail-label {
        font-weight: 500;
        color: #666;
    }

    .detail-value {
        font-weight: 600;
    }

    .earnings-section,
    .deductions-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        font-weight: bold;
        font-size: 18px;
        border-top: 2px solid #eee;
        margin-top: 1rem;
    }

    .statement-footer {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #eee;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .print-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 2rem;
        background: #00897B;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .print-button:hover {
        background: #00695C;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 120px;
        color: rgba(0,0,0,0.05);
        pointer-events: none;
        z-index: 0;
    }
</style>
@endsection

@section('content')
<div class="statement-container">
    <div class="watermark">PAID</div>
    
    <div class="statement-header">
        <div class="company-name">PointagePro</div>
        <div class="statement-title">Payroll Statement</div>
        <div class="statement-date">Generated on {{ date('F d, Y') }}</div>
    </div>

    <div class="statement-details">
        <div class="detail-row">
            <span class="detail-label">Employee Name:</span>
            <span class="detail-value">{{ $employee->fullName }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Employee ID:</span>
            <span class="detail-value">{{ $employee->cin }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Department:</span>
            <span class="detail-value">{{ $employee->department }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Position:</span>
            <span class="detail-value">{{ $employee->position }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Pay Period:</span>
            <span class="detail-value">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="earnings-section">
        <div class="section-title">Earnings</div>
        <div class="detail-row">
            <span class="detail-label">Base Salary</span>
            <span class="detail-value">${{ number_format($baseSalary, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Overtime ({{ $overtimeHours }} hours)</span>
            <span class="detail-value">${{ number_format($overtimePay, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Bonus</span>
            <span class="detail-value">${{ number_format($bonus, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Benefits</span>
            <span class="detail-value">${{ number_format($benefits, 2) }}</span>
        </div>
        <div class="total-row">
            <span>Total Earnings</span>
            <span>${{ number_format($totalEarnings, 2) }}</span>
        </div>
    </div>

    <div class="deductions-section">
        <div class="section-title">Deductions</div>
        <div class="detail-row">
            <span class="detail-label">Taxes</span>
            <span class="detail-value">-${{ number_format($taxes, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Other Deductions</span>
            <span class="detail-value">-${{ number_format($deductions, 2) }}</span>
        </div>
        <div class="total-row">
            <span>Total Deductions</span>
            <span>-${{ number_format($totalDeductions, 2) }}</span>
        </div>
    </div>

    <div class="total-row">
        <span>Net Pay</span>
        <span>${{ number_format($netPay, 2) }}</span>
    </div>

    <div class="statement-footer">
        <p>This is an official document. Please keep it for your records.</p>
        <p>For any questions, please contact the HR department.</p>
    </div>
</div>

<button class="print-button no-print" onclick="window.print()">
    <i class="fas fa-print"></i> Print Statement
</button>
@endsection

@section('scripts')
<script>
    // Auto-print when the page loads (optional)
    // window.onload = function() {
    //     window.print();
    // };
</script>
@endsection 