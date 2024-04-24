<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: super_user.php");
    exit;
}

// Function to fetch the list of registered users from the database
function getRegisteredUsers() {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT username, password, email, phone, name, post_title, post_content, is_enabled FROM users";
    $result = $mysqli->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $mysqli->close();
    return $users;
}

// Function to disable a user (update is_enabled to 0)
function disableUser($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "UPDATE users SET is_enabled = 0 WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return $result;
}

// Function to enable a user (update is_enabled to 1)
function enableUser($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "UPDATE users SET is_enabled = 1 WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return $result;
}

// Check if the form is submitted to disable or enable a user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["disable_user"])) {
        $username = $_POST["disable_user"];
        disableUser($username);
    } elseif (isset($_POST["enable_user"])) {
        $username = $_POST["enable_user"];
        enableUser($username);
    }
}

// Fetch the list of registered users
$registeredUsers = getRegisteredUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        /* Your CSS styles here */
        /* Container styles */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Table styles */
table {
  width: 100%;
  border-collapse: collapse;
  font-family: Arial, sans-serif;
}

th, td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
}

/* Button styles */
.btn-disable, .btn-enable {
  display: inline-block;
  padding: 6px 12px;
  font-size: 14px;
  font-weight: bold;
  text-align: center;
  text-decoration: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-disable {
  background-color: #dc3545;
  color: #fff;
  border: none;
}

.btn-enable {
  background-color: #28a745;
  color: #fff;
  border: none;
}

.btn-disable:hover, .btn-enable:hover {
  opacity: 0.8;
}
.button {
  display: inline-block;
  padding: 12px 24px;
  margin: 10px;
  background-color: #3498db;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  text-decoration: none;
  font-size: 16px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard - Registered Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Name</th>
                    <th>Post Title</th>
                    <th>Post Content</th>
                    <th>Is Enabled</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registeredUsers as $user) : ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['post_title']; ?></td>
                        <td><?php echo $user['post_content']; ?></td>
                        <td><?php echo ($user['is_enabled'] == 1) ? 'Enabled' : 'Disabled'; ?></td>
                        <td>
                            <?php if ($user['is_enabled'] == 1) : ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="disable_user" value="<?php echo $user['username']; ?>">
                                    <button type="submit" class="btn-disable">Disable</button>
                                </form>
                            <?php else : ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="enable_user" value="<?php echo $user['username']; ?>">
                                    <button type="submit" class="btn-enable">Enable</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a class="button" href="index.php">Go To Homepage</a>
    </div>
</body>
</html>