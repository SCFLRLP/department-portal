<?php
session_start();
include("../../include/db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "User is not logged in.";
    exit();
}

// Check if shift is not started
if (!isset($_SESSION['shift_started']) || $_SESSION['shift_started'] !== true) {
    echo "No active shift found.";
    exit();
}

// Record end time of shift
$end_time = date("Y-m-d H:i:s");
$_SESSION['shift_started'] = false;

// Update shift end time in the database
$username = $_SESSION['username'];
$sql = "UPDATE shifts SET end_time = ? WHERE username = ? AND end_time IS NULL";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $end_time, $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "Shift ended at: " . $end_time;
?>
