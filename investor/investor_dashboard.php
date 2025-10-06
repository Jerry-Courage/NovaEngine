<?php
session_start();
include("../db.php");

if (!isset($_SESSION['username'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Investor Info
$stmt = $conn->prepare("SELECT fullName_or_organization, preferred_industries, country FROM investors WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fullName, $preferred_industries, $country);
$stmt->fetch();
$stmt->close();

// Fallbacks
if (empty($fullName)) $fullName = $_SESSION['username'];
if (empty($preferred_industries)) $preferred_industries = "Technology, Renewable Energy, Health, FinTech";

// Build industries array (normalized to lowercase for matching)
$industries = array_map('trim', explode(',', $preferred_industries));
$industries_lower = array_map('strtolower', $industries);

// Fetch dreamers and match by field_of_interest
$matched_dreamers = [];
$stmt2 = $conn->prepare("SELECT user_id, fullName, bio, goal, field_of_interest, country FROM dreamers WHERE field_of_interest IS NOT NULL");
if ($stmt2) {
    $stmt2->execute();
    $res = $stmt2->get_result();
    while ($row = $res->fetch_assoc()) {
        $fields = strtolower($row['field_of_interest'] ?? '');
        foreach ($industries_lower as $ind) {
            if ($ind === '') continue;
            if (stripos($fields, $ind) !== false) {
                $matched_dreamers[] = $row;
                break;
            }
        }
    }
    $stmt2->close();
} else {
    // fallback: no dreamers found or query failed
    $matched_dreamers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nova | Investor | Dashboard</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #0a0f2c 40%, #121a45 100%);
      color: white;
      font-family: "Poppins", Arial, sans-serif;
      margin: 0;
      padding: 0;
      padding-top: 100px;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    /* --- NAVBAR --- */
    .navbar {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      height: 55px;
      background: rgba(11, 18, 56, 0.95);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 30px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
      z-index: 100;
      backdrop-filter: blur(20px);
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

    .text-icon img {
      width: 210px;
      height: 120px;
      margin-left: -70px;
    }

    /* --- PROFILE DROPDOWN --- */
    .profile {
      position: relative;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #8ab4f8;
      transition: transform 0.2s ease;
    }

    .profile img:hover { transform: scale(1.05); }

    .profile i {
      font-size: 14px;
      color: #8ab4f8;
      transition: transform 0.2s ease;
    }

    .profile.active i { transform: rotate(180deg); }

    .dropdown {
      display: none;
      position: absolute;
      top: 50px;
      right: 0;
      background-color: #111633;
      border-radius: 10px;
      min-width: 170px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      z-index: 1;
      overflow: hidden;
      border: 1px solid #1d2455;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .dropdown.show {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }

    .dropdown-header {
      padding: 12px 15px;
      background-color: #0e1433;
      color: #8ab4f8;
      border-bottom: 1px solid #1d2455;
      font-size: 0.95rem;
    }

    .dropdown a {
      color: #8ab4f8;
      padding: 10px 15px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s ease;
    }

    .dropdown a:hover {
      background-color: #1d2455;
      color: white;
    }

    .dropdown i { width: 18px; text-align: center; }

    /* --- SIDEBAR --- */
    .side {
      background-color: rgba(11, 18, 56, 0.95);
      position: fixed;
      bottom: 0;
      left: 0;
      top: 79px;
      height: 100%;
      width: 72px;
      border-right: 1px solid rgba(11, 18, 56, 0.95);
      z-index: 200;
      overflow: hidden;
      transition: width 0.3s ease;
    }

    .side.expanded { width: 200px; }

    .menu-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #8ab4f8;
      color: #0a0f2c;
      border: none;
      border-radius: 6px;
      padding: 6px 10px;
      cursor: pointer;
      z-index: 250;
      transition: background 0.3s;
    }

    .menu-toggle:hover { background: #b6ceff; }

    .side ul {
      list-style: none;
      padding: 0;
      margin: 20px 0;
    }

    .side ul li {
      padding: 15px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      color: #8ab4f8;
      cursor: pointer;
      white-space: nowrap;
      transition: background 0.3s;
    }

    .side ul li:hover { background-color: #1d2455; }
    .side ul li i { font-size: 20px; min-width: 24px; text-align: center; }
    .side ul li span { opacity: 0; transition: opacity 0.3s; }
    .side.expanded ul li span { opacity: 1; }

    /* --- HERO --- */
    .hero { text-align: center; margin-top: 40px; margin-bottom: 10px; }
    .hero h1 { font-size: 1.6rem; margin-bottom: 5px; }
    .hero p { color: #8ab4f8; font-size: 0.95rem; }

    /* --- SEARCH BAR --- */
    .bar {
      margin-top: 10px;
      max-width: 750px;
      width: 90%;
      position: relative;
      display: flex;
      align-items: center;
    }

    .put {
      height: 50px;
      width: 100%;
      font-size: 15px;
      border: none;
      border-radius: 25px;
      padding-left: 45px;
      padding-right: 45px;
      outline: none;
      box-sizing: border-box;
    }

    .icon {
      width: 20px;
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
    }

    .mic {
      width: 20px;
      height: 20px;
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      border-radius: 50%;
      background-color: lightgray;
      padding: 5px;
      border: 1px solid lightgray;
      transition: background 0.3s ease;
    }

    .recording { background-color: #ff2e2e; border: 1px solid #ff2e2e; }

    /* --- TAGS --- */
    .tags { margin: 40px 0 20px; text-align: center; }
    .tags span {
      margin: 0 12px;
      padding: 6px 12px;
      border-radius: 12px;
      background: #111633;
      cursor: pointer;
      font-weight: bold;
      color: #8ab4f8;
      transition: background 0.3s ease;
    }
    .tags span:hover { background: #1d2455; }

    /* --- OPPORTUNITIES --- */
    .opportunities {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      max-width: 1000px;
      width: 90%;
      margin: 20px auto 60px;
    }

    .card {
      background: #111633;
      border-radius: 12px;
      padding: 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 15px rgba(138, 180, 248, 0.2);
    }

    .badge {
      display: inline-block;
      margin-top: 10px;
      padding: 5px 12px;
      border-radius: 12px;
      background: #8ab4f8;
      color: #0a0f2c;
      font-weight: bold;
      font-size: 0.85rem;
    }

    .time {
      display: block;
      margin-top: 8px;
      font-size: 0.8rem;
      color: #888;
    }

    .fab {
      position: fixed;
      bottom: 25px;
      right: 25px;
      background: #8ab4f8;
      color: #0a0f2c;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 30px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      transition: 0.3s;
    }

    .fab:hover { transform: scale(1.1); }

    .chat-btn {
      margin-top: 12px;
      display: inline-block;
      background: #8ab4f8;
      color: #0a0f2c;
      padding: 8px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }
    .chat-btn:hover { background: #b3c9ff; color: #0a0f2c; }
  </style>
</head>

<body>
  <!-- NAVBAR -->
  <div class="navbar">
    <div class="logo">
      <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
      </button>
      <img class="golo" src="../asset/img/logo.png" alt="Nova Logo" />
    </div>

    <div class="text-icon">
      <img src="../image/file_00000000e33861f78775d9159bad979d.png" alt="Nova" />
    </div>

    <div class="profile" id="profileMenu">
      <img src="../assets/img/avatar.svg" alt="Profile" />
      <i class="fas fa-chevron-down"></i>
      <div class="dropdown" id="dropdownMenu">
        <div class="dropdown-header"><strong><?php echo htmlspecialchars($fullName); ?></strong></div>
        <a href="#"><i class="fas fa-user" style = "transform: rotate(0deg)"></i> Profile</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  </div>

  <!-- SIDEBAR -->
  <div class="side" id="sidebar">
    <ul>
      <li onclick="window.location.href='chat.php'"><i class="fas fa-comments"></i><span>Chat</span></li>
      <li onclick="window.location.href='browse_startups.php'"><i class="fas fa-lightbulb"></i><span>Browse Startups</span></li>
      <li onclick="window.location.href='portfolio.php'"><i class="fas fa-briefcase"></i><span>My Portfolio</span></li>
      <li onclick="window.location.href='community.php'"><i class="fas fa-users"></i><span>Community</span></li>
      <li onclick="window.location.href='settings.php'"><i class="fas fa-cog"></i><span>Settings</span></li>
    </ul>
  </div>

  <!-- HERO -->
  <section class="hero">
    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h1>
    <p>Explore promising startups that match your investment interests.</p>
  </section>

  <!-- SEARCH BAR -->
  <form action="search_startups.php" method="get" class="bar">
    <img class="icon" src="../image/search.svg" alt="search icon" />
    <input
      class="put"
      type="search"
      name="q"
      id="searchInput"
      placeholder="Search startups, industries, or funding rounds..."
    />
    <img class="mic" src="../image/mic.png" alt="mic button" id="micButton" />
  </form>

  <!-- INDUSTRIES -->
  <div class="tags">
    <h2>Interested In:</h2>
    <?php foreach ($industries as $industry): ?>
      <span><?php echo htmlspecialchars($industry); ?></span>
    <?php endforeach; ?>
  </div>

  <!-- OPPORTUNITIES -->
  <h2>Promising Startups and Dreamers</h2>
  <div class="opportunities">
    <?php if (!empty($matched_dreamers)): ?>
      <?php foreach ($matched_dreamers as $dreamer): ?>
        <div class="card">
          <h3><?php echo htmlspecialchars($dreamer['fullName'] ?: 'Unnamed'); ?></h3>
          <p><?php echo htmlspecialchars($dreamer['bio'] ?: 'No bio provided.'); ?></p>
          <p><strong>Goal:</strong> <?php echo htmlspecialchars($dreamer['goal'] ?: '-'); ?></p>
          <p><strong>Field:</strong> <?php echo htmlspecialchars($dreamer['field_of_interest'] ?: '-'); ?></p>
          <p><strong>Country:</strong> <?php echo htmlspecialchars($dreamer['country'] ?: '-'); ?></p>
          <!-- NOTE: chat link points to the dreamer chat implementation -->
          <a class="chat-btn" href="chat.php?user_id=<?php echo (int)$dreamer['user_id']; ?>">💬 Chat</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;color:#8ab4f8; width:100%;">No matching dreamers found for your current interests.</p>
    <?php endif; ?>
  </div>

  <button class="fab">+</button>

  <script>
    // Dropdown toggle
    const profileMenu = document.getElementById("profileMenu");
    const dropdown = document.getElementById("dropdownMenu");
    profileMenu.addEventListener("click", () => {
      dropdown.classList.toggle("show");
      profileMenu.classList.toggle("active");
    });
    document.addEventListener("click", (e) => {
      if (!profileMenu.contains(e.target)) {
        dropdown.classList.remove("show");
        profileMenu.classList.remove("active");
      }
    });

    // Sidebar toggle
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");
    menuToggle.addEventListener("click", () => {
      sidebar.classList.toggle("expanded");
    });

    // Voice recognition
    const micButton = document.getElementById("micButton");
    const searchInput = document.getElementById("searchInput");
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (SpeechRecognition) {
      const recognition = new SpeechRecognition();
      recognition.lang = "en-US";
      micButton.addEventListener("click", () => {
        recognition.start();
        micButton.classList.add("recording");
      });
      recognition.onresult = (event) => {
        searchInput.value = event.results[0][0].transcript;
      };
      recognition.onend = () => {
        micButton.classList.remove("recording");
      };
    }
  </script>
</body>
</html>
