<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['sno']) || !is_numeric($_GET['sno'])) {
    echo "Invalid or missing 'sno' parameter.";
    exit;
}

$sno = intval($_GET['sno']); // Sanitize and convert to integer

// Connect to the database
$server = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$con = new mysqli($server, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Prepare the SQL statement to prevent SQL injection
$sql = "DELETE FROM users WHERE sno=?";
$stmt = $con->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("i", $sno);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
