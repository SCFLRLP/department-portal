<?php
session_start();
include("../../include/db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "User is not logged in.";
    exit();
}

// Check if shift is already started
if (isset($_SESSION['shift_started']) && $_SESSION['shift_started'] === true) {
    echo "Shift is already started.";
    exit();
}

// Record start time of shift
$start_time = date("Y-m-d H:i:s");
$_SESSION['shift_started'] = true;

// Insert shift start time into database
$username = $_SESSION['username'];
$sql = "INSERT INTO shifts (username, start_time) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $start_time);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "Shift started at: " . $start_time;
?>
