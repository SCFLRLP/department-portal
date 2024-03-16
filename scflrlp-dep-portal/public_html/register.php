<?php
session_start();
include("include/db.php");

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $accessCode = $_POST['access_code'];

    // Validate access code
    $accessCodes = file("codes.txt", FILE_IGNORE_NEW_LINES);
    $department = null;
    foreach ($accessCodes as $line) {
        list($dep, $code) = explode(",", $line);
        if ($code == $accessCode) {
            $department = $dep;
            break;
        }
    }

    if ($department) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database with hashed password and department
        $sql = "INSERT INTO users (username, password, department) VALUES ('$username', '$hashedPassword', '$department')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $username;
            header("Location: /");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Invalid access code";
    }
}
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        #password-strength {
            display: none;
        }
        .list-group-item {
            border: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Register</h2>
        <!-- Registration Form -->
        <form id="registrationForm" method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                <div id="password-strength" class="mt-2">
                    <div class="list-group">
                        <div class="list-group-item" id="length">
                            <i class="fa fa-times"></i> Minimum 10 characters
                        </div>
                        <div class="list-group-item" id="uppercase">
                            <i class="fa fa-times"></i> At least one uppercase letter
                        </div>
                        <div class="list-group-item" id="lowercase">
                            <i class="fa fa-times"></i> At least one lowercase letter
                        </div>
                        <div class="list-group-item" id="number">
                            <i class="fa fa-times"></i> At least one number
                        </div>
                        <div class="list-group-item" id="special">
                            <i class="fa fa-times"></i> At least one special character
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="access_code">Access Code</label>
                <input type="text" class="form-control" id="access_code" name="access_code" placeholder="Enter access code">
            </div>
            <button type="submit" class="btn btn-primary" id="registerBtn" disabled>Register</button>
        </form>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#password').keyup(function() {
                var password = $(this).val();
                var length = password.length >= 10;
                var uppercase = /[A-Z]/.test(password);
                var lowercase = /[a-z]/.test(password);
                var number = /\d/.test(password);
                var special = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                
                $('#length').toggleClass('list-group-item-success', length);
                $('#uppercase').toggleClass('list-group-item-success', uppercase);
                $('#lowercase').toggleClass('list-group-item-success', lowercase);
                $('#number').toggleClass('list-group-item-success', number);
                $('#special').toggleClass('list-group-item-success', special);

                // Check if all criteria are met
                var isValid = length && uppercase && lowercase && number && special;
                $('#registerBtn').prop('disabled', !isValid);

                // Show/hide password strength checklist
                $('#password-strength').toggle(password.length > 0);
            });
        });
    </script>
</body>
</html>
