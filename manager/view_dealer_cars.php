<?php
session_start();
include "config.php";

// Check if the user is logged in as a Bank Manager
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get the dealer ID from the query parameters
$dealer_id = $_GET['dealer_id'] ?? null;

// Check if the dealer ID is provided
if ($dealer_id === null) {
    echo "Dealer ID is required.";
    exit();
}

// Fetch all cars added by the specific Car Dealer
$cars_query = "SELECT * FROM cars WHERE dealer_id = '$dealer_id'";
$cars_result = mysqli_query($conn, $cars_query);

// Handle form submission for adding or updating car details
if (isset($_POST['save_details'])) {
    $car_id = $_POST['car_id'];
    $processing_charges = $_POST['processing_charges'];
    $estimated_tax = $_POST['estimated_tax'];
    $income_estimation_charges = $_POST['income_estimation_charges'];
    $documentation_charges = $_POST['documentation_charges'];
    
    // Check if details already exist for this car
    $details_query = "SELECT * FROM car_details WHERE car_id = '$car_id'";
    $details_result = mysqli_query($conn, $details_query);

    if (mysqli_num_rows($details_result) > 0) {
        // Update existing details
        $update_query = "UPDATE car_details 
                         SET processing_charges = '$processing_charges',
                             estimated_tax = '$estimated_tax',
                             income_estimation_charges = '$income_estimation_charges',
                             documentation_charges = '$documentation_charges'
                         WHERE car_id = '$car_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $message = "Car details updated successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new details
        $insert_query = "INSERT INTO car_details (car_id, processing_charges, estimated_tax, income_estimation_charges, documentation_charges) 
                         VALUES ('$car_id', '$processing_charges', '$estimated_tax', '$income_estimation_charges', '$documentation_charges')";
        
        if (mysqli_query($conn, $insert_query)) {
            $message = "Car details added successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch car details if a specific car is selected
$selected_car_details = null;
if (isset($_GET['car_id']) && !empty($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $selected_car_query = "SELECT * FROM car_details WHERE car_id = '$car_id'";
    $selected_car_result = mysqli_query($conn, $selected_car_query);
    $selected_car_details = mysqli_fetch_assoc($selected_car_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Dealer's Cars</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Cars Added by Dealer</h2>

    <!-- Display success/error message -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Form to select a car and manage details -->
    <form action="view_dealer_cars.php" method="GET">
        <input type="hidden" name="dealer_id" value="<?php echo htmlspecialchars($dealer_id); ?>">
        <label for="car_id">Select Car:</label>
        <select name="car_id" id="car_id" onchange="this.form.submit()">
            <option value="">-- Select a Car --</option>
            <?php while ($car = mysqli_fetch_assoc($cars_result)) { ?>
                <option value="<?php echo $car['id']; ?>" <?php echo (isset($selected_car_details) && $selected_car_details['car_id'] == $car['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($car['car_name']); ?>
                </option>
            <?php } ?>
        </select>
    </form>

    <!-- Form to add or update car details -->
    <?php if (isset($selected_car_details)) { ?>
        <h3>Manage Car Details</h3>
        <form action="view_dealer_cars.php?dealer_id=<?php echo urlencode($dealer_id); ?>" method="POST">
            <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($selected_car_details['car_id']); ?>">
            
            <label for="processing_charges">Processing Charges:</label>
            <input type="number" name="processing_charges" step="0.01" value="<?php echo htmlspecialchars($selected_car_details['processing_charges']); ?>" required>
            
            <label for="estimated_tax">Estimated Tax:</label>
            <input type="number" name="estimated_tax" step="0.01" value="<?php echo htmlspecialchars($selected_car_details['estimated_tax']); ?>" required>
            
            <label for="income_estimation_charges">Income Estimation Charges:</label>
            <input type="number" name="income_estimation_charges" step="0.01" value="<?php echo htmlspecialchars($selected_car_details['income_estimation_charges']); ?>" required>
            
            <label for="documentation_charges">Documentation Charges:</label>
            <input type="number" name="documentation_charges" step="0.01" value="<?php echo htmlspecialchars($selected_car_details['documentation_charges']); ?>" required>
            
            <button type="submit" name="save_details">Save Details</button>
        </form>
    <?php } else { ?>
        <p>Please select a car to manage its details.</p>
    <?php } ?>

    <p><a href="bank_manager_dashboard.php">Back to Dashboard</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>
