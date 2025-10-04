<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova | Dashboard</title>
    <style>
      /* --- GLOBAL THEME --- */
      body {
        background: linear-gradient(135deg, #0a0f2c 40%, #121a45 100%);
        color: white;
        font-family: "Poppins", Arial, sans-serif;
        margin: 0;
        padding: 0px;
        padding-top: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
        animation: fadeIn 0.6s ease-in;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
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
        padding: 12px 30px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        z-index: 100;
        backdrop-filter: blur(20px);
      }

      .navbar .logo {
        display: flex;
        justify-content: centers;
        align-items: center;
        gap: 10px;
        margin-left: 63px;
      }

      .logo .golo {
        width: 86px;
        height: auto;
        margin-left: -39px;
      }

      .text-icon {
        margin-left: 460px;
        margin-top: 5px;
      
      }
      .text-icon img {
        width: 265px;
        height: 137px;
      }

      /* --- PROFILE DROPDOWN --- */
      .profile {
        position: absolute;
        display: flex;
        left: 85%;
        align-items: center;

        gap: 10px;
      }

      .profile img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #8ab4f8;
        transition: transform 0.2s ease;
      }

      .profile img:hover {
        transform: scale(1.05);
      }

      .dropdown {
        display: none;
        position: absolute;
        right: 0;
        background-color: #111633;
        border-radius: 10px;
        min-width: 160px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        z-index: 1;
        overflow: hidden;
        border: 1px solid #1d2455;
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

      .profile:hover .dropdown {
        display: block;
      }

      .dropdown i {
        width: 18px;
        text-align: center;
      }

      /* --- HERO SECTION --- */
      .hero {
        text-align: center;
        transform: scale(0.9);
        margin-top: 40px;
        margin-bottom: 10px;
      }

      .hero img {
        width: 180px;
        height: auto;
        margin-bottom: -10px;
      }

      .hero h1 {
        font-size: 1.6rem;
        margin-bottom: 5px;
      }

      .hero p {
        color: #8ab4f8;
        font-size: 0.95rem;
      }

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

      .recording {
        background-color: #ff2e2e;
        border: 1px solid #ff2e2e;
      }

      /* --- TAGS --- */
      .tags {
        margin: 40px 0 20px;
        text-align: center;
      }
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
      .tags span:hover {
        background: #1d2455;
      }

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

      .card h3 {
        margin-top: 0;
        margin-bottom: 10px;
      }

      .card p {
        color: #bbb;
        font-size: 0.95rem;
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

      /* --- FLOATING ACTION BUTTON --- */
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
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: 0.3s;
      }

      .fab:hover {
        transform: scale(1.1);
      }
      .name {
        display: inline-block;

        font-weight: bold;
        color: #8ab4f8;
      }

      /* --- RESPONSIVE --- */
      @media (max-width: 768px) {
        .hero img {
          width: 140px;
        }
        .hero h1 {
          font-size: 1.3rem;
        }
        .bar {
          width: 95%;
        }
      }
    </style>
    <!-- Include Font Awesome for icons -->
    <script
      src="https://kit.fontawesome.com/a076d05399.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <!-- NAVBAR -->
    <div class="navbar">
      <div class="logo">
        <img class="golo" src="asset/img/logo.png" alt="Nova Logo" />
      </div>

      <div class="text-icon">
        <img
          
          src="image/file_00000000e33861f78775d9159bad979d.png"
          alt="Nova"
        />
      </div>

      <div class="profile">
        <img src="assets/img/avatar.svg" alt="Profile" />
        <h3 class="name">John Doe</h3>
        <div class="dropdown">
          <a href="#"><i class="fas fa-user"></i> Profile</a>
          <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>

    <!-- HERO -->
    <div
      class="side"
      style="
        background-color: rgba(11, 18, 56, 0.95);
        position: fixed;
        bottom: 0;
        left: 0;
        top: 55px;
        height: 100%;
        width: 72px;
        border-bottom-style: solid;
        border-right-color: #0a0f2c;
        border-width: 5px;
        z-index: 200;
      "
    ></div>

    <section class="hero">
      <h1>Welcome to the Opportunity Engine</h1>
      <p>Not just a search bar - your gateway to the future</p>
    </section>

    <!-- SEARCH BAR -->
    <form action="search.php" method="get" class="bar">
      <img class="icon" src="image/search.svg" alt="search icon" />
      <input
        class="put"
        type="search"
        name="q"
        id="searchInput"
        placeholder="What are you looking for? Your future starts here..."
        aria-label="Search"
      />
      <img class="mic" src="image/mic.png" alt="mic button" id="micButton" />
    </form>

    <!-- TAGS -->
    <div class="tags">
      <h2>Discover Opportunities in:</h2>
      <span>Innovation</span>
      <span>Education</span>
      <span>Finance</span>
      <span>Health</span>
    </div>

    <!-- OPPORTUNITIES -->
    <h2>Matched Opportunities</h2>
    <div class="opportunities">
      <div class="card">
        <h3>🚀 Seed funding for EdTech startup</h3>
        <p>
          Investment opportunity for a seed round in an early-stage EdTech
          company.
        </p>
        <span class="badge">Investment</span>
        <span class="time">Posted 2 days ago</span>
      </div>

      <div class="card">
        <h3>🎯 Mentor for social impact project</h3>
        <p>
          Looking for an experienced mentor to guide a nonprofit organization.
        </p>
        <span class="badge">Mentorship</span>
        <span class="time">Posted 5 days ago</span>
      </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fab">+</button>

    <!-- Voice Recognition -->
    <script>
      const micButton = document.getElementById("micButton");
      const searchInput = document.getElementById("searchInput");

      const SpeechRecognition =
        window.SpeechRecognition || window.webkitSpeechRecognition;

      if (SpeechRecognition) {
        const recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = "en-US";

        micButton.addEventListener("click", () => {
          recognition.start();
          micButton.classList.add("recording");
        });

        recognition.onresult = (event) => {
          const speechResult = event.results[0][0].transcript;
          searchInput.value = speechResult;
        };

        recognition.onend = () => {
          micButton.classList.remove("recording");
        };

        recognition.onerror = (event) => {
          micButton.classList.remove("recording");
          alert("Speech recognition error: " + event.error);
        };
      } else {
        micButton.addEventListener("click", () => {
          alert("Sorry, your browser does not support speech recognition.");
        });
      }
    </script>
  </body>
</html>
