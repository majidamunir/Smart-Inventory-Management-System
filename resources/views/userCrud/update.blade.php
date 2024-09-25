<!-- resources/views/roles/edit.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role</title>
    <style>
        /* Same styles as in create.blade.php */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #e91e63; /* Pink color */
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #e91e63; /* Pink color */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #d81b60; /* Darker pink */
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #e91e63;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #d81b60;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Role</h1>
    <form action="{{ route('roles.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="" disabled>Select a role</option>
                <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="supplier" {{ $user->role === 'supplier' ? 'selected' : '' }}>Supplier</option>
                <option value="procurement_officer" {{ $user->role === 'procurement_officer' ? 'selected' : '' }}>Procurement Officer</option>
                <option value="warehouse_manager" {{ $user->role === 'warehouse_manager' ? 'selected' : '' }}>Warehouse Manager</option>
            </select>
        </div>
        <input type="submit" value="Update Role">
    </form>
    <a href="{{ route('roles.index') }}" class="btn">Back to Roles</a>
</div>
</body>
</html>
