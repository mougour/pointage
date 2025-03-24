@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Attendance Management</h1>
            <h2 class="employee-name">{{ $employee->name }}</h2>
            <p class="employee-info">{{ $employee->position }} - {{ $employee->department }}</p>
        </div>
        <div class="header-actions">
            <div class="date-picker">
                <input type="month" id="monthPicker" class="month-input" value="{{ date('Y-m') }}">
            </div>
            <button onclick="recordAttendance()" class="btn btn-primary">
                <svg class="btn-icon" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 2a6 6 0 110 12 6 6 0 010-12zm1 2H9v5h4V7h-2V6z"/>
                </svg>
                Record Attendance
            </button>
        </div>
    </div>

    <div class="attendance-grid">
        <div class="calendar-container">
            <div class="calendar-header">
                <h3>Monthly Calendar</h3>
            </div>
            <div id="calendar" class="calendar"></div>
        </div>

        <div class="attendance-details">
            <div class="details-header">
                <h3>Attendance Details</h3>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon present">
                        <svg viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value" id="presentDays">0</span>
                        <span class="stat-label">Present Days</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon absent">
                        <svg viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value" id="absentDays">0</span>
                        <span class="stat-label">Absent Days</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon late">
                        <svg viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 2a6 6 0 110 12 6 6 0 010-12zm1 2H9v5h4V7h-2V6z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value" id="lateDays">0</span>
                        <span class="stat-label">Late Days</span>
                    </div>
                </div>
            </div>

            <div class="attendance-list">
                <h4>Recent Attendance</h4>
                <div class="list-container" id="attendanceList"></div>
            </div>
        </div>
    </div>
</div>

<!-- Record Attendance Modal -->
<div id="attendanceModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Record Attendance</h3>
            <button onclick="closeModal()" class="close-btn">&times;</button>
        </div>
        <form id="attendanceForm" onsubmit="submitAttendance(event)">
            <div class="form-group">
                <label for="datee">Date</label>
                <input type="date" id="datee" name="datee" class="form-input" required value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-input" required>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                </select>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" class="form-input" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<style>
.page-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.employee-name {
    font-size: 1.25rem;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.employee-info {
    color: var(--text-light);
    font-size: 0.875rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.month-input {
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.attendance-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.calendar-container,
.attendance-details {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.calendar-header,
.details-header {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.calendar {
    padding: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    padding: 1rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: 0.5rem;
}

.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: white;
}

.stat-icon.present {
    background: var(--success-color);
}

.stat-icon.absent {
    background: var(--danger-color);
}

.stat-icon.late {
    background: #eab308;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-light);
}

.attendance-list {
    padding: 1rem;
}

.list-container {
    margin-top: 1rem;
    max-height: 300px;
    overflow-y: auto;
}

/* Modal Styles */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.modal.hidden {
    display: none;
}

.modal-content {
    background: white;
    border-radius: 0.5rem;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-light);
    cursor: pointer;
}

.modal-footer {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-secondary {
    background-color: var(--bg-light);
    color: var(--text-dark);
}

.btn-secondary:hover {
    background-color: var(--border-color);
}
</style>

<script>
let calendar;
const employeeId = {{ $employee->id }};

document.addEventListener('DOMContentLoaded', function() {
    initializeCalendar();
    loadAttendanceData();
    
    document.getElementById('monthPicker').addEventListener('change', function() {
        loadAttendanceData();
    });
});

function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: '',
            center: 'title',
            right: ''
        },
        events: [],
        eventClick: function(info) {
            showAttendanceDetails(info.event);
        }
    });
    calendar.render();
}

function loadAttendanceData() {
    const month = document.getElementById('monthPicker').value;
    showLoading();
    
    axios.get(`/admin/attendance/${employeeId}/data?month=${month}`)
        .then(response => {
            updateCalendar(response.data.attendance);
            updateStats(response.data.stats);
            updateList(response.data.recent);
        })
        .catch(error => {
            showToast('Failed to load attendance data', 'error');
        })
        .finally(() => {
            hideLoading();
        });
}

function updateCalendar(attendance) {
    calendar.removeAllEvents();
    attendance.forEach(record => {
        calendar.addEvent({
            title: record.status,
            start: record.date,
            className: `status-${record.status}`,
            extendedProps: {
                notes: record.notes
            }
        });
    });
}

function updateStats(stats) {
    document.getElementById('presentDays').textContent = stats.present;
    document.getElementById('absentDays').textContent = stats.absent;
    document.getElementById('lateDays').textContent = stats.late;
}

function updateList(records) {
    const container = document.getElementById('attendanceList');
    container.innerHTML = records.map(record => `
        <div class="attendance-record">
            <div class="record-status status-${record.status}">
                ${record.status.charAt(0).toUpperCase() + record.status.slice(1)}
            </div>
            <div class="record-date">${record.date}</div>
            ${record.notes ? `<div class="record-notes">${record.notes}</div>` : ''}
        </div>
    `).join('');
}

function recordAttendance() {
    document.getElementById('attendanceModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('attendanceModal').classList.add('hidden');
    document.getElementById('attendanceForm').reset();
}

function submitAttendance(event) {
    event.preventDefault();
    const form = event.target;
    const data = {
        employee_id: employeeId,
        datee: form.datee.value,
        status: form.status.value,
        notes: form.notes.value
    };
    
    showLoading();
    axios.post('/admin/attendance', data)
        .then(response => {
            showToast('Attendance recorded successfully');
            closeModal();
            loadAttendanceData();
        })
        .catch(error => {
            showToast('Failed to record attendance', 'error');
        })
        .finally(() => {
            hideLoading();
        });
}

function showAttendanceDetails(event) {
    showToast(`
        Date: ${event.start.toLocaleDateString()}
        Status: ${event.title}
        ${event.extendedProps.notes ? `Notes: ${event.extendedProps.notes}` : ''}
    `);
}
</script>
@endsection 