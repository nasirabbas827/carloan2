<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "kainat_carloan");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>