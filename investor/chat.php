<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// --- Create contact if coming from "Chat" button ---
if (isset($_GET['user_id'])) {
  $contact_id = intval($_GET['user_id']);
  
  if ($contact_id !== $user_id) {
    $stmt = $conn->prepare("INSERT IGNORE INTO chat_contacts (user_id, contact_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $contact_id);
    $stmt->execute();
    $stmt->close();

    // Create reverse link for mutual chat
    $stmt = $conn->prepare("INSERT IGNORE INTO chat_contacts (user_id, contact_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $contact_id, $user_id);
    $stmt->execute();
    $stmt->close();
  }
}

// --- Fetch user contacts ---
$stmt = $conn->prepare("
  SELECT u.id, u.username 
  FROM users u
  JOIN chat_contacts c ON c.contact_id = u.id
  WHERE c.user_id = ?
  ORDER BY c.last_message_time DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$contacts = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nova Chat</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
  background: #0a0f2c;
  color: white;
  font-family: "Poppins", sans-serif;
  margin: 0;
  display: flex;
  height: 100vh;
  overflow: hidden;
}
.sidebar {
  width: 280px;
  background: #111633;
  border-right: 1px solid #1d2455;
  display: flex;
  flex-direction: column;
}
.sidebar h2 {
  text-align: center;
  background: #0e1433;
  padding: 15px;
  color: #8ab4f8;
  margin: 0;
}
.contacts {
  flex: 1;
  overflow-y: auto;
}
.contact {
  padding: 12px 15px;
  border-bottom: 1px solid #1d2455;
  cursor: pointer;
  color: #8ab4f8;
  transition: 0.3s;
}
.contact:hover {
  background: #1d2455;
  color: white;
}
.chat-area {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.chat-header {
  background: #111633;
  padding: 15px;
  border-bottom: 1px solid #1d2455;
}
.chat-header h3 {
  margin: 0;
  color: #8ab4f8;
}
#typingStatus {
  font-size: 13px;
  color: #b6ceff;
  margin-top: 5px;
}
.messages {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
}
.message {
  background: #1d2455;
  padding: 10px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
  max-width: 70%;
}
.message.sent {
  background: #8ab4f8;
  color: #0a0f2c;
  margin-left: auto;
}
.input-area {
  display: flex;
  padding: 10px;
  background: #0e1433;
}
.input-area input {
  flex: 1;
  padding: 10px;
  border: none;
  border-radius: 8px;
  margin-right: 10px;
  background: #111633;
  color: white;
}
.input-area button {
  background: #8ab4f8;
  color: #0a0f2c;
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  cursor: pointer;
}

.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-right: 8px;
  background-color: gray;
  vertical-align: middle;
}

.status-dot.online {
  background-color: #2ecc71; /* green */
}

.status-dot.offline {
  background-color: #555; /* dim */
}

</style>
</head>
<body>

<a href="investor_dashboard.php" style="position:absolute;top:18px;left:18px;color:#8ab4f8;font-size:22px;text-decoration:none;" title="Back to Dashboard">
    <i class="fas fa-arrow-left"></i>
</a>

<div class="sidebar">
  <h2>💬 Chats</h2>
  <div class="contacts" id="contactList">
    <?php if ($contacts->num_rows > 0): ?>
      <?php while ($row = $contacts->fetch_assoc()): ?>
        <div class="contact" data-id="<?php echo $row['id']; ?>">
  <span class="status-dot" id="status-<?php echo $row['id']; ?>"></span>
  <i class="fas fa-user-circle"></i> 
  <?php echo htmlspecialchars($row['username']); ?>
</div>

      <?php endwhile; ?>
    <?php else: ?>
      <p style="padding:15px; color:#888;">No chats yet. Start one from an opportunity!</p>
    <?php endif; ?>
  </div>
</div>

<div class="chat-area">
  <div class="chat-header">
    <h3 id="chatUser">Select a chat</h3>
    <div id="typingStatus"></div>
  </div>
  <div class="messages" id="chatBox"></div>
  <form class="input-area" id="chatForm">
    <input type="text" id="messageInput" placeholder="Type a message..." autocomplete="off">
    <button type="submit"><i class="fas fa-paper-plane"></i></button>
  </form>
</div>

<script>
let currentChat = null;

// ===== LOAD MESSAGES =====
async function loadMessages() {
  if (!currentChat) return;
  const res = await fetch(`fetch_messages.php?user_id=${currentChat}`);
  const data = await res.json();
  document.getElementById("chatBox").innerHTML = data.messages;
  document.getElementById("chatBox").scrollTop = document.getElementById("chatBox").scrollHeight;
}

// ===== ONLINE STATUS UPDATES =====
function updateActivity() {
  fetch("update_activity.php");
}
setInterval(updateActivity, 10000); // every 10 seconds

function checkUserStatus() {
  if (!currentChat) return;
  fetch(`get_activity.php?id=${currentChat}`)
    .then(res => res.json())
    .then(data => {
      const typingDiv = document.getElementById("typingStatus");
      if (data.is_online == 1) {
        typingDiv.textContent = "Online";
      } else if (data.last_seen) {
        typingDiv.textContent = "Last seen at " + data.last_seen;
      }
    });
}
setInterval(checkUserStatus, 10000);


// ====== UPDATE CONTACT ONLINE STATUS ======
function updateContactStatuses() {
  const contactDivs = document.querySelectorAll(".contact");
  contactDivs.forEach(contact => {
    const id = contact.dataset.id;
    fetch(`get_activity.php?id=${id}`)
      .then(res => res.json())
      .then(data => {
        const dot = document.getElementById(`status-${id}`);
        if (!dot) return;
        if (data.is_online == 1) {
          dot.classList.add("online");
          dot.classList.remove("offline");
          dot.title = "Online";
        } else {
          dot.classList.remove("online");
          dot.classList.add("offline");
          dot.title = data.last_seen ? `Last seen ${data.last_seen}` : "Offline";
        }
      })
      .catch(err => console.error("Status check failed:", err));
  });
}

setInterval(updateContactStatuses, 10000); // every 10 seconds
updateContactStatuses(); // initial run



// ===== CONTACT CLICK =====
document.querySelectorAll(".contact").forEach(contact => {
  contact.addEventListener("click", () => {
    currentChat = contact.dataset.id;
    document.getElementById("chatUser").innerText = contact.innerText.trim();
    loadMessages();
  });
});

// ===== SEND MESSAGE =====
document.getElementById("chatForm").addEventListener("submit", async e => {
  e.preventDefault();
  const msg = document.getElementById("messageInput").value.trim();
  if (!msg || !currentChat) return;

  await fetch("send_messages.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `receiver_id=${currentChat}&message=${encodeURIComponent(msg)}`
  });
  
  document.getElementById("messageInput").value = "";
  loadMessages();
});

// ===== REALTIME REFRESH =====
setInterval(() => {
  if (currentChat) {
    loadMessages();
    checkTypingStatus();
  }
}, 2000);

// ====== TYPING INDICATOR ======
let typingTimeout;
const input = document.getElementById("messageInput");

input.addEventListener("input", () => {
  if (!currentChat) return;
  fetch("typing_status.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `to=${currentChat}&status=typing`
  });
  clearTimeout(typingTimeout);
  typingTimeout = setTimeout(() => {
    fetch("typing_status.php", {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded"},
      body: `to=${currentChat}&status=stop`
    });
  }, 1500);
});

function checkTypingStatus() {
  if (!currentChat) return;
  fetch(`typing_status.php?from=${currentChat}`)
    .then(res => res.text())
    .then(status => {
      const typingDiv = document.getElementById("typingStatus");
      typingDiv.textContent = (status === "typing") ? "Typing..." : "";
    });
}
</script>

</body>
</html>
