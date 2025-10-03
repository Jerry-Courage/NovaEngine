<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // dreamer, mentor, company, investor

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Email already Exist!";
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
            case 'Investor': header("Location: profiles/investor.php"); break;
            case 'Company': header("Location: profiles/company.php"); break;
          
        } // redirect after signup based on role
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova | Sign up</title>
    <link
      rel="icon"
      href="image/file_00000000e1c461f9a425912a16c49f72-removebg-preview.png"
    />
    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap"
      rel="stylesheet"
    />
    <!-- Font Awesome -->
    <script
      src="https://kit.fontawesome.com/a81368914c.js"
      crossorigin="anonymous"
    ></script>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="background">
    </div>
    <div class="container">
      <!-- Login Content -->
      <div class="login-content">
        <?php if (!empty($message)): ?>
          <div class="alert" style="color: red; text-align: center; margin-bottom: 10px;">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <form action="" method="POST">
          <img src="asset/img/logo.png" alt="User Avatar" />
          <h2 class="title">Welcome</h2>

          <!-- Username -->
          <div class="input-div one">
            <div class="i">
              <i class="fas fa-user"></i>
            </div>
            <div class="div">
              <input type="text" class="input" name="username" placeholder="Username" />
            </div>
          </div>

          <!-- Email -->
          <div class="input-div one">
            <div class="i">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="div">
              <input type="email" class="input" name="email" placeholder="Email" />
            </div>
          </div>

          <!-- Password -->
          <div class="input-div pass">
            <div class="i">
              <i class="fas fa-lock"></i>
            </div>
            <div class="div">
              <input type="password" class="input" name="password" placeholder="Password" />
            </div>
          </div>

          <!-- Role Select -->
          <label for="user_type">How do you describe yourself:</label>
          <select name="user_type" class="what">
            <option value="Dreamer">Dreamer (Start up) / Skilled individual</option>
            <option value="Investor">Investor (Funding, Mentorships, Scholarships)</option>
            <option value="Company">Company (Business, Internship)</option>
            <option value="Mentor">Mentor</option>
          </select>

          <a href="login.php">Already have an account?</a>
          <input type="submit" class="btn" value="Sign up" />
        </form>
      </div>
    </div>

    <script type="text/javascript" src="assets/js/main.js"></script>
  </body>
</html>
