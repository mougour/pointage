@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Employee Management</h1>
        <div class="search-container">
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 20 20">
                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                </svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Search employees...">
            </div>
            <button onclick="location.href='{{ route('admin.employees.create') }}'" class="btn btn-primary">
                <svg class="btn-icon" viewBox="0 0 20 20">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
                Add New Employee
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeesTable">
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>
                        <span class="status-badge {{ $employee->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="actions-cell">
                        <a href="{{ route('admin.attendance.show', $employee->id) }}" class="btn btn-icon" title="Manage Attendance">
                            <svg viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 2a6 6 0 110 12 6 6 0 010-12zm1 2H9v5h4V7h-2V6z"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-icon" title="Edit">
                            <svg viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-8.486 8.486-3.536.707.707-3.536 8.486-8.486z"/>
                            </svg>
                        </a>
                        <button onclick="deleteEmployee({{ $employee->id }})" class="btn btn-icon btn-danger" title="Delete">
                            <svg viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8z"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $employees->links() }}
        </div>
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
    align-items: center;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
}

.search-container {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    color: var(--text-light);
}

.search-input {
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    width: 300px;
}

.table-container {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.data-table th {
    background: var(--bg-light);
    font-weight: 600;
    color: var(--text-dark);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.actions-cell {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    padding: 0.5rem;
    border-radius: 0.375rem;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-light);
    transition: all 0.3s ease;
}

.btn-icon svg {
    width: 1.25rem;
    height: 1.25rem;
}

.btn-icon:hover {
    color: var(--primary-color);
    background: var(--bg-light);
}

.btn-icon.btn-danger:hover {
    color: var(--danger-color);
}

.pagination {
    padding: 1rem;
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}
</style>

<script>
// Live search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.getElementById('employeesTable').getElementsByTagName('tr');
    
    Array.from(rows).forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Delete confirmation
function deleteEmployee(id) {
    if (confirm('Are you sure you want to delete this employee?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/employees') }}/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection 