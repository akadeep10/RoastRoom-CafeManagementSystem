<!-- login.php -->
<?php
session_start();
include('db.php');

$error_message = ""; // Variable to hold error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['password'] === $password) { // Compare directly to the plain-text password
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: landing.html"); // Redirect to landing page after login
            exit();
        } else {
            $error_message = "Invalid password."; // Set error message
        }
    } else {
        $error_message = "No user found with this email."; // Set error message
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #4F6F52; /* Light gray background */
            color: #343a40; /* Dark gray text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #F5EFE6; /* White background for form */
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
            color: #4F6F52; /* Heading color matching */
        }

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
            background-color: #4F6F52; /* Coral red button */
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
            background-color: #415b44; /* Darker coral red on hover */
        }

        p {
            margin-top: 15px;
        }

        a {
            color: #4F6F52; /* Coral red link */
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

        .error-message {
            color: red; /* Red color for error messages */
            margin-bottom: 15px;
        }

        #logo {
            height: 400px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <img src="./Images/Coffee.png" id="logo" alt="">
    <div class="login-container">
        <h2>Login to Roast Room</h2>
        
        <!-- Display error message here -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Signup here</a></p>
    </div>
</body>
</html>
