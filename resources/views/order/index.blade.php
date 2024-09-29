{{-- resources/views/orders/index.blade.php --}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Index</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> {{-- Assuming you have some CSS --}}
</head>
<body>

<div class="container">
    <h1>Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Expected Delivery Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->supplier->name }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->expected_delivery_date->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
