<?php
session_start();
include("../../include/db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /login");
    exit();
}

// Check if start shift form is submitted
if (isset($_POST['start_shift'])) {
    include("start_shift.php");
}

// Check if end shift form is submitted
if (isset($_POST['end_shift'])) {
    include("end_shift.php");
}

// Fetch previous shift data if available
$username = $_SESSION['username'];
$sql = "SELECT * FROM shifts WHERE username = ? ORDER BY start_time DESC LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$previous_shift = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Shift Manager</h2>
        <?php if (isset($_SESSION['shift_started'])): ?>
            <p>Shift is currently in progress.</p>
            <form id="endShiftForm" method="post">
                <button type="submit" name="end_shift" class="btn btn-danger">End Shift</button>
            </form>
        <?php else: ?>
            <p>No active shift found.</p>
            <form id="startShiftForm" method="post">
                <button type="submit" name="start_shift" class="btn btn-success">Start Shift</button>
            </form>
        <?php endif; ?>

        <div id="loading" class="mt-2" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <?php if (!empty($previous_shift)): ?>
            <h3>Previous Shift</h3>
            <p>Start Time: <?php echo $previous_shift['start_time']; ?></p>
            <p>End Time: <?php echo $previous_shift['end_time'] ?? "Shift not ended"; ?></p>
        <?php endif; ?>

        <a href="/" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Start Shift form submission
            $("#startShiftForm").submit(function(event) {
                event.preventDefault();
                $("#loading").show(); // Show loading indicator
                $.post("start_shift.php", function(data) {
                    alert(data); // Display response
                    $("#loading").hide(); // Hide loading indicator
                    if (data.includes("Shift started")) {
                        location.reload(); // Reload page only if shift started successfully
                    }
                });
            });

            // End Shift form submission
            $("#endShiftForm").submit(function(event) {
                event.preventDefault();
                $("#loading").show(); // Show loading indicator
                $.post("end_shift.php", function(data) {
                    alert(data); // Display response
                    $("#loading").hide(); // Hide loading indicator
                    if (data.includes("Shift ended")) {
                        location.reload(); // Reload page only if shift ended successfully
                    }
                });
            });
        });
    </script>
</body>
</html>
