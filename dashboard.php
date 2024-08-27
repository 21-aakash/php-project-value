<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$server = "localhost";
$username = "root";
$password_db = ""; // Renamed to avoid conflict with user password variable
$dbname = "registration_db";

$con = new mysqli($server, $username, $password_db, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = $con->query($sql);

// Get logged-in user's sno
$loggedInUserSno = $_SESSION['user']['sno'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #DDDDDD;
            margin: 50px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .icon {
            text-decoration: none;
            font-size: 18px;
            color: #000;
            padding: 5px;
        }

        .icon.edit {
            color: #28a745;
        }

        .icon.delete {
            color: #dc3545;
        }

        .icon.view {
            color: #007bff;
        }

        /* Style for the logout and my profile buttons */
        .button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 50px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .profile-image {
            max-width: 50px;
            max-height: 50px;

        }


        .nav {

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

           

            .nav h2 {
                margin-bottom: 10px;
            }

            .button {
                font-size: 14px;
                padding: 8px 15px;
            }
            .btn{
               

               display: flex;
              
               

           }
            table,
            th,
            td {
                font-size: 14px;
            }

            .profile-image {
                max-width: 40px;
                max-height: 40px;
            }
        }

        @media (max-width: 480px) {
            .nav {
                align-items: center;
            }

            table,
            th,
            td {
                font-size: 12px;
                padding: 3px;
            }

            .button {
                font-size: 8px;
                padding: 6px 10px;
            }

            .btn{
               

                display: flex;
               
                

            }

            .profile-image {
                max-width: 30px;
                max-height: 30px;
            }
        }
    </style>

</head>

<body>
    <div class="nav">

        <h2>Registered Users</h2>

        <div class="btn">
            <a href="profile.php" class="button">My Profile</a>
            <a href="logout.php" class="button">Logout</a>
        </div>


    </div>


    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Profile Image</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email ID</th>
                <th>Mobile Number</th>
                <th>Date of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1; // Initialize serial number
            while ($row = $result->fetch_assoc()):
                $userId = htmlspecialchars($row['sno'] ?? ''); // Provide a default empty string if id is null
                $profileImage = !empty($row['profile_image']) ? htmlspecialchars($row['profile_image']) : 'path/to/default/image.png'; // Use a default image if profile image is missing
            ?>
                <tr>
                    <td><?= $sno++; ?></td>
                    <td><img src="<?= $profileImage; ?>" alt="Profile Image" class="profile-image"></td>
                    <td><?= htmlspecialchars($row['firstname']); ?></td>
                    <td><?= htmlspecialchars($row['lastname']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['dob']); ?></td>
                    <td>

                        <button class='icon edit' onclick="window.location.href='edit.php?sno=<?= htmlspecialchars($row['sno']) ?>'">✎</button>
                        <button class='icon delete' onclick="if(confirm('Are you sure you want to delete this user?')) { window.location.href='delete.php?sno=<?= htmlspecialchars($row['sno']) ?>'; }">🗑️</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>

<?php
$con->close();
?>