@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employees</h1>
    
    <div class="employee-list">
        @foreach($employees as $employee)
            <div class="employee-card">
                <h2>{{ $employee->name }}</h2>
                <p>Email: {{ $employee->email }}</p>
                <p>Department: {{ $employee->department }}</p>
                
                @php
                    $todayAttendance = $employee->attendances->first();
                    $status = $todayAttendance ? ($todayAttendance->check_out ? 'Checked Out' : 'Checked In') : 'Not Present';
                    $statusClass = $todayAttendance ? ($todayAttendance->check_out ? 'status-checked-out' : 'status-checked-in') : 'status-absent';
                @endphp
                
                <p class="status {{ $statusClass }}">Status: {{ $status }}</p>
                
                <div class="actions">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    color: #333;
    margin-bottom: 30px;
    text-align: center;
}

.employee-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.employee-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.employee-card:hover {
    transform: translateY(-5px);
}

.employee-card h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.employee-card p {
    color: #666;
    margin: 5px 0;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    margin: 10px 0;
}

.status-checked-in {
    background-color: #e3f2fd;
    color: #1976d2;
}

.status-checked-out {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.status-absent {
    background-color: #ffebee;
    color: #c62828;
}

.actions {
    margin-top: 15px;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.2s;
}

.btn-primary {
    background-color: #1976d2;
    color: white;
}

.btn-primary:hover {
    background-color: #1565c0;
}
</style>
@endsection 