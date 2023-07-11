<?php
session_start();

// Database connection configuration
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$database = "vehicle-parking-db";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $database);

// Retrieve the username and password from the form submission
$username = $_POST['uname'];
$password = $_POST['pword'];

// Sanitize the input
$username = filter_var($username, FILTER_SANITIZE_STRING);
$password = filter_var($password, FILTER_SANITIZE_STRING);

// Prepare the SQL statement to fetch the user from the database
$sql = "SELECT * FROM customer WHERE customer_username = ?";

// Prepare the query
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle the prepare error
    die("Error: " . $conn->error);
}

// Bind the username parameter to the prepared statement
$stmt->bind_param("s", $username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify the password using password_verify function
    if (password_verify($password, $row['customer_password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['phone'] = $row['customer_phone'];
        header("Location: booking.php");
        exit();
    }
}

header("Location: index.php");
exit();
?>
