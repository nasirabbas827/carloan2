<?php
session_start();
include "config.php";

// Check if the user is logged in as a Car Dealer
if (!isset($_SESSION['dealer_logged_in']) || $_SESSION['dealer_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get the dealer ID and car ID from session and query parameters
$dealer_id = $_SESSION['dealer_id'];
$car_id = $_GET['id'];

// Handle form submission for editing a car
if (isset($_POST['update_car'])) {
    $car_company = $_POST['car_company'];
    $car_name = $_POST['car_name'];
    $car_price = $_POST['car_price'];
    
    // Update the car details in the database
    $update_query = "UPDATE cars SET car_company = '$car_company', car_name = '$car_name', car_price = '$car_price' WHERE id = '$car_id' AND dealer_id = '$dealer_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "Car updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch car details for editing
$query = "SELECT * FROM cars WHERE id = '$car_id' AND dealer_id = '$dealer_id'";
$result = mysqli_query($conn, $query);
$car = mysqli_fetch_assoc($result);

if (!$car) {
    die("Car not found or you do not have permission to edit it.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Edit Car</h2>

    <!-- Display success/error message -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Form for editing the car -->
    <form action="edit_car.php?id=<?php echo $car_id; ?>" method="POST">
        <label for="car_company">Car Company:</label>
        <input type="text" name="car_company" value="<?php echo $car['car_company']; ?>" required>
        
        <label for="car_name">Car Name:</label>
        <input type="text" name="car_name" value="<?php echo $car['car_name']; ?>" required>
        
        <label for="car_price">Car Price:</label>
        <input type="number" name="car_price" value="<?php echo $car['car_price']; ?>" step="0.01" required>
        
        <button type="submit" name="update_car">Update Car</button>
    </form>

    <p><a href="view_cars.php">Go back to View Cars</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>
