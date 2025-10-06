<?php
session_start();
include("../db.php");

if (!isset($_SESSION['username'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// ==================== HANDLE NEW PITCH SUBMISSION ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pitch'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $goal = trim($_POST['goal']);
    $category = trim($_POST['category']);
    $uploaded_files = [];

    if (!empty($_FILES['files']['name'][0])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        foreach ($_FILES['files']['name'] as $key => $filename) {
            if ($_FILES['files']['error'][$key] === 0) {
                $path = $targetDir . time() . "_" . basename($filename);
                move_uploaded_file($_FILES['files']['tmp_name'][$key], $path);
                $uploaded_files[] = $path;
            }
        }
    }

    $file_paths_json = json_encode($uploaded_files);

    $stmt = $conn->prepare("INSERT INTO pitches (user_id, title, description, goal, category, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $description, $goal, $category, $file_paths_json);

    if ($stmt->execute()) {
        $success = "✅ Pitch submitted successfully!";
    } else {
        $error = "❌ Failed to submit pitch: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

// ==================== HANDLE DELETE ====================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM pitches WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: pitch_idea.php");
    exit;
}

// ==================== HANDLE EDIT ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pitch'])) {
    $id = intval($_POST['pitch_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $goal = trim($_POST['goal']);
    $category = trim($_POST['category']);

    // Fetch old images
    $result = $conn->prepare("SELECT file_path FROM pitches WHERE id=? AND user_id=?");
    $result->bind_param("ii", $id, $user_id);
    $result->execute();
    $result_data = $result->get_result()->fetch_assoc();
    $old_files = json_decode($result_data['file_path'], true) ?? [];
    $result->close();

    // Upload new images if any
    if (!empty($_FILES['files']['name'][0])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        foreach ($_FILES['files']['name'] as $key => $filename) {
            if ($_FILES['files']['error'][$key] === 0) {
                $path = $targetDir . time() . "_" . basename($filename);
                move_uploaded_file($_FILES['files']['tmp_name'][$key], $path);
                $old_files[] = $path;
            }
        }
    }

    $file_paths_json = json_encode($old_files);

    $stmt = $conn->prepare("UPDATE pitches SET title=?, description=?, goal=?, category=?, file_path=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssi", $title, $description, $goal, $category, $file_paths_json, $id, $user_id);

    if ($stmt->execute()) {
        $success = "✅ Pitch updated successfully!";
    } else {
        $error = "❌ Update failed: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

// ==================== FETCH USER PITCHES ====================
$pitches = [];
$result = $conn->prepare("SELECT id, title, description, goal, category, file_path, created_at FROM pitches WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$pitchesData = $result->get_result();
while ($row = $pitchesData->fetch_assoc()) {
    $pitches[] = $row;
}
$result->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pitch Idea | Nova</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  body {
    background: linear-gradient(135deg, #0a0f2c 40%, #121a45 100%);
    color: white;
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding-top: 80px;
  }

  .navbar {
    position: fixed; top: 0; right: 0; left: 0;
    height: 55px;
    background: rgba(11, 18, 56, 0.95);
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 30px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
    z-index: 100;
  }

  .navbar .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-left: 30px;
      }

      .logo .golo {
        width: 86px;
        height: auto;
        margin-left: 20px;
      }

      .content h1{
        text-align: center;
      }
  .profile { position: relative; display: flex; align-items: center; gap: 8px; cursor: pointer; }
  .profile img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #8ab4f8; }

  .dropdown { display: none; position: absolute; top: 50px; right: 0; background: #111633; border-radius: 10px; min-width: 170px; border: 1px solid #1d2455; }
  .dropdown.show { display: block; }
  .dropdown a { color: #8ab4f8; text-decoration: none; display: block; padding: 10px 15px; }
  .dropdown a:hover { background: #1d2455; color: white; }

  .side {
    background-color: rgba(11, 18, 56, 0.95);
    position: fixed; bottom: 0; left: 0; top: 79px;
    height: 100%; width: 72px; transition: width 0.3s ease;
    border-right: 1px solid rgba(11, 18, 56, 0.95);
  }
  .side.expanded { width: 200px; }

  .side ul { list-style: none; padding: 0; margin: 20px 0; }
  .side ul li { padding: 15px 20px; display: flex; align-items: center; gap: 15px; color: #8ab4f8; cursor: pointer; transition: background 0.3s; }
  .side ul li:hover { background-color: #1d2455; }
  .side ul li span { opacity: 0; transition: opacity 0.3s; }
  .side.expanded ul li span { opacity: 1; }

  .menu-toggle { position: fixed; top: 20px; left: 20px; background: #8ab4f8; color: #0a0f2c; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; }

  .content { margin-left: 80px; padding: 40px; transition: margin-left 0.3s ease; }
  .side.expanded ~ .content { margin-left: 210px; }

  form { background: #111633; padding: 25px; border-radius: 12px; max-width: 600px; margin: 0 auto; box-shadow: 0 0 10px rgba(138,180,248,0.2); }
  input, textarea, select { width: 100%; padding: 10px; border-radius: 6px; border: none; margin-bottom: 15px; background: #0e1433; color: white; }
  input[type="file"] { color: #8ab4f8; }
  .submit_button { background: transparent; border: 1px solid #8ab4f8; padding: 10px 20px; color: #8ab4f8; border-radius: 5px; cursor: pointer; }
  .submit_button:hover { background: #8ab4f8; color: #0a0f2c; }

  .pitch-card { background: #111633; border-radius: 10px; padding: 20px; margin: 20px auto; max-width: 900px; position: relative; }
  .actions { position: absolute; top: 10px; right: 20px; }
  .actions a { color: #8ab4f8; margin-left: 10px; text-decoration: none; }
  .pitch-gallery { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
  .pitch-gallery img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; cursor: pointer; }

  /* Lightbox preview */
  .lightbox {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    align-items: center;
    justify-content: center;
    z-index: 500;
  }
  .lightbox img {
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(255,255,255,0.2);
  }
</style>
</head>
<body>

<div class="navbar">
  <div class="logo">
    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    <img class="golo" src="../asset/img/logo.png" alt="Nova Logo" />
  </div>
  <div class="profile" id="profileMenu">
    <img src="../assets/img/avatar.svg" alt="Profile" />
    <i class="fas fa-chevron-down"></i>
    <div class="dropdown" id="dropdownMenu">
      <a href="#"><i class="fas fa-user"></i> Profile</a>
      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</div>

<div class="side" id="sidebar">
  <ul>
    <li onclick="window.location.href='dreamer_dashboard.php'"><i class="fas fa-home"></i><span>Dashboard</span></li>
    <li><i class="fas fa-comments"></i><span>Chat</span></li>
    <li><i class="fas fa-lightbulb"></i><span>Pitch Idea</span></li>
    <li><i class="fas fa-briefcase"></i><span>Opportunities</span></li>
    <li><i class="fas fa-users"></i><span>Community</span></li>
    <li><i class="fas fa-cog"></i><span>Settings</span></li>
  </ul>
</div>

<div class="content">
  <h1>🚀 Pitch Your Idea / Project</h1>

  <form method="POST" enctype="multipart/form-data">
    <label>Idea Title</label>
    <input type="text" name="title" placeholder="e.g. Smart Waste System" required>
    <label>Description / Problem Statement</label>
    <textarea name="description" placeholder="Describe your idea..." required></textarea>
    <label>Goal</label>
    <select name="goal" required>
      <option value="">-- Select --</option>
      <option value="Investment">Investment</option>
      <option value="Mentorship">Mentorship</option>
      <option value="Partnership">Partnership</option>
      <option value="Exposure">Exposure</option>
    </select>
    <label>Category</label>
    <input type="text" name="category" placeholder="e.g. HealthTech" required>
    <label>Upload Images (optional)</label>
    <input type="file" name="files[]" id="fileInput" accept="image/*" multiple>
    <div id="preview"></div>
    <button type="submit" name="submit_pitch" class="submit_button">Submit Pitch</button>
  </form>

  <section class="pitches">
    <h2>💡 My Pitches</h2>
    <?php if (count($pitches) > 0): foreach ($pitches as $pitch): ?>
      <div class="pitch-card">
        <div class="actions">
          <a href="#" class="edit-btn" data-id="<?= $pitch['id'] ?>"><i class="fas fa-edit"></i></a>
          <a href="?delete=<?= $pitch['id'] ?>" onclick="return confirm('Delete this pitch?');"><i class="fas fa-trash"></i></a>
        </div>
        <h3><?= htmlspecialchars($pitch['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($pitch['description'])) ?></p>

        <?php $files = json_decode($pitch['file_path'], true);
        if ($files && is_array($files)): ?>
        <div class="pitch-gallery">
          <?php foreach ($files as $file): ?>
            <img src="<?= htmlspecialchars($file) ?>" alt="Pitch Image">
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <small><strong>Goal:</strong> <?= htmlspecialchars($pitch['goal']) ?> |
        <strong>Category:</strong> <?= htmlspecialchars($pitch['category']) ?> |
        <strong>Date:</strong> <?= htmlspecialchars($pitch['created_at']) ?></small>
      </div>
    <?php endforeach; else: ?>
      <p style="text-align:center;">You haven’t submitted any ideas yet.</p>
    <?php endif; ?>
  </section>
</div>

<!-- Lightbox Preview -->
<div class="lightbox" id="lightbox">
  <img src="" alt="Preview">
</div>

<script>
const profileMenu = document.getElementById("profileMenu");
profileMenu.addEventListener("click", () => document.getElementById("dropdownMenu").classList.toggle("show"));
document.getElementById("menuToggle").addEventListener("click", () => document.getElementById("sidebar").classList.toggle("expanded"));

// === IMAGE PREVIEW BEFORE UPLOAD ===
document.getElementById("fileInput").addEventListener("change", (e) => {
  const preview = document.getElementById("preview");
  preview.innerHTML = "";
  Array.from(e.target.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = ev => {
      const img = document.createElement("img");
      img.src = ev.target.result;
      img.style.width = "100px";
      img.style.margin = "5px";
      img.style.borderRadius = "8px";
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});

// === LIGHTBOX PREVIEW ON CLICK ===
const lightbox = document.getElementById("lightbox");
const lightboxImg = lightbox.querySelector("img");

document.addEventListener("click", e => {
  if (e.target.tagName === "IMG" && e.target.closest(".pitch-gallery")) {
    lightboxImg.src = e.target.src;
    lightbox.style.display = "flex";
  } else if (e.target === lightbox) {
    lightbox.style.display = "none";
  }
});
</script>

</body>
</html>
