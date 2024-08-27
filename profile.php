<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$userSno = $user['sno'];

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
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    $updateSql = "UPDATE users SET firstname=?, email=?, dob=?";
    $params = [$firstname, $email, $dob];

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['profile_image']['name'];
        $imageTmpName = $_FILES['profile_image']['tmp_name'];
        $imagePath = 'uploads/' . basename($imageName);

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Update profile image path in the database
            $updateSql .= ", profile_image=?";
            $params[] = $imagePath;
        } else {
            echo "Failed to upload image.";
            exit;
        }
    }

    $updateSql .= " WHERE sno=?";
    $params[] = $userSno;

    $stmt = $con->prepare($updateSql);
    if ($stmt) {
        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

        if ($stmt->execute()) {
            // Update session user info
            $_SESSION['user']['firstname'] = $firstname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['dob'] = $dob;
            if (isset($imagePath)) {
                $_SESSION['user']['profile_image'] = $imagePath;
            }

            // Redirect to dashboard.php after updating
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #DDDDDD;
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

    .profile-container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    .profile-image {
        display: block;
        margin: 0 auto 20px auto;
        
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="file"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<body>

   

    <div class="profile-container">

        <?php if (!empty($user['profile_image'])): ?>

            <h3>Profile Image</h3>
            <img   class="profile-image" src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image" style="max-width: 200px; max-height: 200px;">
            <br><br>
        <?php endif; ?>
    </class=>

  

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required><br><br>

            <label for="email">Email ID:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br><br>





            
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" id="profile_image"><br><br>


          
            
            <button type="submit">Update Profile</button>
        </form>



</body>

</html>