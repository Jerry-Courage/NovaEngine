<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) exit;

$current_user = $_SESSION['user_id'];

// 🧱 Auto-create table if missing (optional for dev environment)
$conn->query("
CREATE TABLE IF NOT EXISTS typing_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  receiver_id INT NOT NULL,
  status ENUM('typing', 'stop') DEFAULT 'stop',
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_pair (user_id, receiver_id)
)
");

// 🟢 When updating typing status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = intval($_POST['to']);
    $status = $_POST['status'] === 'typing' ? 'typing' : 'stop';

    $stmt = $conn->prepare("
        INSERT INTO typing_status (user_id, receiver_id, status, updated_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE status = VALUES(status), updated_at = NOW()
    ");
    $stmt->bind_param("iis", $current_user, $to, $status);
    $stmt->execute();
    $stmt->close();
    exit;
}

// 🕐 Auto-expire old typing statuses
$conn->query("UPDATE typing_status 
              SET status = 'stop' 
              WHERE updated_at < NOW() - INTERVAL 10 SECOND 
              AND status = 'typing'");

// 👀 When fetching typing status
if (isset($_GET['from'])) {
    $from = intval($_GET['from']);
    $stmt = $conn->prepare("SELECT status FROM typing_status WHERE user_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $from, $current_user);
    $stmt->execute();
    $stmt->bind_result($status);
    echo ($stmt->fetch()) ? $status : "stop";
    $stmt->close();
}
?>
