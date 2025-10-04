<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nova | Dashboard</title>
  <style>
    body {
      background-color: #0a0f2c;
      color: white;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    /* Logo / Title block */
    .go {
      font-size: 1.3rem;
      margin-bottom: 40px;
      text-align: center;
    }

    /* Search container */
    .bar {
      margin-top: -20px;
      max-width: 750px;
      width: 106%;
      position: relative;
      display: flex;
      align-items: center;
    }

    /* Search input */
    .put {
      height: 50px;
      width: 100%;
      font-size: 15px;
      border: none;
      border-radius: 20px;
      padding-left: 45px;
      padding-right: 45px;
      outline: none;
      box-sizing: border-box;
    }

    /* Search icon */
    .icon {
      width: 20px;
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
    }

    /* Mic button */
    .mic {
      width: 20px;
      height: 20px;
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      transition: filter 0.3s ease;
      border-radius: 50%;
      background-color: lightgray;
      padding: 5px;
      border: 1px solid lightgray;
    }

    /* Red glow when recording */
    .recording {
      background-color: #FF2E2E;
      border: 1px solid #FF2E2E;
    }

    /* Category tags */
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

    /* Opportunity cards */
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
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
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

    /* Mobile adjustments */
    @media (max-width: 768px) {
      .go { font-size: 1rem; }
      .put { height: 45px; font-size: 14px; }
      .icon { width: 18px; left: 12px; }
      .mic { width: 22px; right: 12px; }
    }
    @media (max-width: 480px) {
      .go { font-size: 0.9rem; }
      .put { height: 40px; font-size: 13px; }
      .icon { width: 16px; left: 10px; }
      .mic { width: 20px; right: 10px; }
    }
  </style>
</head>
<body>
  <!-- Logo / Title -->
  <div style="transform: scale(0.85);margin-bottom: -30px;">
    <div class="go" style="margin-top: 30px; margin-bottom: 30px; text-align: center;">
      <img src="image/Still 2025-10-02 184149_1.1.2.png" alt="Nova Logo" style="width: 200px; height: auto; margin-bottom: -20px;" />
      <h1>Welcome to the Opportunity Engine</h1>
      <p>Not just a search bar - your gateway to the future</p>
    </div>

    <!-- Search Form -->
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
  </div>

  <!-- Categories -->
   
  <div class="tags">
    <h2>Discover Opportunities in:</h2>
    <span>Innovation</span>
    <span>Education</span>
    <span>Finance</span>
    <span>Health</span>
  </div>

  <!-- Opportunities -->
  <h2>Matched Opportunities</h2>
  <div class="opportunities">
    <div class="card">
      <h3>🚀 Seed funding for EdTech startup</h3>
      <p>Investment opportunity for a seed round in an early-stage EdTech company.</p>
      <span class="badge">Investment</span>
      <span class="time">Posted 2 days ago</span>
    </div>
    <div class="card">
      <h3>🎯 Mentor for social impact project</h3>
      <p>Looking for an experienced mentor to guide a nonprofit organization.</p>
      <span class="badge">Mentorship</span>
      <span class="time">Posted 5 days ago</span>
    </div>
  </div>

  <script>
    const micButton = document.getElementById("micButton");
    const searchInput = document.getElementById("searchInput");

    // SpeechRecognition setup
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (SpeechRecognition) {
      const recognition = new SpeechRecognition();
      recognition.continuous = false;
      recognition.interimResults = false;
      recognition.lang = "en-US";

      micButton.addEventListener("click", () => {
        recognition.start();
        micButton.classList.add("recording"); // 🔴 glow when listening
      });

      recognition.onresult = (event) => {
        const speechResult = event.results[0][0].transcript;
        searchInput.value = speechResult;
      };

      recognition.onend = () => {
        micButton.classList.remove("recording"); // back to normal
      };

      recognition.onerror = (event) => {
        micButton.classList.remove("recording");
        console.error("Speech recognition error:", event.error);
        alert("Speech recognition error: " + event.error);
      };
    } else {
      // Keep mic visible, but alert user if clicked
      micButton.addEventListener("click", () => {
        alert("Sorry, your browser does not support speech recognition.");
      });
    }
  </script>
</body>
</html>
