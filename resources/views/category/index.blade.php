<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Categories</title>
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
        <h2>Categories</h2>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <button class="btn btn-primary mb-3" id="openCreateModalBtn">
        <i class="fas fa-plus"></i> Add Category
    </button>

    <!-- Table of Categories -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Categories Table</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-pink">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? 'N/A' }}</td>
                        <td>
                            <!-- Button to open the edit modal -->
                            <button class="btn btn-info" onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->description }}')">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Link to view the category details -->
                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-success">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Form to delete the category -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
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

<!-- The Modal for Create Category -->
<div id="createCategoryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.store') }}" method="POST">
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
                        <textarea name="description" id="description" class="form-control"></textarea>
                        @error('description')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Create Category" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Modal for Edit Category -->
<div id="editCategoryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" action="" method="POST">
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
                        <textarea name="description" id="editDescription" class="form-control"></textarea>
                        @error('description')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="submit" value="Update Category" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('openCreateModalBtn').addEventListener('click', function() {
        $('#createCategoryModal').modal('show');
    });

    function openEditModal(id, name, description) {
        $('#editCategoryForm').attr('action', `/categories/${id}`);
        $('#editName').val(name);
        $('#editDescription').val(description);

        $('#editCategoryModal').modal('show');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this category?');
    }
</script>
</body>
</html>
