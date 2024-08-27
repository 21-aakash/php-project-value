 <?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Check if 'sno' is set and is a valid number
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $con->real_escape_string($_POST['firstname']);
    $lastname = $con->real_escape_string($_POST['lastname']);
    $mobile = $con->real_escape_string($_POST['mobile']);
    $dob = $con->real_escape_string($_POST['dob']);

    // Update user information (excluding profile image)
    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', phone='$mobile', dob='$dob' WHERE sno=$sno";

    if ($con->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating record: " . $con->error;
    }
} else {
    $sql = "SELECT * FROM users WHERE sno=$sno"; // Use sanitized sno
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
}

$con->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:  #DDDDDD;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .edit-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .edit-form input[type="text"],
        .edit-form input[type="date"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .edit-form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .edit-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="edit-form">
        <h2>Edit User</h2>
        <form action="edit.php?sno=<?php echo $sno; ?>" method="POST">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>" required><br>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($user['lastname'] ?? ''); ?>" required><br>

            <label for="mobile">Mobile Number:</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>" required><br>

            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>
