<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect post variables
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the database
    $server = "localhost";
    $username = "root";
    $password_db = ""; // Renamed to avoid conflict with the password input
    $dbname = "registration_db";

    $con = new mysqli($server, $username, $password_db, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if the email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['user'] = $row;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    // Close the connection
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            background-color: #DDDDDD;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #FFFFFF;
            overflow: hidden;
            padding: 30px;

        }

        .navbar a {
            float: right;
            display: block;
            color: #FF6347;
            text-align: center;
            font-size: larger;

            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .login-container {


            background-color: white;
            padding: 30px;
            border-radius: 5px;

            width: 477px;
            height: 324px;
            margin: 20px auto;
        }



        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 30px;
            border: 1px solid #ccc;
            flex: 1;

        }

        .login-container button {
            margin: auto;
            width: auto;
            padding: 10px 50px;
            background-color: #FF6F61;
            border: none;
            color: white;

            cursor: pointer;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #FF5A4A;
        }


        form {

            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 60px;
        }


        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px;
                text-align: center;
            }

            .navbar a {
                float: none;
                display: inline-block;
                margin-top: 10px;
            }

            .login-container {
                padding: 20px;
            }

            .login-container input {
                margin-bottom: 15px;
            }

            .login-container button {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                width: 100%;
                padding: 20px;
            }

            .login-container {
                padding: 50px;
                width: 60%;
            }

            form {
                margin: 10px 5px;
            }

            .login-container input {
                padding: 8px;
            }

            .login-container button {
                padding: 8px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="register.php">Register</a>
    </div>
    <div class="login-container">

        <form action="login.php" method="POST">

            <input type="email" name="email" id="email" required placeholder="Email Id"><br>


            <input type="password" name="password" id="password" placeholder="Password" required><br>

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>

</html>