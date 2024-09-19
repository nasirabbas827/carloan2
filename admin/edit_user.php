<?php
session_start();
include "config.php";

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch the user's current details based on the user_id
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}

// Handle updating user details
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text password
    $usertype = $_POST['usertype'];
    $email = $_POST['email'];
    $branch_name = $_POST['branch_name'];
    
    $query = "UPDATE users SET username = '$username', password = '$password', usertype = '$usertype', email = '$email', branch_name = '$branch_name' WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        $message = "User updated successfully!";
        header("Location: admin_dashboard.php"); // Redirect back to dashboard
        exit();
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
    <title>Edit User</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Edit User</h2>

    <!-- Display the user's current details in the form -->
    <form action="edit_user.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
        <label for="password">Password:</label>
        <input type="text" name="password" value="<?php echo $user['password']; ?>" required> <!-- Plain text password -->
        <label for="usertype">User Type:</label>
        <select name="usertype" required>
            <option value="BankManager" <?php if ($user['usertype'] == 'BankManager') echo 'selected'; ?>>Bank Manager</option>
            <option value="CarDealer" <?php if ($user['usertype'] == 'CarDealer') echo 'selected'; ?>>Car Dealer</option>
        </select>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <label for="branch_name">Branch Name:</label>
        <input type="text" name="branch_name" value="<?php echo $user['branch_name']; ?>" required>
        <button type="submit" name="edit_user">Update User</button>
    </form>

    <a href="admin_dashboard.php">Go back to Dashboard</a>
</body>
</html>

<?php
mysqli_close($conn);
?>
