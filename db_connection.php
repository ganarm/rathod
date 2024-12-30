<?php
$servername = "localhost";
$username = "root"; // Adjust accordingly
$password = ""; // Adjust accordingly
$dbname = "try"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
