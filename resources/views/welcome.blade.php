<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Inventory Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            align-items: center;
            width: 600px;
            text-align: left;
        }

        .form-group {
            flex: 1;
            padding-right: 20px;
        }

        .image-container {
            width: 300px;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        h2 {
            margin-bottom: 50px;
            color: #35424a;
            text-align: left;
            font-size: 25px;
            line-height: 1.4;
        }

        .btn {
            background-color: #ffcccb;
            color: #35424a;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 200px;
            font-weight: bold;
            transition: background 0.3s;
            margin: 10px auto;
            font-size: 14px;
            display: block;
            text-align: center; /* Center text inside the button */
            text-decoration: none; /* Remove underline */
        }

        .btn:hover {
            background-color: #ffb3ba;
        }

        .switch-form {
            text-align: center;
            margin-top: 10px;
        }

        .switch-form a {
            color: #35424a;
            text-decoration: none; /* Remove underline for links */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-group">
        <h2>Smart Inventory Management System</h2>
        <a href="{{ route('login') }}" class="btn">Login</a>
    </div>
    <div class="image-container">
        <img src="{{ asset('images/inventory-management-system.webp') }}" alt="Inventory Management System" onerror="this.onerror=null; this.src='https://via.placeholder.com/300';">
    </div>
</div>
</body>
</html>
