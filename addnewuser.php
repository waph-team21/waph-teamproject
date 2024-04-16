<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$hostname = 'localhost';
$dbUsername = 'team21';
$dbPassword = 'Pa$$w0rd';
$dbName = 'waph_team21';

// Retrieve user input
$newUsername = $_POST["username"];
$newPassword = $_POST["password"];
$newEmail = $_POST["email"];

// Validate user input
if(empty($newUsername) || empty($newPassword) || empty($newEmail)) {
    echo "Please provide username, password, and email.";
} else {
    // Attempt to add new user
    $dbConnection = new mysqli($hostname, $dbUsername, $dbPassword, $dbName);
    if ($dbConnection->connect_errno) {
        printf("Database connection failed: %s\n", $dbConnection->connect_error);
    } else {
        // Prepare SQL statement for user registration
        $insertQuery = "INSERT INTO users (username, password, email) VALUES (?, md5(?), ?);";
        $statement = $dbConnection->prepare($insertQuery);
        $statement->bind_param("sss", $newUsername, $newPassword, $newEmail);

        // Execute the SQL statement
        if ($statement->execute()) {
            // Registration successful
            echo "<script>alert('Registration successful!'); window.location='form.php';</script>";
            exit(); // Stop further execution
        } else {
            echo "Registration failed. Please try again later.";
        }

        // Close database connection
        $dbConnection->close();
    }
}
?>