<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; 

    if (empty($username) || empty($email) || empty($password) || empty($user_type)) {
        $message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address!";
    } else {
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $user_type);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $user_type;

                switch ($user_type) {
                    case 'Mentor': header("Location: profiles/mentor.php"); break;
                    case 'Dreamer': header("Location: profiles/dreamer.php"); break;
                    case 'Investor': header("Location: profiles/investors.php"); break;
                    case 'Company': header("Location: profiles/company.php"); break;
                }
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nova | Sign up</title>
  <link rel="icon" href="image/file_00000000e1c461f9a425912a16c49f72-removebg-preview.png" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <div class="background"></div>
  <div class="container">
    <div class="login-content">
      <?php if (!empty($message)): ?>
        <div class="alert <?php echo (strpos($message, 'Error') !== false || strpos($message, 'exists') !== false) ? 'alert-error' : 'alert-success'; ?>">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <form  method="POST">
        <img src="asset/img/logo.png" alt="User Avatar" />
        <h2 class="title">Welcome</h2>

        <!-- Username -->
        <div class="input-div one">
          <div class="i"><i class="fas fa-user"></i></div>
          <div class="div"><input type="text" class="input" name="username" placeholder="Username" required /></div>
        </div>

        <!-- Email -->
        <div class="input-div one">
          <div class="i"><i class="fas fa-envelope"></i></div>
          <div class="div"><input type="email" class="input" name="email" placeholder="Email" required /></div>
        </div>

        <!-- Password -->
        <div class="input-div pass">
          <div class="i"><i class="fas fa-lock"></i></div>
          <div class="div">
            <input type="password" id="password" class="input" name="password" placeholder="Password" required minlength="6" />
            <i class="fas fa-eye" id="togglePassword" style="cursor:pointer; position:absolute; right:10px; top:10px;"></i>
          </div>
        </div>

        <!-- Role Selector -->
        <label>How do you describe yourself:</label>
        <div class="role-options">
          <label class="role-card">
            <input type="radio" name="user_type" value="Dreamer" required />
            <span><i class="fas fa-lightbulb"></i> Dreamer</span>
          </label>
          <label class="role-card">
            <input type="radio" name="user_type" value="Investor" />
            <span><i class="fas fa-hand-holding-usd"></i> Investor</span>
          </label>
          <label class="role-card">
            <input type="radio" name="user_type" value="Company" />
            <span><i class="fas fa-building"></i> Company</span>
          </label>
          <label class="role-card">
            <input type="radio" name="user_type" value="Mentor" />
            <span><i class="fas fa-chalkboard-teacher"></i> Mentor</span>
          </label>
          <label class="role-card">
            <input type="radio" name="user_type" value="Startup" />
            <span><i class="fas fa-chalkboard-teacher"></i> Startup</span>
          </label>
          <label class="role-card">
            <input type="radio" name="user_type" value="Students" />
            <span><i class="fas fa-chalkboard-teacher"></i> Students</span>
          </label>



        
        </div>

        <a href="login.php">Already have an account?</a>
        <input type="submit" class="btn" value="Sign up" />
      </form>
    </div>
  </div>

  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");
    togglePassword.addEventListener("click", () => {
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      togglePassword.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>
