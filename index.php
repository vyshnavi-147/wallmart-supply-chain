<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ProductInfo";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($createDbQuery) === false) {
    die("Database Creation Error: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create the product_information table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS product_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    manufactured_date DATE NOT NULL,
    expiry_date DATE NOT NULL
)";
if ($conn->query($createTableQuery) === false) {
    die("Table Creation Error: " . $conn->error);
}

// Get current date
$currentDate = date('Y-m-d');

// Retrieve product information with 4 days or less left to expire
$sql = "SELECT product_id, manufactured_date, expiry_date,
               DATEDIFF(expiry_date, '$currentDate') AS days_left
        FROM product_information
        WHERE DATEDIFF(expiry_date, '$currentDate') <= 4";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Waste Reduction and Sustainability Dashboard</title>
    <style>
        .alert {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://imgs.search.brave.com/sNkHhAifcpmiRfnPe_h7N7DL1PgmQS6vHfX96FbwYy0/rs:fit:860:0:0/g:ce/aHR0cHM6Ly93d3cu/ZWRpZ2l0YWxhZ2Vu/Y3kuY29tLmF1L3dw/LWNvbnRlbnQvdXBs/b2Fkcy9XYWxtYXJ0/LWxvZ28tcG5nLnBu/Zw" alt="WALLMART">
            <h1>Waste Reduction Dashboard</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="input_page.php">Products</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="dashboard">
        <div class="metrics">
            <div class="metric-card">
                <h2>Total Products</h2>
                <p><?php echo $result->num_rows; ?></p>
            </div>
            <div class="metric-card">
                <h2>Near Expiration</h2>
                <p><?php echo $result->num_rows; ?></p>
            </div>
            <!-- Add more metric cards -->
        </div>
        <div class="chart">
            <!-- Add chart here -->
        </div>
    </section>

    <section class="products">
        <h2>Product List</h2>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Manufactured Date</th>
                    <th>Expiry Date</th>
                    <th>Days Left to Expire</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $daysLeft = $row["days_left"];
                        $highlightClass = ($daysLeft <= 4) ? "highlight" : "";
                        echo "<tr class='$highlightClass'>";
                        echo "<td>" . $row["product_id"] . "</td>";
                        echo "<td>" . $row["manufactured_date"] . "</td>";
                        echo "<td>" . $row["expiry_date"] . "</td>";
                        echo "<td>" . $daysLeft . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No expiring products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Add modal for product details -->

    <section class="notifications">
        <!-- Add notifications here -->
        <?php
        if ($hasAlertProducts) {
            echo "<div class='alert'>ALERT!</div>";
        }
        ?>
    </section>

    <footer>
        <p>&copy; 2023 Waste Reduction Dashboard</p>
        <ul>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </footer>
</body>
</html>