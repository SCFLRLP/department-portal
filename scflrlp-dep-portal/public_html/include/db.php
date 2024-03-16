<?php
// Database connection
$host = "DATABASEHOST"; // Change this to your database host
$user = "DATABASEUSERNAME"; // Change this to your database username
$password = "DATABASEPASSWORD"; // Change this to your database password
$database = "DATABASENAME"; // Change this to your database name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
