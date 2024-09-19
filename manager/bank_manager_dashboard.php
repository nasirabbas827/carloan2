<?php
session_start();
include "config.php";

// Check if the user is logged in as a Bank Manager
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch manager's information
$manager_id = $_SESSION['manager_id'];
$query_manager = "SELECT * FROM users WHERE user_id = '$manager_id' AND usertype = 'BankManager'";
$result_manager = mysqli_query($conn, $query_manager);
$manager = mysqli_fetch_assoc($result_manager);

// Fetch car dealers
$query_dealers = "SELECT * FROM users WHERE usertype = 'CarDealer'";
$result_dealers = mysqli_query($conn, $query_dealers);

// Handle form submission for adding/updating charges
if (isset($_POST['submit_charges'])) {
    $car_id = $_POST['car_id'];
    $processing_charges = $_POST['processing_charges'];
    $estimated_tax = $_POST['estimated_tax'];
    $income_estimation = $_POST['income_estimation'];
    $documentation_charges = $_POST['documentation_charges'];

    // Check if charges already exist for the selected car
    $query_check = "SELECT * FROM car_charges WHERE car_id = '$car_id'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Update existing charges
        $update_query = "UPDATE car_charges SET processing_charges = '$processing_charges', estimated_tax = '$estimated_tax', income_estimation = '$income_estimation', documentation_charges = '$documentation_charges' WHERE car_id = '$car_id'";
        $message = mysqli_query($conn, $update_query) ? "Charges updated successfully!" : "Error: " . mysqli_error($conn);
    } else {
        // Insert new charges
        $insert_query = "INSERT INTO car_charges (car_id, processing_charges, estimated_tax, income_estimation, documentation_charges) VALUES ('$car_id', '$processing_charges', '$estimated_tax', '$income_estimation', '$documentation_charges')";
        $message = mysqli_query($conn, $insert_query) ? "Charges added successfully!" : "Error: " . mysqli_error($conn);
    }
}

// Fetch all cars for dropdown
$query_cars = "SELECT * FROM cars";
$result_cars = mysqli_query($conn, $query_cars);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Manager Dashboard</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        p,h3{
            margin-left:90px;
        }
    </style>
    <script>
        function loadCharges(carId) {
            if (carId === "") {
                document.getElementById("processing_charges").value = "";
                document.getElementById("estimated_tax").value = "";
                document.getElementById("income_estimation").value = "";
                document.getElementById("documentation_charges").value = "";
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_car_charges.php?car_id=" + carId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data) {
                        document.getElementById("processing_charges").value = data.processing_charges;
                        document.getElementById("estimated_tax").value = data.estimated_tax;
                        document.getElementById("income_estimation").value = data.income_estimation;
                        document.getElementById("documentation_charges").value = data.documentation_charges;
                    } else {
                        document.getElementById("processing_charges").value = "";
                        document.getElementById("estimated_tax").value = "";
                        document.getElementById("income_estimation").value = "";
                        document.getElementById("documentation_charges").value = "";
                    }
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Welcome, Bank Manager <?php echo htmlspecialchars($manager['username']); ?>!</h2>

    <h3>Your Information:</h3>
    <p>Name: <?php echo htmlspecialchars($manager['username']); ?></p>
    <p>Bank Branch: <?php echo htmlspecialchars($manager['branch_name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($manager['email']); ?></p>

    <h3>Car Dealers:</h3>
    <ul>
        <?php while ($dealer = mysqli_fetch_assoc($result_dealers)) { ?>
            <li><?php echo htmlspecialchars($dealer['username']); ?> (Email: <?php echo htmlspecialchars($dealer['email']); ?>)</li>
        <?php } ?>
    </ul>

    <h3>Manage Car Charges:</h3>
    <!-- Form for adding/updating charges -->
    <form action="bank_manager_dashboard.php" method="POST">
        <label for="car_id">Select Car:</label>
        <select name="car_id" id="car_id" onchange="loadCharges(this.value)" required>
            <option value="">Select a car</option>
            <?php while ($car = mysqli_fetch_assoc($result_cars)) { ?>
                <option value="<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['car_company'] . ' ' . $car['car_name']); ?></option>
            <?php } ?>
        </select>

        <label for="processing_charges">Processing Charges:</label>
        <input type="number" id="processing_charges" name="processing_charges" step="0.01" required>
        
        <label for="estimated_tax">Estimated Tax:</label>
        <input type="number" id="estimated_tax" name="estimated_tax" step="0.01" required>
        
        <label for="income_estimation">User's Income Estimation:</label>
        <input type="number" id="income_estimation" name="income_estimation" step="0.01" required>
        
        <label for="documentation_charges">Documentation Charges:</label>
        <input type="number" id="documentation_charges" name="documentation_charges" step="0.01" required>
        
        <button type="submit" name="submit_charges">Submit</button>
    </form>

    <p><a href="bank_manager_dashboard.php">Refresh Page</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>
