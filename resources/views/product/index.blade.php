<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" />
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
        <h2>Products</h2>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <button class="btn btn-primary mb-3" id="openCreateModalBtn">
        <i class="fas fa-plus"></i> Add Product
    </button>

    <!-- Table of Products -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Products Table</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-pink">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Reorder Level</th> <!-- Added Reorder Level column -->
                    <th>Category</th> <!-- Added Category column -->
                    <th>Suppliers</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->reorder_level }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>
                            @foreach ($product->suppliers as $supplier)
                                <span class="badge badge-info">{{ $supplier->name }}</span> <!-- Display multiple suppliers -->
                            @endforeach
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-info" onclick="openEditModal('{{ $product->id }}', '{{ $product->name }}', '{{ $product->description }}', '{{ $product->quantity }}', '{{ $product->price }}', '{{ $product->reorder_level }}', {{ json_encode($product->suppliers->pluck('id')) }}, '{{ $product->category_id }}')">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- View Product Link -->
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-success" title="View Product">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
</div>

<!-- The Modal for Create Product -->
<div id="createProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" required class="form-control">
                        @error('name')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" required class="form-control"></textarea>
                        @error('description')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" required class="form-control">
                        @error('quantity')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" name="price" id="price" required class="form-control">
                        @error('price')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reorder_level">Reorder Level</label>
                        <input type="number" name="reorder_level" id="reorder_level" required class="form-control">
                        @error('reorder_level')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editSupplierIds">Suppliers</label>
                        <select name="supplier_ids[]" id="editSupplierIds" multiple required class="form-control selectpicker" data-live-search="true">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_ids')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" required class="form-control selectpicker" data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Create Product" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Modal for Edit Product -->
<div id="editProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" action="" method="POST">
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
                        <label for="editDescription">Description</label>
                        <textarea name="description" id="editDescription" required class="form-control"></textarea>
                        @error('description')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Quantity</label>
                        <input type="number" name="quantity" id="editQuantity" required class="form-control">
                        @error('quantity')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editPrice">Price</label>
                        <input type="number" step="0.01" name="price" id="editPrice" required class="form-control">
                        @error('price')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editReorderLevel">Reorder Level</label>
                        <input type="number" name="reorder_level" id="editReorderLevel" required class="form-control">
                        @error('reorder_level')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editCategoryId">Category</label>
                        <select name="category_id" id="editCategoryId" required class="form-control selectpicker" data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Update Product" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('openCreateModalBtn').addEventListener('click', function() {
        $('#createProductModal').modal('show');
    });

    function openEditModal(id, name, description, quantity, price, reorderLevel, suppliers, categoryId) {
        $('#editProductForm').attr('action', `/products/${id}`);
        $('#editName').val(name);
        $('#editDescription').val(description);
        $('#editQuantity').val(quantity);
        $('#editPrice').val(price);
        $('#editReorderLevel').val(reorderLevel);

        $('#editSupplierIds option').prop('selected', false);
        suppliers.forEach(function(supplierId) {
            $('#editSupplierIds option[value="' + supplierId + '"]').prop('selected', true);
        });

        $('#editCategoryId').val(categoryId).selectpicker('refresh'); // Set the selected category

        $('#editProductModal').modal('show');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this product?');
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
</body>
</html>
