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

// Handle car deletion
if (isset($_GET['delete'])) {
    $car_id = $_GET['delete'];
    $delete_query = "DELETE FROM cars WHERE id = '$car_id' AND dealer_id = '$dealer_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        $message = "Car deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch cars added by the dealer
$query = "SELECT * FROM cars WHERE dealer_id = '$dealer_id'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cars</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>My Cars</h2>

    <!-- Display success/error message -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Table to display the cars -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Car Company</th>
                <th>Car Name</th>
                <th>Car Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($car = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $car['id']; ?></td>
                    <td><?php echo $car['car_company']; ?></td>
                    <td><?php echo $car['car_name']; ?></td>
                    <td><?php echo $car['car_price']; ?></td>
                    <td>
                        <a href="edit_car.php?id=<?php echo $car['id']; ?>">Edit</a> | 
                        <a href="view_cars.php?delete=<?php echo $car['id']; ?>" onclick="return confirm('Are you sure you want to delete this car?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <p><a href="car_dealer_dashboard.php">Go back to Dashboard</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>
