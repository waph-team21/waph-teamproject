<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: form.php");
    exit;
}

// Handle chat form submission
$messageSent = false; // Flag to track if a message was sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_username']) && isset($_POST['message'])) {
    // Process the chat message here, you can use the recipient username and message
    $sender = $_SESSION['username'];
    $recipientUsername = $_POST['recipient_username'];
    $message = $_POST['message'];
    saveMessageToDatabase($sender, $recipientUsername, $message);
    $messageSent = true; // Set flag to true after message is sent
}

// Fetch messages for the logged-in user
$loggedInUserMessages = getMessagesForUser($_SESSION['username']);

// Fetch users for the chat dropdown
$users = getUsersForChat($_SESSION['username']);

function saveMessageToDatabase($sender, $recipientUsername, $message) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "INSERT INTO messages (sender, recipient_username, message) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $sender, $recipientUsername, $message);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getMessagesForUser($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "SELECT * FROM messages WHERE recipient_username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $result->free_result();
    $mysqli->close();

    return $messages;
}

function getUsersForChat($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "SELECT username FROM users WHERE username != ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['username'];
    }
    $result->free_result();
    $mysqli->close();

    return $users;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Chat</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1, h2 {
            color: #333;
        }

        /* Form Styles */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #2196f3;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Messages Styles */
        #received-messages {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        #received-messages div {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f4f4f4;
            border-radius: 4px;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
            display: none; /* Initially hide alert */
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
    <h1>Chat</h1>
    <h2>Select a User to Start Chatting</h2>
    <form method="POST" action="">
        <label for="recipient">Select User:</label>
        <select id="recipient" name="recipient_username" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user; ?>"><?php echo $user; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea>
        <br><br>
        <button type="submit" id="send-message-btn">Send Message</button>
    </form>

    <!-- Alert for Message Sent -->
    <div class="alert" id="message-sent-alert">Message Sent!</div>

    <h2>Messages Received</h2>
    <div id="received-messages">
        <?php foreach ($loggedInUserMessages as $message): ?>
            <div><?php echo htmlentities($message['sender']) . ': ' . htmlentities($message['message']); ?></div>
        <?php endforeach; ?>
    </div>

    <!-- Go Back Button -->
    <a class="button" href="index.php">Go To Homepage</a>

    <script>
        // Function to go back to the previous page
        function goBack() {
            window.history.back();
        }

        // Show alert message after sending message
        <?php if ($messageSent): ?>
        document.getElementById('message-sent-alert').style.display = 'block';
        setTimeout(function () {
            document.getElementById('message-sent-alert').style.display = 'none';
        }, 3000); // Hide alert after 3 seconds
        <?php endif; ?>
    </script>
</body>
</html>
