<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suppliers</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

        .table .thead-light th {
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
            background-color: #d78d9b;
            border-color: #d78d9b;
        }

        /* Supplier Status Colors */
        .status-approved {
            color: green;
        }

        .status-rejected {
            color: red;
        }

        .status-shipped {
            color: purple;
        }

        .status-accepted {
            color: orange;
        }

        /* Confirmation Button Styling */
        .confirmation-button {
            display: inline;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="heading-container mb-4">
        <h2>Suppliers</h2>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Suppliers Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Suppliers Table</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->id }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td class="status-{{ strtolower($supplier->status) }}">{{ ucfirst($supplier->status) }}</td>
                        <td>
                            <!-- Accept Action -->
                            @if ($supplier->status === 'pending')
                                <form action="{{ route('orders.accept', $supplier->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-thumbs-up"></i>
                                    </button>
                                </form>
                                <form action="{{ route('suppliers.orders.reject', $supplier->id) }}" method="POST"
                                      class="confirmation-button" onsubmit="return confirm('Reject this supplier?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-times-circle"></i>
                                    </button>
                                </form>
                            @endif

                            @foreach ($supplier->orders as $order)
                                @if ($order->status === 'accepted')
                                    <form action="{{ route('suppliers.ship', ['supplier' => $supplier->id, 'order' => $order->id]) }}" method="POST" class="confirmation-button" onsubmit="return confirm('Ship this order?')">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa-shipping-fast"></i>
                                        </button>
                                    </form>
                                @endif
                            @endforeach

                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                  class="confirmation-button" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Delete Supplier"
                                        aria-label="Delete Supplier">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No suppliers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination links (if applicable) -->
            @if(method_exists($suppliers, 'links'))
                <div class="pagination justify-content-center mt-4">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this supplier?');
    }
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
