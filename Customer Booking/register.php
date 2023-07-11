<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['pnum'];

    // Database connection configuration
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $database = "vehicle-parking-db";

    $conn = new mysqli($servername, $usernameDB, $passwordDB, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO `customer` (`customer_username`, `customer_password`, `customer_phone`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $phone);

    if ($stmt->execute()) {
        $successMessage = "Registration successful!";
        header("Location: index.php?message=" . urlencode($successMessage));
    } else {
        // Registration failed
        echo "Registration failed. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>
