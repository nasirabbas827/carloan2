<?php
include "config.php";

// Get car_id from query parameter
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

if ($car_id > 0) {
    // Query to fetch car charges
    $query = "SELECT * FROM car_charges WHERE car_id = '$car_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $charges = mysqli_fetch_assoc($result);
        echo json_encode($charges);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}

mysqli_close($conn);
?>
