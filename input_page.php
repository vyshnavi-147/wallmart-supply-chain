<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$error_message = '';
$success_message = '';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ProductInfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $manufactured_date = $_POST["manufactured_date"];
    $expiry_date = $_POST["expiry_date"];

    // Insert product data into the database
    $insertQuery = "INSERT INTO product_information (product_id, manufactured_date, expiry_date)
                    VALUES ('$product_id', '$manufactured_date', '$expiry_date')";
    if ($conn->query($insertQuery) === true) {
        $success_message = "Product information saved successfully.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Information Input</title>

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
            padding: 15px; /* Increase padding to create a larger container */
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
        input[type="date"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        input[type="submit"] {
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

        a {
            color: #0076c8; /* Walmart blue */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="https://cdn.mos.cms.futurecdn.net/5StAbRHLA4ZdyzQZVivm2c.jpg" 
    alt="Walmart Logo" width="300" height="100" style="position: absolute; top: 10px; left: 10px;">
           <h2>Product Information Input</h2>
        <p>Welcome, <?php echo $_SESSION["email"]; ?>! You are logged in.</p>

        <form method="post" action="">
            <label for="product_id">Product ID:</label><br>
            <input type="text" id="product_id" name="product_id" required><br>
            <label for="manufactured_date">Manufactured Date:</label><br>
            <input type="date" id="manufactured_date" name="manufactured_date" required><br>
            <label for="expiry_date">Expiry Date:</label><br>
            <input type="date" id="expiry_date" name="expiry_date" required><br>
            <input type="submit" value="Save Product Information">
            <p class="error"><?php echo $error_message; ?></p>
            <p class="success"><?php echo $success_message; ?></p>
        </form>

        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
