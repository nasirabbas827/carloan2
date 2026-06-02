<?php
session_start();
include "config.php";

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Remember this is plain text in your case

    // Fetch user data based on the provided username and password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = "YOUR_OWN_API_KEY"";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // User exists, now check their usertype and assign session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['usertype'] = $user['usertype'];

        if ($user['usertype'] === 'BankManager') {
            $_SESSION['manager_logged_in'] = true;
            $_SESSION['manager_id'] = $user['user_id'];
            header("Location: manager/bank_manager_dashboard.php");
        } elseif ($user['usertype'] === 'CarDealer') {
            $_SESSION['dealer_logged_in'] = true;
            $_SESSION['dealer_id'] = $user['user_id'];
            header("Location: dealer/car_dealer_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <h2>User Login</h2>

    <!-- Display error message if login fails -->
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <a href="index.php">Go back to Home</a>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
