<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $message = $_POST['message'];

    // Insert the message into the database
    $mysqli = new mysqli('localhost', 'team21', 'password', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "INSERT INTO chat_messages (username, message) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $message);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
