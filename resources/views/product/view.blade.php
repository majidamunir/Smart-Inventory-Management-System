<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin-top: 40px;
        }
        .card-header {
            background-color: #ffcccb;
            color: #333;
            font-weight: bold;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-footer {
            background-color: #f8f9fa;
        }
        .btn-primary {
            background-color: #ea8aaf;
            border-color: #ea8aaf;
        }
        .btn-primary:hover {
            background-color: #ea8aaf;
            border-color: #ea8aaf;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">{{ $product->name }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            Product Details
        </div>
        <div class="card-body">
            <h5 class="card-title">SKU: {{ $product->sku }}</h5>
            <p class="card-text"><strong>Description:</strong> {{ $product->description }}</p>
            <p class="card-text"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p class="card-text"><strong>Quantity Available:</strong> {{ $product->quantity }}</p>
            <p class="card-text"><strong>Reorder Level:</strong> {{ $product->reorder_level }}</p>
            <p class="card-text"><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
        </div>
        <div class="card-footer">
            <strong>Suppliers:</strong>
            <ul class="list-unstyled">
                @foreach($product->suppliers as $supplier)
                    <li><i class="fas fa-user"></i> {{ $supplier->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
