<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect post variables
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['mobile'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    // Validate data (example)
    if (strlen($phone) < 10 || strlen($password) < 4) {
        echo "Invalid mobile number or password length.";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration_db";

    $con = new mysqli($server, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Insert the data into the database
    $sql = "INSERT INTO users (firstname, lastname, email, phone, password, dob) 
            VALUES ('$firstname', '$lastname', '$email', '$phone', '$hashed_password', '$dob')";

    if ($con->query($sql) === TRUE) {
        header("Location: login.php");
        exit(); // Always exit after a header redirect
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Navbar Styles */
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

        /* Form Container Styles */
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;

            width: 700px;
            max-width: 100%;
            margin: 20px auto;
            padding-bottom: 200px;

        }




        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
            width: 100%;

            flex: 1;
            /* Allow the input fields to grow and fill the available space */

        }

        .input-row input {
            width: 100%;
            padding: 10px;
            margin-bottom: 0;
            border: 1px solid #ccc;
            flex: 1;
            /* Allow the input fields to grow and fill the available space */
        }


        .input-row2 {

            width: 60%;
            margin: 0 auto;



        }


        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus {
            border-color: #888;
            outline: none;
        }

        button {
            display: inline-block;
            padding: 10px 50px;
            margin: 0 auto;
            background-color: #FF6347;
            color: #4B4544;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: auto;

        }

        button:hover {
            background-color: #ff5a47;
        }


        @media (max-width: 768px) {

            .form-container {
                padding: 20px;
                width: 80%;
                margin: 1rem 4rem;
                /* Slightly reduce the form width for better appearance on mobile */
            }

            .navbar {

                width: 100%;
            }

            button {
                padding: 10px 20px;
                font-size: 14px;
                /* Reduce button size on smaller screens */
            }

        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="login.php">Sign In</a>
    </div>

    <div class="form-container">

        <form action="register.php" method="POST">
            <div class="input-row">
                <input type="text" name="firstname" placeholder="Firstname" required>
                <input type="text" name="lastname" placeholder="LastName" required>
            </div>
            <div class="input-row2">

                <input type="text" name="mobile" placeholder="MobileNumber" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <input type="date" name="dob" placeholder="DOB" required>


            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>