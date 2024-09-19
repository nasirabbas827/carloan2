<?php
session_start();
include 'config.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Handle form submission for updating car details
if (isset($_POST['update_car'])) {
    $car_id = $_POST['car_id'];
    $car_company = $_POST['car_company'];
    $car_name = $_POST['car_name'];
    $car_price = $_POST['car_price'];
    $installment_years = $_POST['installment_years'];
    $advance_deposit = $_POST['advance_deposit'];
    $car_delivery = $_POST['car_delivery'];

    $update_query = "UPDATE cars SET 
        car_company = '$car_company',
        car_name = '$car_name',
        car_price = '$car_price',
        installment_years = '$installment_years',
        advance_deposit = '$advance_deposit',
        car_delivery = '$car_delivery'
        WHERE id = '$car_id'";

    if (mysqli_query($conn, $update_query)) {
        $message = "Car details updated successfully!";
    } else {
        $message = "Error updating car details: " . mysqli_error($conn);
    }
}

// Fetch all cars
$query_cars = "SELECT * FROM cars";
$result_cars = mysqli_query($conn, $query_cars);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View and Edit Cars</title>
    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body>
    <?php include('navbar.php'); ?>
    <h2>Admin - View and Edit Cars</h2>

    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>Car Company</th>
                <th>Car Name</th>
                <th>Car Price</th>
                <th>Installment Years</th>
                <th>Advance Deposit</th>
                <th>Car Delivery (Months)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($car = mysqli_fetch_assoc($result_cars)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($car['car_company']); ?></td>
                    <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                    <td><?php echo htmlspecialchars($car['car_price']); ?></td>
                    <td><?php echo htmlspecialchars($car['installment_years']); ?></td>
                    <td><?php echo htmlspecialchars($car['advance_deposit']); ?></td>
                    <td><?php echo htmlspecialchars($car['car_delivery']); ?></td>
                    <td>
                        <button onclick="openEditForm(<?php echo $car['id']; ?>, '<?php echo htmlspecialchars($car['car_company']); ?>', '<?php echo htmlspecialchars($car['car_name']); ?>', '<?php echo htmlspecialchars($car['car_price']); ?>', <?php echo $car['installment_years']; ?>, <?php echo $car['advance_deposit']; ?>, <?php echo $car['car_delivery']; ?>)">Edit</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div id="editForm" style="display:none;">
        <h3>Edit Car Details</h3>
        <form action="view_edit_cars.php" method="POST">
            <input type="hidden" name="car_id" id="car_id">

            <div class="form-group">
                <label for="car_company">Car Company:</label>
                <input type="text" name="car_company" id="car_company" required>
            </div>

            <div class="form-group">
                <label for="car_name">Car Name:</label>
                <input type="text" name="car_name" id="car_name" required>
            </div>

            <div class="form-group">
                <label for="car_price">Car Price:</label>
                <input type="number" name="car_price" id="car_price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="installment_years">Installment Years:</label>
                <input type="number" name="installment_years" id="installment_years" required>
            </div>

            <div class="form-group">
                <label for="advance_deposit">Advance Deposit:</label>
                <input type="number" name="advance_deposit" id="advance_deposit" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="car_delivery">Car Delivery (Months):</label>
                <input type="number" name="car_delivery" id="car_delivery" required>
            </div>

            <button type="submit" name="update_car">Update Car</button>
            <button type="button" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <script>
        function openEditForm(id, company, name, price, years, deposit, delivery) {
            document.getElementById('car_id').value = id;
            document.getElementById('car_company').value = company;
            document.getElementById('car_name').value = name;
            document.getElementById('car_price').value = price;
            document.getElementById('installment_years').value = years;
            document.getElementById('advance_deposit').value = deposit;
            document.getElementById('car_delivery').value = delivery;
            document.getElementById('editForm').style.display = 'block';
        }

        function closeEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>

    
</body>
</html>

<?php
mysqli_close($conn);
?>
