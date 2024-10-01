<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Orders</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
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

        /* Order Status Colors */
        .status-pending {
            color: blue;
        }

        .status-approved {
            color: green;
        }

        .status-disapproved {
            color: red;
        }

        .status-cancelled {
            color: gray;
        }

        .status-shipped {
            color: orange;
        }

        .status-delivered {
            color: purple;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="heading-container mb-4">
        @if(auth()->check())
            @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'procurement_officer']))
                <h2>Orders</h2>
            @elseif(auth()->user()->role === 'supplier')
                <h2>Supplier Orders</h2>
            @else
                <h2>Access Denied</h2>
                <p>You do not have permission to view this section.</p>
            @endif
        @endif
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

    <!-- Orders Table for Admin and Procurement Officer -->
    @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'procurement_officer']))
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title mb-0">Orders History</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Supplier Name</th> <!-- Added Supplier Name Column -->
                        <th>Total Price</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        @if(in_array(auth()->user()->role, ['admin', 'procurement_officer']))
                            <th>Actions</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->supplier->name }}</td> <!-- Display Supplier Name -->
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</td>
                            @if(in_array(auth()->user()->role, ['admin', 'procurement_officer']))
                                <td>
                                    <!-- View Button -->
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-success"
                                       title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Actions Based on Order Status -->
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('orders.approve', $order->id) }}" method="POST"
                                              style="display: inline;" onsubmit="return confirm('Approve this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </form>
                                        <form action="{{ route('orders.disapprove', $order->id) }}" method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Disapprove this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Disapprove</button>
                                        </form>
                                    @elseif ($order->status === 'shipped')
                                        <form action="{{ route('orders.delivered', $order->id) }}" method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Mark this order as delivered?')">
                                            @csrf
                                            <button type="submit" class="btn btn-info">Mark as Delivered</button>
                                        </form>
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                                              style="display: inline;" onsubmit="return confirm('Cancel this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No orders found.</td> <!-- Updated colspan -->
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <!-- Pagination links (if applicable) -->
                @if(method_exists($orders, 'links'))
                    <div class="pagination justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Orders Table for Supplier -->
    @if(auth()->user()->role === 'supplier')
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title mb-0">Supplier Table</h4>
            </div>
            <div class="card-body">
                @if($orders->isEmpty())
                    <p>No orders found for this supplier.</p>
                @else
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Supplier Name</th> <!-- Added Supplier Name Column -->
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>${{ number_format($order->total_price, 2) }}</td>
                                <td class="status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>{{ $order->supplier->name }}</td> <!-- Display Supplier Name -->
                                <td>
                                    <!-- View Button -->
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-success"
                                       title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Actions Based on Order Status -->
                                    @if ($order->status === 'approved')
                                        <form action="{{ route('orders.accept', $order->id) }}" method="POST"
                                              style="display: inline;" onsubmit="return confirm('Accept this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Accept</button>
                                        </form>
                                        <form action="{{ route('orders.reject', $order->id) }}" method="POST"
                                              style="display: inline;" onsubmit="return confirm('Reject this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    @elseif ($order->status === 'accepted')
                                        <form action="{{ route('orders.ship', $order->id) }}" method="POST"
                                              style="display: inline;" onsubmit="return confirm('Ship this order?')">
                                            @csrf
                                            <button type="submit" class="btn btn-info">Ship</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination links (if applicable) -->
                    @if(method_exists($orders, 'links'))
                        <div class="pagination justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
</div>

<!-- JavaScript Confirmation for Deletion -->
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this order?');
    }
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
