@extends('layouts.app')

@section('title', 'Employees List')

@section('styles')
    <style>
    :root {
        --primary: #00897B; /* Teal */
        --primary-light: rgba(0, 137, 123, 0.1);
        --secondary: #6C757D; /* Muted Gray */
        --secondary-light: rgba(108, 117, 125, 0.1);
        --accent: #26A69A; /* Lighter Teal */
        --accent-light: rgba(38, 166, 154, 0.1);
        --success: #28A745; /* Green */
        --success-light: rgba(40, 167, 69, 0.1);
        --danger: #DC3545; /* Red */
        --danger-light: rgba(220, 53, 69, 0.1);
        --warning: #FFC107; /* Yellow */
        --warning-light: rgba(255, 193, 7, 0.1);
        --text-dark: #343A40; /* Dark Gray */
        --text-light: #6C757D; /* Secondary gray */
        --bg-light: #F8F9FA; /* Light background */
        --card-bg: #FFFFFF; /* White card background */
        --border-color: rgba(0, 137, 123, 0.2); /* Teal border */
    }

    .container {
        max-width: 1600px;
        width: 100%;
        margin: 0 auto;
        padding: 0;
    }

    .employee-container {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .employee-container:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .header h2 {
        font-size: 2.25rem;
        margin-bottom: 1rem;
        color: var(--primary);
        font-weight: 600;
        letter-spacing: -0.5px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: #007267; /* Darker teal */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary {
        background-color: var(--secondary);
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268; /* Darker gray */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-success:hover {
        background-color: #218838; /* Darker green */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-danger {
        background-color: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333; /* Darker red */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .search-box {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 0.375rem;
        padding: 0.65rem 1rem;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    
    .search-box:hover {
        border-color: var(--primary);
    }

    .search-box:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    .modal-content {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
    }
    
    .modal-header {
        background-color: var(--primary-light);
        border-bottom: 1px solid var(--border-color);
    }
    
    .modal-footer {
        border-top: 1px solid var(--border-color);
    }
    
    .form-control {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 0.375rem;
        padding: 0.65rem 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    
    .form-control:hover {
        border-color: var(--primary);
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1rem;
        background-color: var(--card-bg);
    }
    
    th {
        text-align: left;
        padding: 0.75rem;
        background-color: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
    }
    
    td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
    }
    
    tr:hover td {
        background-color: var(--primary-light);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: var(--card-bg);
        margin: 5% auto;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-color);
    }
    
    .modal-content h2 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--primary);
        font-weight: 600;
        letter-spacing: -0.5px;
    }

    .close {
        color: var(--text-light);
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s;
    }

    .close:hover {
        color: var(--text-dark);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: var(--card-bg);
        color: var(--text-dark);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .text-center {
        text-align: center;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }

    /* Two-column layout for forms */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .form-column {
        flex: 1;
        min-width: 200px;
    }

    /* Success message styling */
    .success-message {
        background-color: var(--success-light);
        color: var(--success);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--success);
        font-weight: 500;
    }
        
    /* Search functionality styling */
    .search-container {
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .search-input {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: var(--card-bg);
        color: var(--text-dark);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .search-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .search-input::placeholder {
        color: var(--text-light);
    }
    
    .no-results-message {
        text-align: center;
        padding: 1.5rem;
        background-color: var(--bg-light);
        border-radius: 8px;
        margin-top: 1rem;
        color: var(--text-dark);
        font-size: 0.95rem;
        border: 1px dashed var(--border-color);
    }
    
    /* Responsive styling */
    @media (max-width: 992px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .header h2 {
            font-size: 1.75rem;
        }
        
        .employee-container {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .btn {
            width: 100%;
        }
        
        .form-row {
            flex-direction: column;
        }
        
        .header h2 {
            font-size: 1.5rem;
        }
    }
    </style>
@endsection

@section('content')
<div class="employee-container">
        <div class="header">
        <h2>Employees Management</h2>
        <button class="btn btn-primary" id="addEmployeeBtn">
            <i class="fas fa-plus" style="margin-right: 8px;"></i>
            Add Employee
        </button>
            </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="search-container">
        <input type="text" id="employee-search" class="search-input" placeholder="Search employees by name, email, or department...">
        </div>

            <table>
                <thead>
                    <tr>
                        <th>CIN</th>
                        <th>Full Name</th>
                        <th>Email</th>
                <th>Department</th>
                        <th>Position</th>
                <th>Actions</th>
                    </tr>
                </thead>
        <tbody>
                    @foreach($employees as $employee)
            <tr class="clickable-row" data-href="{{ route('employees.show', $employee->cin) }}">
                            <td>{{ $employee->cin }}</td>
                            <td>{{ $employee->fullName }}</td>
                            <td>{{ $employee->email }}</td>
                <td>{{ $employee->department }}</td>
                            <td>{{ $employee->position }}</td>
                <td class="action-cell">
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="event.stopPropagation(); openEditModal('{{ $employee->cin }}', '{{ $employee->fullName }}', '{{ $employee->email }}', '{{ $employee->department }}', '{{ $employee->position }}')">
                            <i class="fas fa-edit" style="margin-right: 5px;"></i> Edit
                        </button>
                        <form action="{{ route('employees.destroy', $employee->cin) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this employee?')">
                                <i class="fas fa-trash-alt" style="margin-right: 5px;"></i> Delete
                            </button>
                        </form>
                    </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Employee</h2>
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="cin">CIN:</label>
                        <input type="text" id="cin" name="cin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="fullName">Full Name:</label>
                        <input type="text" id="fullName" name="fullName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select id="department" name="department" class="form-control" required onchange="updatePositionOptions()">
                            <option value="" disabled selected>Select Department</option>
                            <option value="Administration">Administration</option>
                            <option value="Human Resources (HR)">Human Resources (HR)</option>
                            <option value="Finance & Accounting">Finance & Accounting</option>
                            <option value="Sales & Marketing">Sales & Marketing</option>
                            <option value="IT & Technical">IT & Technical</option>
                            <option value="Operations & Logistics">Operations & Logistics</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Construction">Construction</option>
                            <option value="Manufacturing">Manufacturing</option>
                            <option value="Security & Maintenance">Security & Maintenance</option>
                            <option value="Education & Training">Education & Training</option>
                            <option value="Healthcare">Healthcare</option>
                            <option value="Senior Management">Senior Management</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select id="position" name="position" class="form-control" required>
                            <option value="" disabled selected>Select Department First</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success mt-4" style="width: 100%;">Add Employee</button>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModal">&times;</span>
        <h2>Edit Employee</h2>
        <form id="editEmployeeForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="edit_fullName">Full Name:</label>
                        <input type="text" id="edit_fullName" name="fullName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone:</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control">
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="edit_gender">Gender:</label>
                        <select id="edit_gender" name="gender" class="form-control">
                            <option value="" disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_date_of_birth">Date of Birth:</label>
                        <input type="date" id="edit_date_of_birth" name="date_of_birth" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_department">Department:</label>
                        <select id="edit_department" name="department" class="form-control" required>
                            <option value="Administration">Administration</option>
                            <option value="Human Resources (HR)">Human Resources (HR)</option>
                            <option value="Finance & Accounting">Finance & Accounting</option>
                            <option value="Sales & Marketing">Sales & Marketing</option>
                            <option value="IT & Technical">IT & Technical</option>
                            <option value="Operations & Logistics">Operations & Logistics</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Construction">Construction</option>
                            <option value="Manufacturing">Manufacturing</option>
                            <option value="Security & Maintenance">Security & Maintenance</option>
                            <option value="Education & Training">Education & Training</option>
                            <option value="Healthcare">Healthcare</option>
                            <option value="Senior Management">Senior Management</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_position">Position:</label>
                        <select id="edit_position" name="position" class="form-control" required>
                            <option value="" disabled>Select Position</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_address">Address:</label>
                <textarea id="edit_address" name="address" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-row mt-4">
                <div class="form-column">
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="width: 100%;">Cancel</button>
                </div>
                <div class="form-column">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Employee</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script>
    // Get the modal elements
    const addEmployeeModal = document.getElementById('addEmployeeModal');
    const editEmployeeModal = document.getElementById('editEmployeeModal');
    const addEmployeeBtn = document.getElementById('addEmployeeBtn');
    const closeAddModalBtn = addEmployeeModal.querySelector('.close');
    const closeEditModalBtn = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    // Open the add employee modal
    addEmployeeBtn.addEventListener('click', function() {
        addEmployeeModal.style.display = 'block';
    });

    // Close the add employee modal
    closeAddModalBtn.addEventListener('click', function() {
        addEmployeeModal.style.display = 'none';
    });

    // Close the edit employee modal with the X button
    closeEditModalBtn.addEventListener('click', function() {
        editEmployeeModal.style.display = 'none';
    });

    // Close the edit employee modal with Cancel button
    cancelEditBtn.addEventListener('click', function() {
        editEmployeeModal.style.display = 'none';
    });

    // Also close modals when clicking outside of them
    window.addEventListener('click', function(event) {
        if (event.target === addEmployeeModal) {
            addEmployeeModal.style.display = 'none';
        }
        if (event.target === editEmployeeModal) {
            editEmployeeModal.style.display = 'none';
        }
    });

    // Make table rows clickable to view employee details
        document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
        
        // Real-time search functionality
        const searchInput = document.getElementById('employee-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const cin = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const department = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const position = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    
                    // Check if any field contains the search term
                    const matches = 
                        cin.includes(searchTerm) || 
                        name.includes(searchTerm) || 
                        email.includes(searchTerm) || 
                        department.includes(searchTerm) || 
                        position.includes(searchTerm);
                    
                    // Show or hide the row based on the search result
                    row.style.display = matches ? '' : 'none';
                });
                
                // Add no results message if all rows are hidden
                let noResultsMsg = document.getElementById('no-search-results');
                const visibleRows = document.querySelectorAll('tbody tr[style=""]').length;
                
                if (searchTerm && visibleRows === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'no-search-results';
                        noResultsMsg.className = 'no-results-message';
                        noResultsMsg.textContent = 'No employees found matching your search.';
                        
                        const table = document.querySelector('table');
                        table.parentNode.insertBefore(noResultsMsg, table.nextSibling);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            });
        }
        });

    // Department-Position mapping
    const positionsByDepartment = {
        'Administration': ['Receptionist', 'Office Manager', 'Data Entry Clerk', 'Executive Assistant'],
        'Human Resources (HR)': ['HR Assistant', 'HR Manager', 'Training Coordinator', 'Recruitment Officer'],
        'Finance & Accounting': ['Accountant', 'Finance Manager', 'Payroll Specialist', 'Auditor'],
        'Sales & Marketing': ['Sales Representative', 'Marketing Specialist', 'Social Media Manager', 'Public Relations (PR) Officer'],
        'IT & Technical': ['IT Technician', 'Software Developer', 'System Administrator', 'Cybersecurity Specialist'],
        'Operations & Logistics': ['Warehouse Supervisor', 'Operations Manager', 'Supply Chain Coordinator', 'Transport Manager', 'Technical Support Specialist', 'Client Relations Officer'],
        'Engineering': ['Mechanical Engineer', 'Electrical Engineer', 'Civil Engineer', 'Industrial Engineer'],
        'Construction': ['Site Supervisor', 'Project Manager', 'Architect'],
        'Manufacturing': ['Production Manager', 'Quality Assurance Inspector', 'Factory Worker'],
        'Security & Maintenance': ['Security Guard', 'Maintenance Technician', 'Facility Manager'],
        'Education & Training': ['Teacher', 'Trainer', 'University Professor'],
        'Healthcare': ['Nurse', 'Doctor', 'Pharmacist'],
        'Senior Management': ['CEO / General Manager', 'Chief Operating Officer (COO)', 'Chief Financial Officer (CFO)']
    };

    // Update position dropdown based on selected department
    function updatePositionOptions() {
        const departmentSelect = document.getElementById('department');
        const positionSelect = document.getElementById('position');
        
        // Clear existing options
        positionSelect.innerHTML = '<option value="" disabled selected>Select Position</option>';
        
        // Get selected department
        const selectedDepartment = departmentSelect.value;
        
        // If a department is selected, populate positions
        if (selectedDepartment && positionsByDepartment[selectedDepartment]) {
            const positions = positionsByDepartment[selectedDepartment];
            positions.forEach(position => {
                const option = document.createElement('option');
                option.value = position;
                option.textContent = position;
                positionSelect.appendChild(option);
            });
        }
    }

    // Update position dropdown in edit form based on selected department
    function updateEditPositionOptions() {
        const departmentSelect = document.getElementById('edit_department');
        const positionSelect = document.getElementById('edit_position');
        
        // Get selected department
        const selectedDepartment = departmentSelect.value;
        
        // Clear existing options except the first one (which is the current position)
        const currentPosition = positionSelect.value;
        positionSelect.innerHTML = '<option value="" disabled>Select Position</option>';
        
        // If a department is selected, populate positions
        if (selectedDepartment && positionsByDepartment[selectedDepartment]) {
            const positions = positionsByDepartment[selectedDepartment];
            positions.forEach(position => {
                const option = document.createElement('option');
                option.value = position;
                option.textContent = position;
                if (position === currentPosition) {
                    option.selected = true;
                }
                positionSelect.appendChild(option);
            });
        }
    }

    // Format date string for date input fields
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // Open the edit employee modal with pre-filled data
    function openEditModal(cin, fullName, email, department, position) {
        // Set form action URL
        document.getElementById('editEmployeeForm').action = `/employees/${cin}`;
        
        // Set basic values
        document.getElementById('edit_fullName').value = fullName;
        document.getElementById('edit_email').value = email;
        
        // Set department and populate position options
        const departmentSelect = document.getElementById('edit_department');
        departmentSelect.value = department;
        
        const positionSelect = document.getElementById('edit_position');
        positionSelect.innerHTML = '<option value="" disabled>Select Position</option>';
        
        // Add positions for the selected department
        if (positionsByDepartment[department]) {
            positionsByDepartment[department].forEach(pos => {
                const option = document.createElement('option');
                option.value = pos;
                option.textContent = pos;
                if (pos === position) {
                    option.selected = true;
                }
                positionSelect.appendChild(option);
            });
        }
        
        // Fetch additional employee data (phone, address, etc.)
        fetch(`/employees/${cin}/get-data`)
                        .then(response => response.json())
                        .then(data => {
                document.getElementById('edit_phone').value = data.phone || '';
                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_date_of_birth').value = formatDateForInput(data.date_of_birth);
                
                const genderSelect = document.getElementById('edit_gender');
                if (data.gender) {
                    genderSelect.value = data.gender;
                } else {
                    genderSelect.selectedIndex = 0;
                }
                        })
                        .catch(error => {
                console.error('Error fetching employee data:', error);
            });
        
        // Display the modal
        editEmployeeModal.style.display = 'block';
    }

    // Add event listener to edit department dropdown
    document.getElementById('edit_department').addEventListener('change', updateEditPositionOptions);
    </script>
@endsection
