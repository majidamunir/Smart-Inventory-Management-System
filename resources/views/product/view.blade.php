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

        .product-details th {
            width: 200px;
            text-align: left;
            background-color: #ffcccb;
            padding: 10px;
            font-weight: bold;
        }

        .product-details td {
            padding: 10px;
            text-align: left;
        }

        .btn-primary {
            background-color: #ea8aaf;
            border-color: #ea8aaf;
        }

        .btn-primary:hover {
            background-color: #d78d9b;
            border-color: #d78d9b;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="heading-container mb-4 text-center">
        <h2>{{ $product->name }}</h2>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Product Information</h4>
        </div>
        <div class="card-body">
            <table class="table product-details">
                <tr>
                    <th>SKU</th>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $product->description }}</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>${{ number_format($product->price, 2) }}</td>
                </tr>
                <tr>
                    <th>Quantity Available</th>
                    <td>{{ $product->quantity }}</td>
                </tr>
                <tr>
                    <th>Reorder Level</th>
                    <td>{{ $product->reorder_level }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
{{--            <strong>Suppliers:</strong>--}}
            <ul class="list-unstyled">
                @foreach($product->suppliers as $supplier)
                   <li><strong>Send this Order:</strong> {{ $supplier->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Back
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
