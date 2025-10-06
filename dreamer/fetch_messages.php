<?php
session_start();
include("../db.php");

header("Content-Type: application/json"); // Always return JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$current_user = $_SESSION['user_id'];
$chat_user_id = intval($_GET['user_id'] ?? 0); // match your JS param name

if ($chat_user_id <= 0) {
    echo json_encode(["messages" => "<p style='color:gray;'>No contact selected</p>"]);
    exit;
}

// Fetch messages
$stmt = $conn->prepare("
    SELECT sender_id, receiver_id, message, created_at
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $current_user, $chat_user_id, $chat_user_id, $current_user);
$stmt->execute();
$result = $stmt->get_result();

$messages_html = '';
while ($msg = $result->fetch_assoc()) {
    $class = $msg['sender_id'] == $current_user ? 'sent' : 'received';
    $messages_html .= '<div class="message ' . $class . '">'
                    . htmlspecialchars($msg['message'])
                    . '<br><small style="font-size:10px; color:#8ab4f8;">'
                    . htmlspecialchars(date("H:i", strtotime($msg['created_at'])))
                    . '</small></div>';
}
$stmt->close();

// Mark as read
$mark = $conn->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ? AND is_read = 0");
$mark->bind_param("ii", $current_user, $chat_user_id);
$mark->execute();
$mark->close();

// Return valid JSON
echo json_encode(["messages" => $messages_html]);
?>
