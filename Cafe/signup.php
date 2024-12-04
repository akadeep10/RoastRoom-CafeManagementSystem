<?php
session_start();
include('db.php'); // Include your database connection file

// Initialize cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize empty cart
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "<script>alert('Email already registered. Please login.');</script>";
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error in signup. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #4F6F52;
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: #F5EFE6;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-left: 150px;
        }
        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #4F6F52;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
        }
        button:hover {
            background-color: #415b44;
        }
        p {
            margin-top: 15px;
        }
        a {
            color: #4F6F52;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
            color: #415b44;
        }
        .form-label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
            color: #343a40;
        }
        #logo {
            height: 400px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <img src="./Images/Coffee.png" id="logo" alt="">
    <div class="signup-container">
        <h2>Signup for Roast Room</h2>
        <form method="POST" action="">
            <label class="form-label" for="name">Full Name</label>
            <input type="text" name="name" id="name" required>

            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
