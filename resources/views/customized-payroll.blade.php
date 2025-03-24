@extends('layouts.app')

@section('title', 'Payroll Statement')

@section('styles')
<style>
    /* Update payroll page to match white/teal color scheme */
    .payroll-container,
    .payroll-card {
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .payroll-header,
    .section-header {
        background-color: rgba(0, 128, 128, 0.1);
        color: teal;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .payroll-title,
    .section-title {
        color: teal;
    }
    
    .payroll-form label {
        color: #666666;
    }
    
    .text-label {
        color: #666666;
    }
    
    .text-value {
        color: #333333;
    }
    
    .highlight-value {
        color: teal;
        font-weight: 600;
    }
    
    .payroll-table th {
        background-color: rgba(0, 128, 128, 0.1);
        color: #666666;
    }
    
    .payroll-table tr:hover td {
        background-color: rgba(0, 128, 128, 0.05);
    }
    
    .payroll-table td {
        color: #333333;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .btn-generate,
    .btn-export,
    .btn-filter {
        background-color: teal !important;
        border-color: teal !important;
        color: white !important;
    }
    
    .btn-generate:hover,
    .btn-export:hover,
    .btn-filter:hover {
        background-color: #006666 !important;
        border-color: #006666 !important;
    }
    
    .filter-card {
        background-color: white;
        border: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    /* Any progress bars */
    .progress-bar {
        background-color: teal;
    }
    
    /* Any specific status indicators */
    .status-paid {
        background-color: rgba(0, 128, 128, 0.1);
        color: teal;
    }

    :root {
        --primary: teal !important; /* Change from dark blue to teal */
        --primary-light: rgba(0, 128, 128, 0.1) !important;
        --secondary: #008B8B !important; /* Darker teal */
        --secondary-light: rgba(0, 139, 139, 0.1) !important;
        --accent: teal !important; /* Change from light blue to teal */
        --accent-light: rgba(0, 128, 128, 0.1) !important;
        --success: #047857; /* Dark green */
        --success-light: rgba(4, 120, 87, 0.1);
        --danger: #B91C1C; /* Dark red */
        --danger-light: rgba(185, 28, 28, 0.1);
        --warning: #B45309; /* Dark amber */
        --warning-light: rgba(180, 83, 9, 0.1);
        --dark-bg: white !important; /* Change from dark blue to white */
        --border-color: rgba(0, 128, 128, 0.2) !important; /* Teal border */
        --text-light: #666666 !important; /* Dark text for white background */
        --card-bg: white !important; /* White card background */
    }

    .payroll-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        background: white !important;
        border-radius: 12px;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    
    .payroll-form {
        background: white !important;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
    }
    
    .payroll-form-title {
        color: teal !important;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        font-weight: 600;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #666666 !important;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 0.6rem;
        border-radius: 6px;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
        background-color: #f5f5f5 !important; 
        color: #333333 !important;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        border-color: teal !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 128, 128, 0.1) !important;
        background-color: #ffffff !important;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }
    
    .btn-primary {
        background-color: teal !important;
        color: white !important;
    }
    
    .btn-primary:hover {
        background-color: #006666 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    
    .payroll-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .payroll-header h1 {
        color: teal !important;
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .payroll-header p {
        font-size: 1.1rem;
        color: #666666 !important;
    }
    
    .section-title {
        color: teal !important;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2) !important;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
    }
    
    .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .info-row > div {
        flex: 1;
        min-width: 250px;
        padding: 0.5rem;
    }
    
    .footer-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .btn-success {
        background-color: teal !important;
        color: white;
    }
    
    .btn-success:hover {
        background-color: #006666 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    
    .btn-secondary {
        background-color: #475569; /* Slate gray */
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #334155; /* Darker slate */
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    
    .btn-outline {
        background-color: transparent;
        color: var(--accent);
        border: 1px solid var(--accent);
    }
    
    .btn-outline:hover {
        background-color: var(--accent);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        font-size: 1rem;
        border-radius: 6px;
        transition: all 0.2s;
        color: var(--accent);
        background-color: transparent;
        border: none;
        cursor: pointer;
    }
    
    .btn-icon:hover {
        background-color: var(--primary-light);
        transform: translateY(-1px);
    }
    
    .section-divider {
        margin: 2rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .total-amount {
        font-size: 1.3rem;
        font-weight: bold;
        color: teal !important;
    }
    
    .table {
        width: 100%;
        font-size: 0.95rem;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .table th {
        background-color: rgba(0, 128, 128, 0.1);
        color: #666666;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .table td {
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.03);
    }
    
    .table-striped tbody tr:nth-of-type(even) {
        background-color: rgba(255, 255, 255, 0.01);
    }
    
    .table-striped tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .text-muted {
        font-size: 0.9rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 2rem;
    }
    
    /* Enhanced Modal Styles */
    .payroll-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.65);
        z-index: 1000;
        overflow-y: auto;
        padding: 2rem 0;
        backdrop-filter: blur(5px);
    }
    
    .modal-open {
        overflow: hidden;
    }
    
    .modal-content {
        position: relative;
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        padding: 2.5rem 3rem;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease-out;
        color: #1a1f35;
    }
    
    .modal-show {
        display: block;
    }
    
    .modal-show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }
    
    .payroll-summary {
        background: white !important;
        border-radius: 12px;
        padding: 1.8rem;
        margin: 1.8rem 0;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-out;
    }
    
    .payroll-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }
    
    .modal-content .payroll-header h1 {
        color: #1a1f35;
    }
    
    .modal-content .payroll-header p {
        color: #4b5563;
    }
    
    .modal-content .section-title {
        color: #3b82f6;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .total-section {
        background: rgba(16, 185, 129, 0.1);
        border-radius: 10px;
        padding: 1.2rem 1.5rem;
        margin-top: 1.5rem;
        border-left: 4px solid #10b981;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-content .info-row {
        color: #1a1f35;
    }
    
    .modal-content strong {
        color: #1a1f35;
        font-weight: 600;
    }
    
    .btn-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(0, 0, 0, 0.1);
        border: none;
        color: #1a1f35;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        z-index: 10;
    }
    
    .btn-close:hover {
        background: rgba(0, 0, 0, 0.2);
        transform: rotate(90deg);
    }
    
    .modal-content .text-muted {
        color: #6b7280;
    }
    
    /* Table styles for white background */
    .modal-content .table {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    
    .modal-content .table th {
        background-color: rgba(59, 130, 246, 0.1);
        color: #1a1f35;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-content .table td {
        color: #1a1f35;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-content .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .modal-content .table-striped tbody tr:nth-of-type(even) {
        background-color: white;
    }
    
    .modal-content .table-striped tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }
        
        .modal-content, .modal-content * {
            visibility: visible;
        }
        
        .modal-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background-color: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .modal-header, #closeModal, .modal-actions, .loading-overlay {
            display: none !important;
        }
        
        .payroll-summary, .employee-info, .attendance-log {
            break-inside: avoid;
            margin-bottom: 20px;
        }
        
        .attendance-table {
            page-break-inside: auto;
        }
        
        .attendance-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        .section-title {
            color: #333 !important;
            font-size: 16pt !important;
            margin-top: 15px !important;
        }
        
        .summary-card, .employee-details {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .summary-icon {
            background-color: #eee !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        
        .summary-icon i {
            color: #333 !important;
        }
        
        .summary-value {
            color: #000 !important;
        }
        
        .summary-label {
            color: #555 !important;
        }
        
        .detail-row strong, .info-row strong {
            color: #333 !important;
        }
        
        @page {
            size: A4;
            margin: 1.5cm;
        }
        
        /* Add a footer with page number */
        @page {
            @bottom-right {
                content: counter(page) " of " counter(pages);
            }
        }
        
        /* Add a watermark */
        .modal-content::before {
            content: "PointageX Payroll";
            position: fixed;
            top: 50%;
            left: 0;
            right: 0;
            font-size: 100px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            text-align: center;
            transform: rotate(-45deg);
        }
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .form-row, .info-row {
            flex-direction: column;
        }
        
        .form-group, .info-row > div {
            width: 100%;
            min-width: 100%;
        }
        
        .modal-content {
            width: 95%;
            padding: 1.5rem;
        }
        
        .footer-actions {
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .btn {
            width: 100%;
        }
    }
    
    /* Custom animations */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
        }
        to { 
            opacity: 1; 
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-content > * {
        animation: slideUp 0.4s ease-out forwards;
        opacity: 0;
    }
    
    .modal-content > *:nth-child(1) { animation-delay: 0.1s; }
    .modal-content > *:nth-child(2) { animation-delay: 0.2s; }
    .modal-content > *:nth-child(3) { animation-delay: 0.3s; }
    .modal-content > *:nth-child(4) { animation-delay: 0.4s; }
    .modal-content > *:nth-child(5) { animation-delay: 0.5s; }
    .modal-content > *:nth-child(6) { animation-delay: 0.6s; }
    .modal-content > *:nth-child(7) { animation-delay: 0.7s; }
    
    .footer-actions .btn {
        transition: all 0.3s ease;
        min-width: 180px;
        padding: 0.75rem 1.5rem;
    }
    
    .footer-actions .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }
    
    .footer-actions .btn:active {
        transform: translateY(-1px);
    }
    
    /* Loading indicator */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        backdrop-filter: blur(5px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }
    
    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .spinner {
        width: 70px;
        height: 70px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #3b82f6;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .employee-details {
        background-color: rgba(59, 130, 246, 0.05);
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .employee-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .detail-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .detail-row {
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .detail-row strong {
        color: #3b82f6;
        font-weight: 600;
        margin-right: 0.25rem;
    }
    .address-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(59, 130, 246, 0.2);
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .no-employee {
        color: #ef4444;
        font-style: italic;
    }

    .summary-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .summary-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 6px;
        background-color: #f8fafc;
    }
    .summary-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
    }
    .summary-icon i {
        color: white;
        font-size: 1rem;
    }
    .bg-blue {
        background-color: teal !important;
    }
    .bg-green {
        background-color: #10b981;
    }
    .bg-purple {
        background-color: #8b5cf6;
    }
    .bg-orange {
        background-color: #f59e0b;
    }
    .summary-content {
        display: flex;
        flex-direction: column;
    }
    .summary-value {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1e293b;
    }
    .summary-label {
        font-size: 0.85rem;
        color: #64748b;
    }
    
    /* Media query for smaller screens */
    @media (max-width: 640px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    .attendance-log {
        margin-top: 2rem;
    }
    .table-responsive {
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }
    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .attendance-table th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 600;
        text-align: left;
        padding: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .attendance-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }
    .attendance-table tr:last-child td {
        border-bottom: none;
    }
    .attendance-table tr:hover td {
        background-color: #f8fafc;
    }
    .no-records {
        color: #64748b;
        font-style: italic;
        padding: 1rem 0;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        position: sticky;
        top: 0;
        background-color: white;
        border-bottom: 1px solid #e2e8f0;
        z-index: 10;
    }
    .modal-actions {
        display: flex;
        gap: 0.5rem;
    }
    .print-button, .download-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        padding: 0.5rem 0.75rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .print-button {
        background-color: #f1f5f9;
        color: #334155;
    }
    .download-button {
        background-color: teal !important;
        color: white;
    }
    .print-button:hover {
        background-color: #e2e8f0;
    }
    .download-button:hover {
        background-color: #006666 !important;
    }
    .close-button {
        font-size: 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .close-button:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    .payroll-table-container {
        margin-top: 2rem;
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
        background-color: white !important;
    }
    
    .payroll-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payroll-table th,
    .payroll-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .payroll-table th {
        font-weight: 600;
        color: #666666 !important;
        background-color: rgba(0, 128, 128, 0.1) !important;
    }
    
    .payroll-table tr:last-child td {
        border-bottom: none;
    }
    
    .payroll-table tr:hover td {
        background-color: rgba(0, 128, 128, 0.05) !important;
    }
    
    .payroll-summary {
        background-color: white !important;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid rgba(0, 128, 128, 0.2) !important;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 128, 128, 0.2);
    }
    
    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .summary-title {
        font-weight: 500;
        color: #666666 !important;
    }
    
    .summary-value {
        font-weight: 600;
        color: #333333 !important;
    }
    
    .total-amount {
        color: teal !important;
        font-size: 1.25rem;
    }
</style>
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
