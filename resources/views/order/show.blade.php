<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin-top: 40px;
        }

        .card-header {
            background-color: #ffcccb;
            color: #333;
        }

        .card-body {
            padding: 1.5rem;
        }

        .order-details th {
            width: 200px;
            text-align: left;
            background-color: #ffcccb;
            padding: 10px;
            font-weight: bold;
        }

        .order-details td {
            padding: 10px;
            text-align: left;
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
            color: orange;
        }

        .status-confirmed {
            color: blue;
        }

        .status-shipped {
            color: purple;
        }

        .status-delivered {
            color: green;
        }

        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="heading-container mb-4">
        <h2>Order Details</h2>
    </div>

    <!-- Error message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Order Details -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Order Information</h4>
        </div>
        <div class="card-body">
            <table class="table order-details">
                <tr>
                    <th>Order ID</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td class="status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <th>Date Created</th>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>Product Ordered</th>
                    <td>{{ $order->product->name }} ({{ $order->quantity }})</td>
                </tr>
            </table>
            <br>

            <!-- Admin Actions for Pending Orders -->
            @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager']))
                @if($order->status === 'pending')
                    <form action="{{ route('orders.approve', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form action="{{ route('orders.disapprove', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Disapprove</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
    </div>
</div>
</body>
</html>
