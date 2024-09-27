<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
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

        .success {
            color: #4caf50;
            font-size: 16px;
            text-align: left;
            margin-bottom: 20px;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            display: none;
        }

        .error {
            color: #d32f2f;
            font-size: 14px;
        }

        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .search-bar input {
            width: 150px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group.d-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-group.d-flex label {
            margin-right: 10px;
        }

        .form-group.d-flex input {
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="heading-container mb-4">
        <h2>Transactions</h2>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Button to trigger transaction creation -->
    <button class="btn btn-primary mb-3" id="openCreateModalBtn">
        <i class="fas fa-plus"></i> Add Transaction
    </button>

    <!-- Create Transaction Modal -->
    <div id="createTransactionModal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ffcccb; color: #555">
                    <h5 class="modal-title" id="createTransactionModalLabel">Create Transaction</h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createTransactionForm" action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="form-group d-flex align-items-center">
                                <label for="product" class="mr-2">Select Product</label>
                                <input type="text" id="searchBar" class="form-control" placeholder="Search Product..." style="width: 150px;">
                            </div>

                            <select id="product" class="form-control" name="products[]" required>
                                <option value="" disabled selected>Please Select a Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" class="form-control" name="quantity[]" required min="1">
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-info" id="addProductBtn">Add Product</button>
                        </div>

                        <h5>Selected Products</h5>
                        <table class="table table-bordered" id="productTable">
                            <thead class="thead-pink">
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Rows will be dynamically added here -->
                            </tbody>
                        </table>

                        <div class="total-amount">
                            Total Amount: $<span id="totalAmount">0.00</span>
                        </div>

                        <button type="button" class="btn btn-primary mt-3" id="calculateAmountBtn">Calculate Amount</button>
                        <button type="submit" class="btn btn-primary mt-3" id="submitTransactionBtn"
                                style="display: none;">Submit Transaction
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Transaction History</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-pink">
                <tr>
                    <th>ID</th>
                    <th>Total Amount</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>${{ number_format($transaction->total_amount, 2) }}</td>
                        <td>
                            @foreach($transaction->details as $detail)
                                {{ $detail->product->name }} - {{ $detail->quantity }} units at
                                ${{ $detail->price_at_sale }}<br>
                            @endforeach
                        </td>
                        <td>{{ now()->setTimezone('Asia/Karachi')->format('d M Y, H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.getElementById('openCreateModalBtn').addEventListener('click', function () {
        $('#createTransactionModal').modal('show');
    });

    let productTableBody = document.querySelector('#productTable tbody');
    let totalAmountElem = document.getElementById('totalAmount');
    let totalAmount = 0;

    document.getElementById('searchBar').addEventListener('input', function () {
        let filter = this.value.toLowerCase();
        let options = document.getElementById('product').options;

        for (let i = 0; i < options.length; i++) {
            let text = options[i].text.toLowerCase();
            options[i].style.display = text.includes(filter) ? '' : 'none';
        }
    });

    document.getElementById('addProductBtn').addEventListener('click', function () {
        let selectedProduct = document.getElementById('product');
        let selectedProductId = selectedProduct.value;
        let selectedProductName = selectedProduct.options[selectedProduct.selectedIndex].text;
        let productPrice = parseFloat(selectedProduct.options[selectedProduct.selectedIndex].getAttribute('data-price'));
        let quantity = parseInt(document.getElementById('quantity').value);

        // Check if a product is selected
        if (selectedProductId === "") {
            alert('Please select a product.');
            return;
        }

        // Check if quantity is valid
        if (quantity < 1 || isNaN(quantity)) {
            alert('Please enter a valid quantity.');
            return;
        }

        // Check if the product already exists in the table
        let existingRow = Array.from(productTableBody.rows).find(row => row.cells[0].textContent === selectedProductName);

        if (existingRow) {
            // Update quantity and subtotal for existing product
            let existingQuantity = parseInt(existingRow.cells[1].textContent);
            let newQuantity = existingQuantity + quantity;
            existingRow.cells[1].textContent = newQuantity;

            let newSubtotal = productPrice * newQuantity;
            existingRow.cells[3].textContent = `$${newSubtotal.toFixed(2)}`;

            // Update total amount
            totalAmount += productPrice * quantity; // Add the new quantity's subtotal
            totalAmountElem.textContent = totalAmount.toFixed(2);

            // Remove hidden inputs for the previous quantity
            let hiddenInputs = document.getElementsByName('quantity[]');
            Array.from(hiddenInputs).forEach(input => {
                if (input.value === existingQuantity.toString()) {
                    input.value = newQuantity;
                }
            });
            return;
        }

        let subtotal = productPrice * quantity;
        totalAmount += subtotal;

        let row = document.createElement('tr');
        row.innerHTML = `
        <td>${selectedProductName}</td>
        <td>${quantity}</td>
        <td>$${productPrice.toFixed(2)}</td>
        <td>$${subtotal.toFixed(2)}</td>
        <td><button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fas fa-trash"></i></button></td>
    `;
        productTableBody.appendChild(row);

        let hiddenProductInput = document.createElement('input');
        hiddenProductInput.type = 'hidden';
        hiddenProductInput.name = 'products[]';
        hiddenProductInput.value = selectedProductId;

        let hiddenQuantityInput = document.createElement('input');
        hiddenQuantityInput.type = 'hidden';
        hiddenQuantityInput.name = 'quantity[]';
        hiddenQuantityInput.value = quantity;

        document.getElementById('createTransactionForm').appendChild(hiddenProductInput);
        document.getElementById('createTransactionForm').appendChild(hiddenQuantityInput);

        document.getElementById('quantity').value = '';

        row.querySelector('.remove-btn').addEventListener('click', function () {
            if (confirm('Are you sure you want to remove this product?')) {
                totalAmount -= subtotal; // Update total amount
                totalAmountElem.textContent = totalAmount.toFixed(2);
                row.remove();
                hiddenProductInput.remove();
                hiddenQuantityInput.remove();
            }
        });
    });

    $('#createTransactionModal').on('hidden.bs.modal', function () {
        document.getElementById('product').selectedIndex = 0;
        document.getElementById('quantity').value = '';
        totalAmount = 0;
        totalAmountElem.textContent = '0.00'; // Reset total display
        productTableBody.innerHTML = ''; // Clear product table
    });


    document.getElementById('calculateAmountBtn').addEventListener('click', function () {
        if (productTableBody.children.length === 0) {
            alert('Please add at least one product before calculating.');
            return;
        }

        document.getElementById('product').disabled = true;
        document.getElementById('quantity').disabled = true;
        document.getElementById('addProductBtn').disabled = true;

        document.getElementById('calculateAmountBtn').style.display = 'none';

        document.querySelector('.total-amount').style.display = 'block';
        totalAmountElem.textContent = totalAmount.toFixed(2);
        document.getElementById('submitTransactionBtn').style.display = 'inline-block';
    });

    document.getElementById('createTransactionForm').addEventListener('submit', function (event) {
        if (productTableBody.children.length === 0) {
            alert('Please add at least one product to the transaction.');
            event.preventDefault(); // Prevent form submission
        } else {
            const quantityInputs = document.getElementsByName('quantity[]');
            if (quantityInputs.length === 0) {
                alert('Please ensure quantities are provided for the selected products.');
                event.preventDefault();
            }
        }
    });
</script>

</body>
</html>
