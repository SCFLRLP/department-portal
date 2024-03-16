<?php
session_start();

// Handle access code generation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = $_POST['department'];

    // Generate access code
    $accessCode = generateAccessCode($department);

    // Save access code to file
    $file = fopen("../codes.txt", "a");
    fwrite($file, "$department,$accessCode\n");
    fclose($file);

    $message = "Access code generated successfully: $accessCode";
}

// Function to generate access code
function generateAccessCode($department) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $accessCode = '';

    // Generate a random access code
    for ($i = 0; $i < 8; $i++) {
        $accessCode .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $accessCode;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Access Code</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Generate Access Code</h2>
        <!-- Access Code Generation Form -->
        <form method="post" action="">
            <div class="form-group">
                <label for="department">Select Department</label>
                <select class="form-control" id="department" name="department">
                    <option value="Florida Highway Patrol">Florida Highway Patrol</option>
                    <option value="Florida Highway Command">Florida Highway Command</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Code</button>
        </form>
        <?php if (isset($message)) { ?>
            <div class="alert alert-success mt-3" role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
