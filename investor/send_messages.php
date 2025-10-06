<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Unauthorized");
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_POST['receiver_id']);
$message = trim($_POST['message']);

if ($message === '') exit;

// Ensure both users are in each other's contact list
$contact = $conn->prepare("INSERT IGNORE INTO chat_contacts (user_id, contact_id) VALUES (?, ?)");
$contact->bind_param("ii", $sender_id, $receiver_id);
$contact->execute();
$contact->bind_param("ii", $receiver_id, $sender_id);
$contact->execute();
$contact->close();

// Insert message
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();
$stmt->close();

// Update last message timestamps
$update = $conn->prepare("UPDATE chat_contacts SET last_message_time = NOW() WHERE user_id = ? AND contact_id = ?");
$update->bind_param("ii", $sender_id, $receiver_id);
$update->execute();
$update->bind_param("ii", $receiver_id, $sender_id);
$update->execute();
$update->close();

echo "sent";
?>
