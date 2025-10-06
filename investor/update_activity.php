<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) exit;
$user_id = $_SESSION['user_id'];

// Update user’s last activity
$conn->query("INSERT INTO user_activity (user_id, last_active, is_online)
              VALUES ($user_id, NOW(), 1)
              ON DUPLICATE KEY UPDATE last_active = NOW(), is_online = 1");
?>
