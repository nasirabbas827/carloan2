<?php
session_start();
include "config.php";

// Check if the user is logged in as a Car Dealer
if (!isset($_SESSION['dealer_logged_in']) || $_SESSION['dealer_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get the dealer ID from the session
$dealer_id = $_SESSION['dealer_id'];

// Handle form submission for adding a new car
if (isset($_POST['add_car'])) {
    $car_company = $_POST['car_company'];
    $car_name = $_POST['car_name'];
    $car_price = $_POST['car_price'];
    
    // Insert the new car into the database
    $query = "INSERT INTO cars (car_company, car_name, car_price, dealer_id) VALUES ('$car_company', '$car_name', '$car_price', '$dealer_id')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Car added successfully!";
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
    <title>Add New Car</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Add New Car</h2>

    <!-- Display success/error message -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Form for adding a new car -->
    <form action="add_new_cars.php" method="POST">
        <label for="car_company">Car Company:</label>
        <input type="text" name="car_company" required>
        
        <label for="car_name">Car Name:</label>
        <input type="text" name="car_name" required>
        
        <label for="car_price">Car Price:</label>
        <input type="number" name="car_price" step="0.01" required>
        
        <button type="submit" name="add_car">Add Car</button>
    </form>

    <p><a href="car_dealer_dashboard.php">Go back to Dashboard</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>
