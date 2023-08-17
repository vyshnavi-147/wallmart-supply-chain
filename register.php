<?php
$error_message = '';
$success_message = '';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the login database if it doesn't exist
$loginDb = "login_db";
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $loginDb";
if ($conn->query($createDbQuery) === false) {
    die("Database Creation Error: " . $conn->error);
}

// Select the login database
$conn->select_db($loginDb);

// Create the employees table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($createTableQuery) === false) {
    die("Table Creation Error: " . $conn->error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Insert data into the employees table
    $insertQuery = "INSERT INTO employees (email, username, password)
                    VALUES ('$email', '$username', '$password')";
    if ($conn->query($insertQuery) === true) {
        $success_message = "Registration successful! You can now login.";
    } else {
        $error_message = "Registration failed: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <img align="left" src="https://image.cnbcfm.com/api/v1/image/104414887-GettyImages-494315703.jpg?v=1533072631" alt="walmart1" width="800" height="550">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #0076c8; /* Blue background color */
        }

        .container {
            background-color: #ffff99; /* Yellow container color */
            border: 1px solid #ddd;
            padding: 60px; /* Increase padding to create a larger container */
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px; /* Limit container's maximum width */
            margin: 0 auto; /* Center the container horizontally */
        }

        h2 {
            color: #0076c8; /* Walmart blue */
        }

        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        input[type="submit"],
        button {
            background-color: #0076c8; /* Walmart blue */
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1 style="position: absolute; top: 10px; left: 20px; color: #fff; font-family: 'Arial', sans-serif; font-size: 24px;">Welcome to the Registration page of WALMART ! kindly create an account.</h1>
        <h2>Registration</h2>
        <form method="post" action="">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Register">
            <button onclick="window.location.href='login.php'">Login</button>
            <p class="error"><?php echo $error_message; ?></p>
            <p class="success"><?php echo $success_message; ?></p>
        </form>
    </div>
</body>
</html>
