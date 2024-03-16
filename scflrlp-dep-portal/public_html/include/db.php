<?php
// Database connection
$host = "localhost"; // Change this to your database host
$user = "id21983774_scflrlp"; // Change this to your database username
$password = "agrernbETAte34!"; // Change this to your database password
$database = "id21983774_scflrlp"; // Change this to your database name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
