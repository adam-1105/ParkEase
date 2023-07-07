	<?php
	session_start();

	require_once "config.php";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST["username"];
		$password = $_POST["password"];

		// Sanitize the input
		$username = filter_var($username, FILTER_SANITIZE_STRING);
		$password = filter_var($password, FILTER_SANITIZE_STRING);

		// Prepare the query
		$stmt = $conn->prepare("SELECT customer_phone, customer_username, customer_password FROM customer WHERE customer_username = ?");
		
		if ($stmt === false) {
			// Handle the prepare error
			die("Error: " . $conn->error);
		}

		$stmt->bind_param("s", $username);

		// Execute the query
		$stmt->execute();

		// Get the result
		$result = $stmt->get_result();

		// Check if the user exists
		if ($result->num_rows == 1) {
			// Get the user row
			$row = $result->fetch_assoc();

			// Check the password
			if (password_verify($password, $row["customer_password"])) {
				// Login the user
				$_SESSION['username'] = $username;
				$_SESSION['phone'] = $row['customer_phone'];
				header("Location: booking.php");
				exit();
			} else {
				// Invalid password
				$error = "Invalid username or password";
			}
		} else {
			// User does not exist
			$error = "Invalid username or password";
		}

		// Close the statement
		$stmt->close();
	}
	

	// Close the connection
	$conn->close();
	?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
		<style>
			body {
				background-color: #222;
				font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			}

			.login-container {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
			}

			.login-card {
				width: 400px;
				background-color: rgba(0, 0, 0, 0.8);
				border-radius: 10px;
				box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
				position: relative;
			}

			body:before {
				content: "";
				position: absolute;
				width: 100%;
				height: 100%;
				background-image: url('parking 1.jpg');
				background-position: center;
				background-size: cover;
				z-index: -1;
			}

			.login-card-header {
				background-color: #0d6efd;
				border-radius: 10px 10px 0px 0px;
				padding: 20px;
				color: #fff;
				text-align: center;
			}

			.login-card-header h4 {
				margin: 0;
			}

			.login-card-body {
				padding: 20px;
			}

			.login-card-body label {
				color: #fff;
			}

			.login-card-body input[type="text"],
			.login-card-body input[type="password"] {
				background-color: #333;
				color: #fff;
				border: none;
				border-radius: 5px;
				padding: 10px;
				margin-bottom: 10px;
				height: 45px;
			}

			.login-card-body input[type="text"]:focus,
			.login-card-body input[type="password"]:focus {
				outline: none;
			}

			.login-card-body .input-group {
				position: relative;
			}

			.login-card-body .input-group .reveal-password:hover {
				cursor: pointer;
				background-color: #0d6efd;
			}

			.login-card-footer {
				background-color: #333;
				border-radius: 0px 0px 10px 10px;
				padding: 20px;
				text-align: center;
			}

			.login-card-footer p {
				margin: 0;
				color: #fff;
			}

			.btn-login {
				background-color: #0d6efd;
				border-color: #0d6efd;
				color: #fff;
			}

			.btn-login:hover {
				box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
			}

			.btn-signup {
				background-color: transparent;
				border-color: #fff;
				color: #fff;
			}

			.btn-signup:hover {
				background-color: #fff;
				color: #0d6efd;
				box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
			}

			.input-group-append {
				height: calc(1.5em + .75rem + 9px);
			}

			.reveal-password .eye-icon {
				display: inline-block;
			}

			.reveal-password .eye-slash-icon {
				display: none;
			}

			.reveal-password.show-password .eye-icon {
				display: none;
			}

			.reveal-password.show-password .eye-slash-icon {
				display: inline-block;
			}
		</style>

	</head>

	<body>
		<div class="container-fluid login-container">
			<div class="row">
				<div class="col-md-12">
					<div class="card login-card">
						<div class="card-header login-card-header">
							<h4>Login</h4>
						</div>
						<div class="card-body login-card-body">
							<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
								<div class="form-group">
									<label for="username">Username</label>
									<input type="text" name="username" class="form-control">
								</div>
								<div class="form-group">
									<label for="password">Password</label>
									<div class="input-group">
										<input type="password" name="password" class="form-control pr-1" required>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary reveal-password" type="button">
												<i class="fas fa-eye-slash"></i>
											</button>
										</div>
									</div>
								</div>
								<div class="d-flex justify-content-between">
									<div class="text-center">
										<a href="..\index.php" class="btn btn-primary btn-login mt-2">Admin Login</a> 
									</div>
						  
									<button type="submit" class="btn btn-primary btn-login mt-2">Login</button>
								</div>
								 <a href="register.php" class="btn btn-primary btn-signup mt-2">Sign Up</a>
									</div>
							</form>
						</div>
						<?php if (isset($error)) : ?>
							<div class="card-footer login-card-footer">
								<p class="error"><?php echo htmlspecialchars($error); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<script>
			// Get the password input field and the reveal password button
			var passwordInput = document.querySelector('input[name="password"]');
			var revealPasswordButton = document.querySelector('.reveal-password');
			var revealPasswordIcon = revealPasswordButton.querySelector('i');

			// Toggle the password input field when the button is clicked
			revealPasswordButton.addEventListener('click', function() {
				if (passwordInput.type === 'password') {
					passwordInput.type = 'text';
					revealPasswordIcon.classList.remove('fa-eye-slash');
					revealPasswordIcon.classList.add('fa-eye');
				} else {
					passwordInput.type = 'password';
					revealPasswordIcon.classList.remove('fa-eye');
					revealPasswordIcon.classList.add('fa-eye-slash');
				}
			});
		</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</body>

	</html>
