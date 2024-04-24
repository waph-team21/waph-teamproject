<?php
session_start();

// Check if the user is the super user (you can modify this as per your requirements)
$isSuperUser = $_SESSION['username'] === 'admin';

if (!$isSuperUser) {
    echo "Access denied. You must be a super user to access this page.";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $isEnabled = isset($_POST['is_enabled']) ? 1 : 0;

    // Update the user's status
    updateUserStatus($username, $isEnabled);
}

// Function to update the user's status
function updateUserStatus($username, $isEnabled) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "UPDATE users SET is_enabled = ? WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("is", $isEnabled, $username);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>Admin Panel</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="is_enabled">Enable Account:</label>
        <input type="checkbox" id="is_enabled" name="is_enabled" value="1">

        <button type="submit">Update Status</button>
    </form>
</body>
</html>