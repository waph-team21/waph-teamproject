<?php
// Fetch recent chat messages from the database
$mysqli = new mysqli('localhost', 'team21', 'password', 'database_name');
if ($mysqli->connect_errno) {
    echo "Database connection failed: " . $mysqli->connect_error;
    exit;
}

$sql = "SELECT * FROM chat_messages ORDER BY created_at DESC LIMIT 50";
$result = $mysqli->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$mysqli->close();
