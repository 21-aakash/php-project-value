<!-- logout.php -->
<?php
session_start(); // Start the session

// Destroy the session to log the user out
session_unset(); 
session_destroy(); 

// Redirect to the home page after logout
header("Location: register.php");
exit();
?>
