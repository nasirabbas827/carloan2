<?php
session_start();
include "config.php";

// Check if the user is logged in as a Car Dealer
if (!isset($_SESSION['dealer_logged_in']) || $_SESSION['dealer_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Dealer Dashboard</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
                p,h3,li{
            margin-left:90px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Welcome, Car Dealer <?php echo $_SESSION['username']; ?>!</h2>

    <!-- Options for Car Dealer -->
    <h3>Car Dealer Actions:</h3>
    <ul>
        <li><a href="view_cars.php">View Available Cars</a></li>
        <li><a href="add_new_cars.php">Add New Cars</a></li>
    </ul>

</body>
</html>
<?php
mysqli_close($conn);
?>
