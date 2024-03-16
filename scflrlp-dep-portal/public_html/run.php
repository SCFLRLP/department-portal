<?php
// Include database connection
include("include/db.php");

// Fetch all users from the database
$sql = "SELECT id, password FROM users";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Loop through each user
    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['id'];
        $currentPassword = $row['password'];

        // Check if the password needs to be re-hashed
        if (!password_needs_rehash($currentPassword, PASSWORD_DEFAULT)) {
            continue; // Password is already hashed using current algorithm, skip to the next user
        }

        // Hash the password
        $hashedPassword = password_hash($currentPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updateSql = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
        if (mysqli_query($conn, $updateSql)) {
            echo "Password for user with ID $userId has been hashed successfully.<br>";
        } else {
            echo "Error updating password for user with ID $userId: " . mysqli_error($conn) . "<br>";
        }
    }
} else {
    echo "No users found in the database.";
}

mysqli_close($conn);
?>
