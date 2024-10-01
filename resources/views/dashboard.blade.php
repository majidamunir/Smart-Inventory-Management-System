<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard - Smart Inventory Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #35424a;
            display: flex;
            height: 100vh;
        }

        .dashboard {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 20px;
            margin-left: 220px;
        }

        .sidebar {
            background-color: #e8f5e9;
            color: #35424a;
            min-width: 220px;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 20px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: #35424a;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: background 0.3s;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #b3e6b3;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .header {
            background-color: #ffcccb;
            color: #35424a;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 150px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            transition: transform 0.2s;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .card p {
            margin: 10px 0;
        }

        .footer {
            background-color: #ffcccb;
            color: #35424a;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        .inventory-summary {
            background-color: #e3f2fd;
        }

        .recent-orders-card {
            background-color: #fff3e0;
        }

        .supplier-performance {
            background-color: #f1f8e9;
        }

        .stock-alerts {
            background-color: #fce4ec;
        }

        .warehouse-utilization {
            background-color: #e8f5e9;
        }

        .sales-overview {
            background-color: #ffe0b2;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
        }

        .btn-light:hover {
            background-color: #e0e0e0;
            color: #000;
        }

    </style>
</head>
<body>
<aside class="sidebar">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'cashier']))
            <li><a href="{{ route('products.index') }}"><i class="fas fa-box"></i> Inventory</a></li>
            <li><a href="{{ route('categories.index') }}"><i class="fas fa-box"></i> Categories</a></li>
        @endif
        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'procurement_officer']))
            <li><a href="{{ route('orders.index') }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        @endif
        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'cashier']))
            <li><a href="{{ route('transactions.index') }}"><i class="fas fa-money-bill-alt"></i> Transactions</a></li>
        @endif
        @if((auth()->user()->role === 'supplier'))
            <li><a href="{{ route('orders.index') }}"><i class="fas fa-truck"></i> Suppliers</a></li>
        @endif
    </ul>
    <form action="{{ route('logout') }}" method="POST" style="margin-top: auto; display: inline;">
        @csrf
        <button type="submit"
                style="background: none; border: none; color: inherit; cursor: pointer; width: 100%; padding: 10px 15px; text-align: left; font-size: medium">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</aside>

<div class="dashboard">
    <header class="header">
        <h1>Smart Inventory Management System</h1>
    </header>

    <main class="main-content">
        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager']))
            <section class="card inventory-summary">
                <h2>User Roles</h2>
                <a href="{{ route('roles.index') }}" class="btn btn-light">Users</a>
            </section>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'cashier']))
            <section class="card inventory-summary">
                <h2>Product Inventory</h2>
                <a href="{{ route('products.index') }}" class="btn btn-light">Products</a>
            </section>
            <section class="card recent-orders-card">
                <h2>Product Categories</h2>
                <a href="{{ route('categories.index') }}" class="btn btn-light">Categories</a>
            </section>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'procurement_officer']))
            <section class="card recent-orders-card">
                <h2>Recent Orders</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-light">Orders</a>
            </section>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'warehouse_manager', 'cashier']))
            <section class="card stock-alerts">
                <h2>Product Transactions</h2>
                <a href="{{ route('transactions.index') }}" class="btn btn-light">Transactions</a>
            </section>
        @endif

        @if((auth()->user()->role === 'supplier'))
            <section class="card supplier-performance">
                <h2>Supplier Performance</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-light">Suppliers</a>
            </section>
        @endif
    </main>

    <footer class="footer">
        <p>&copy; 2024 Smart Inventory Management System</p>
    </footer>
</div>
</body>
</html>
