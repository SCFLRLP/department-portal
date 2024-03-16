<?php
session_start();
include("include/db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /login");
    exit();
}

// Get current user's department
$username = $_SESSION['username'];
$sql = "SELECT department FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $department);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Fetch applications for the current department
$applications = [];
if ($department) {
    $table_name = strtolower(str_replace(' ', '_', $department)); // Adjust department name to match table name
    $sql = "SELECT * FROM `$table_name`"; // Use the adjusted table name
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roleplay Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Add custom styles here */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Roleplay Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Welcome to the Roleplay Dashboard, <?php echo $_SESSION['username']; ?></h2>
        <p>Applications for <?php echo $department; ?>:</p>
        <ul>
            <?php foreach ($applications as $app) { ?>
                <li><?php echo $app['app_name']; ?> - <a href="<?php echo $app['app_url']; ?>" target="_blank">Visit</a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
