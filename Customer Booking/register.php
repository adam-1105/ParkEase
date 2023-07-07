<?php
// register.php

require_once "config.php";

if (isset($_POST['sign_up'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    if ($password === $retype_password) {
        // Check for duplicate username or email
        $sql_check = "SELECT * FROM customer WHERE customer_username = ? OR customer_email = ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("ss", $username, $email);
            $stmt_check->execute();
            $stmt_check->store_result();
			if ($stmt_check->num_rows > 0) {
				$stmt_check->bind_result($dbUsername, $dbEmail);
				$stmt_check->fetch();
				$error_message = "The " . ($dbEmail == $email ? "email" : "username") . " already exists, please choose another one.";
				echo "<script>alert('$error_message');</script>";
				echo "<script>document.getElementById('" . ($dbEmail == $email ? "email" : "username") . "').focus();</script>";
			}
			 else {
                // No duplicate, proceed to registration
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql_register = "INSERT INTO customer (customer_username, customer_email, customer_password) VALUES (?, ?, ?)";
                $stmt_register = $conn->prepare($sql_register);
                $stmt_register->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt_register->execute()) {
                    // Registration successful
                    echo "<script>alert('Registration successful!');</script>";
                    header("Location: login.php");
                    exit();
                } else {
                    // Registration failed
                    echo "<script>alert('Registration failed. Please try again later.');</script>";
					echo "Error: " . $stmt_register->error; // Display the SQL error
                }

                $stmt_register->close();
            }
        } else {
            echo "Error: " . $conn->error; // Output any error message for debugging purposes
        }

        $stmt_check->close();
    } else {
        echo "Passwords do not match";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Parking Service</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
		body {
            background-image: url("parking 1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
			font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }

        .container {
            max-width: 500px;
        }

        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #0dcaf0;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #343a40;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
        }

        .form-container .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-container .form-label {
            color: #ffffff;
        }

        .form-container .btn-primary {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .form-container .btn-primary:hover {
            background-color: #0ac0e0;
            border-color: #0ac0e0;
        }

        body::before {
            content: '';
            background-image: url('parking-bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="text-center logo">Parking Service</div>
        <div class="form-container">
            <form action="register.php" method="post">
                <div class="form-title">Create Account</div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                        required>
                </div>
                <div class="mb-3">
                    <label for="retype_password" class="form-label">Re-Type Password</label>
                    <input type="password" name="retype_password" id="retype_password" class="form-control"
                        placeholder="Re-Type Password" required>
                </div>
                <button type="submit" name="sign_up" class="btn btn-primary w-100">Sign Up</button>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
