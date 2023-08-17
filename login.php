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
    $password = $_POST["password"];

    // Check if the entered email exists in the database
    $sql = "SELECT * FROM employees WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["password"] == $password) {
            // Successful login
            session_start();
            $_SESSION["email"] = $row["email"];
            setcookie("login_success", "true", time() + 3600, "/");
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "Entered email doesn't exist, please register.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 400px; /* Limit container's maximum width */
        margin: 0 auto; /* Center the container horizontally */
    }


        h2 {
            color: #0076c8;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        input[type="submit"],
        button {
            background-color: #0076c8;
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
    </style>
</head>
<body>
<h1 style="position: absolute; top: 10px; left: 20px; color: #fff; font-family: 'Arial', sans-serif; font-size: 24px;">Welcome to the Login page of WALMART ! kindly enter your Credentials.</h1>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="">
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
            <button onclick="window.location.href='register.php'">Register</button>
            <p class="error"><?php echo $error_message; ?></p>
        </form>
    </div>
</body>
</html>
