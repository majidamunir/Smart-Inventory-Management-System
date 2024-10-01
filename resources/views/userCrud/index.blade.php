<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin-top: 40px;
        }

        .card-header {
            background-color: #ffcccb;
            color: #333;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table .thead-pink th {
            background-color: #ffcccb;
            color: #555;
            font-weight: bold;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #ea8aaf;
            border-color: #ea8aaf;
        }

        .btn-primary:hover {
            background-color: #ea8aaf;
            border-color: #ea8aaf;
        }

        .modal-header {
            background-color: #ea8aaf;
            color: #fff;
        }

        .btn-danger {
            background-color: #d32f2f;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c62828;
        }

        .error {
            color: #d32f2f;
            font-size: 14px;
        }

        .success {
            color: #4caf50;
            font-size: 16px;
            text-align: left;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="heading-container mb-4">
        <h2>Roles</h2>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->role === 'admin') <!-- Only show for admin -->
    <button class="btn btn-primary mb-3" id="openCreateModalBtn">
        <i class="fas fa-plus"></i> Add Role
    </button>
    @endif

    <!-- Table of Roles -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Roles Table</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-pink">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    @if(auth()->user()->role === 'admin') <!-- Only show for admin -->
                    <th>Actions</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        @if(auth()->user()->role === 'admin') <!-- Only show for admin -->
                        <td>
                            <button class="btn btn-info" onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('roles.destroy', $user->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
</div>

<!-- The Modal for Create Role -->
<div id="createRoleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" required class="form-control">
                        @error('name')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required class="form-control">
                        @error('email')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required class="form-control">
                        @error('password')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" required class="form-control">
                            <option value="" disabled selected>Select a Role</option>
                            <option value="warehouse_manager">Warehouse Manager</option>
                            <option value="procurement_officer">Procurement Officer</option>
                            <option value="cashier">Cashier</option>
                            <option value="supplier">Supplier</option>
                        </select>
                        @error('role')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Create Role" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Modal for Edit Role -->
<div id="editRoleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" name="name" id="editName" required class="form-control">
                        @error('name')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" name="email" id="editEmail" required class="form-control">
                        @error('email')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Password</label>
                        <input type="password" name="password" id="editPassword" class="form-control">
                        @error('password')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editRole">Role</label>
                        <select name="role" id="editRole" required class="form-control">
                            <option value="" disabled selected>Select a Role</option>
                            <option value="warehouse_manager">Warehouse Manager</option>
                            <option value="procurement_officer">Procurement Officer</option>
                            <option value="cashier">Cashier</option>
                            <option value="supplier">Supplier</option>
                        </select>
                        @error('role')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Update Role" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Open Create Modal
    document.getElementById('openCreateModalBtn').addEventListener('click', function() {
        $('#createRoleModal').modal('show');
    });

    function openEditModal(id, name, email, role) {
        $('#editName').val(name);
        $('#editEmail').val(email);
        $('#editRole').val(role);
        $('#editRoleForm').attr('action', '/roles/' + id);
        $('#editRoleModal').modal('show');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this role?');
    }
</script>
</body>
</html>
