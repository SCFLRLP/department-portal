<?php
session_start();
include("../include/db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appName = $_POST['app_name'];
    $appURL = $_POST['app_url'];
    $department = $_POST['department'];

    // Insert application into the respective department's table
    $sql = "INSERT INTO $department (app_name, app_url) VALUES ('$appName', '$appURL')";
    if (mysqli_query($conn, $sql)) {
        $successMessage = "Application added successfully.";
    } else {
        $errorMessage = "Error adding application: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Application</h2>
        <?php if (isset($successMessage)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>
        <?php if (isset($errorMessage)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="app_name">Application Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name" placeholder="Enter application name" required>
            </div>
            <div class="form-group">
                <label for="app_url">Application URL</label>
                <input type="url" class="form-control" id="app_url" name="app_url" placeholder="Enter application URL" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="florida_highway_patrol">Florida Highway Patrol</option>
                    <option value="florida_highway_command">Florida Highway Command</option>
                    <!-- Add more options for other departments if needed -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
