<?php
session_start();
include('config.php');

// Fetch car companies for the dropdown
$query_companies = "SELECT DISTINCT car_company FROM cars";
$result_companies = mysqli_query($conn, $query_companies);

// Fetch car names based on selected company
$car_names = [];
if (isset($_POST['car_company'])) {
    $car_company = $_POST['car_company'];
    $query_names = "SELECT DISTINCT car_name FROM cars WHERE car_company = '$car_company'";
    $result_names = mysqli_query($conn, $query_names);
    while ($row = mysqli_fetch_assoc($result_names)) {
        $car_names[] = $row['car_name'];
    }
}

// Handle form submission for searching cars
$search_result = [];
if (isset($_POST['search'])) {
    $car_company = $_POST['car_company'];
    $car_name = $_POST['car_name'];

    // Build the query based on the selected filters
    $query_search = "SELECT * FROM cars WHERE 1=1";
    if (!empty($car_company)) {
        $query_search .= " AND car_company = '$car_company'";
    }
    if (!empty($car_name)) {
        $query_search .= " AND car_name = '$car_name'";
    }
    
    $result_search = mysqli_query($conn, $query_search);
    if (mysqli_num_rows($result_search) > 0) {
        while ($row = mysqli_fetch_assoc($result_search)) {
            $search_result[] = $row;
        }
    }
}

// Handle form submission for editing car details
if (isset($_POST['update'])) {
    $car_id = $_POST['car_id'];
    $installment_years = $_POST['installment_years'];
    $advance_deposit = $_POST['advance_deposit'];
    $car_delivery = $_POST['car_delivery'];

    $query_update = "UPDATE cars SET installment_years = '$installment_years', advance_deposit = '$advance_deposit', car_delivery = '$car_delivery' WHERE id = '$car_id'";
    $update_result = mysqli_query($conn, $query_update);
    $update_message = $update_result ? "Car details updated successfully!" : "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Loan Management System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .jumbotron {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/img.jpg');
            background-size: cover;
            text-align: center;
            padding: 100px;
            margin-bottom: 0;
            height: 300px;
        }

        .jumbotron h1 {
            color: whitesmoke;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .jumbotron p {
            color: whitesmoke;
            font-size: 1.5em;
        }

        section {
            margin: 20px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 15px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="jumbotron">
        <h1>Welcome To Online Car Loan Management System</h1>
    </div>

    <section>
        <h2>Search Cars</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="car_company">Car Company:</label>
                <select name="car_company" id="car_company" onchange="this.form.submit()">
                    <option value="">Select Company</option>
                    <?php while ($company = mysqli_fetch_assoc($result_companies)) { ?>
                        <option value="<?php echo htmlspecialchars($company['car_company']); ?>" <?php echo isset($_POST['car_company']) && $_POST['car_company'] == $company['car_company'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($company['car_company']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="car_name">Car Name:</label>
                <select name="car_name" id="car_name">
                    <option value="">Select Car Name</option>
                    <?php foreach ($car_names as $car_name) { ?>
                        <option value="<?php echo htmlspecialchars($car_name); ?>" <?php echo isset($_POST['car_name']) && $_POST['car_name'] == $car_name ? 'selected' : ''; ?>><?php echo htmlspecialchars($car_name); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="search">Search</button>
            </div>
        </form>

        <?php if (!empty($search_result)) { ?>
            <h3>Search Results:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Car Company</th>
                        <th>Car Name</th>
                        <th>Car Price</th>
                        <th>Dealer ID</th>
                        <th>Installment Years</th>
                        <th>Advance Deposit</th>
                        <th>Car Delivery (Months)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_result as $car) { ?>
                        <tr>
                            <form action="#" method="POST">
                                <td><?php echo htmlspecialchars($car['car_company']); ?></td>
                                <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                                <td><?php echo htmlspecialchars($car['car_price']); ?></td>
                                <td><?php echo htmlspecialchars($car['dealer_id']); ?></td>
                                <td><input type="number" name="installment_years" value="<?php echo htmlspecialchars($car['installment_years']); ?>" step="1" min="1"></td>
                                <td><input type="number" name="advance_deposit" value="<?php echo htmlspecialchars($car['advance_deposit']); ?>" step="0.01" min="0"></td>
                                <td><input type="number" name="car_delivery" value="<?php echo htmlspecialchars($car['car_delivery']); ?>" step="1" min="0"></td>
                                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['id']); ?>">
                                <td><button type="submit" name="update">Update</button></td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </section>

    <section>
        <h2>Contact Us</h2>
        <p>If you have any questions or queries, please feel free to contact us by filling out the form below.</p>

        <form action="#" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Car Loan Management System. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
mysqli_close($conn);
?>
