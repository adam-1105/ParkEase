<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Process the data (e.g., save to database, send email, etc.)
  
  // Display a success message
  echo "Thank you, $email! Your submission has been received.";
}
?>
