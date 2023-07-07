<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up Form</title>
	<link rel="stylesheet" href="style.css">
	<style>
		.password-container {
			position: relative;
		}

		.toggle-password {
			position: absolute;
			top: 30%;
			right: -90px;
			transform: translateY(-50%);
			background-color: transparent;
			border: none;
			color: #999;
			cursor: pointer;
			font-size: 12px;
			padding: 0;
			width: auto;
			height: auto;
		}
		
		input[type="password"] {
			width: 155%;
			padding: 10px;
		}
		
		.password-container {
			position: relative;
			margin-left: -100px;
		}

	</style>
</head>
<body>
	<div class="container" id="container">
		<div class="form-container register-container">
			<form action="register.php" method="post">
				<h1>Sign Up</h1>
				<input type="text" placeholder="Username" name="username">
				<input type="text" placeholder="Phone Number" name="pnum">
				<div class="password-container">
					<input type="password" placeholder="Password" name="password" id="register-password">
					<button type="button" class="toggle-password" onclick="togglePasswordVisibility('register-password')">Show</button>
				</div>
				<button>Sign Up</button>
			</form>
		</div>	
		<div class="form-container login-container">
			<form action="login.php" method="post">
			   <h2>LOGIN</h2>
			   <input type="text" placeholder="Username" name="uname">
			   <div class="password-container">
				   <input type="password" placeholder="Password" name="pword" id="login-password">
				   <button type="button" class="toggle-password" onclick="togglePasswordVisibility('login-password')">Show</button>
			   </div>
			   <button type="submit">Log In</button>
			</form>
		</div>		
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1 class="title"> Hello <br> mutuals </h1>
					<p>if you have an account, login here and start booking !</p>
					<button class="ghost" id="login">Login</button>
					<i class="1ni 1ni-arrow-left login"></i>
				</div>
				<div class="overlay-panel overlay-right">
					<h1 class="title">Reserve your<br> parking slot today!</h1>
					<p>If you haven't created an account yet, join us and start enjoying the convenience of hassle-free parking for a better experience!</p>
					<button class="ghost" id="register">register
						<i class="1ni 1ni-arrow-right register"></i>
					</button>
				</div>
			</div>
		</div>			
	</div>
	<script src="script.js"></script>
	<script>
		function togglePasswordVisibility(passwordId) {
			var passwordInput = document.getElementById(passwordId);
			var toggleButton = passwordInput.nextElementSibling;

			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				toggleButton.textContent = "Hide";
			} else {
				passwordInput.type = "password";
				toggleButton.textContent = "Show";
			}
		}
	</script>
</body>
</html>
