<?php
// Start session and include the config file
session_start();
include "config.php";

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Handle add user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text password
    $usertype = $_POST['usertype'];
    $email = $_POST['email'];
    $branch_name = $_POST['branch_name'];
    
    $query = "INSERT INTO users (username, password, usertype, email, branch_name) VALUES ('$username', '$password', '$usertype', '$email', '$branch_name')";
    
    if (mysqli_query($conn, $query)) {
        $message = "User added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Handle delete user
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];
    $query = "DELETE FROM users WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Handle edit user
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text password
    $usertype = $_POST['usertype'];
    $email = $_POST['email'];
    $branch_name = $_POST['branch_name'];
    
    $query = "UPDATE users SET username = '$username', password = "YOUR_OWN_API_KEY", usertype = '$usertype', email = '$email', branch_name = '$branch_name' WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        $message = "User updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Users</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Welcome to Admin Dashboard</h2>
    <h3>Manage Users</h3>

    <!-- Display success/error message -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Add User Form -->
    <h3>Add New User</h3>
    <form action="admin_dashboard.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="text" name="password" required>
        <label for="usertype">User Type:</label>
        <select name="usertype" required>
            <option value="BankManager">Bank Manager</option>
            <option value="CarDealer">Car Dealer</option>
        </select>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <label for="branch_name">Branch Name:</label>
        <input type="text" name="branch_name" required>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <!-- Display Users -->
    <h3>All Users</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password (Plain Text)</th>
                <th>User Type</th>
                <th>Email</th>
                <th>Branch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM users";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['user_id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['password']}</td>
                    <td>{$row['usertype']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['branch_name']}</td>
                    <td>
                        <a href='edit_user.php?user_id={$row['user_id']}'>Edit</a> |
                        <a href='admin_dashboard.php?delete_id={$row['user_id']}'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
