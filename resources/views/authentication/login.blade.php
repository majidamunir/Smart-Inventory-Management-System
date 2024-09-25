<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            width: 400px;
            margin: auto;
            margin-top: 100px;
            border: 1px solid #ffcccb;
        }
        .card-header {
            background-color: #ffcccb;
            border-bottom: none;
        }
        .card-body {
            background-color: white;
        }
        .form-control {
            background-color: #f8f9fa;
            color: black;
        }
        label {
            color: black;
        }
        .error {
            color: red;
            font-size: 0.875rem;
        }
        .btn-primary {
            background-color: #ffcccb;
            border-color: #ffcccb;
            color: black;
        }
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:active:focus {
            background-color: #ffcccb;
            border-color: #ffcccb;
            color: black;
            box-shadow: none;
        }
        .card-header h4 {
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h4 class="mb-0">Login</h4>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email" value="{{ old('email') }}">
                    @error('email')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                    @error('password')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
