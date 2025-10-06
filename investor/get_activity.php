<?php
include("../db.php");
$user_id = intval($_GET['id']);

$stmt = $conn->prepare("
  SELECT 
    IF(TIMESTAMPDIFF(SECOND, last_active, NOW()) < 30, 1, 0) AS is_online,
    DATE_FORMAT(last_active, '%H:%i') AS last_seen
  FROM user_activity
  WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
  echo json_encode($result);
} else {
  echo json_encode(["is_online" => 0, "last_seen" => null]);
}
?>
