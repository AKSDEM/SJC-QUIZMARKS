<?php
$servername = "localhost";
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default XAMPP MySQL password (empty)
$database = "quiz_system"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
