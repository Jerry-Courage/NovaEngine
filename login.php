<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];

            if ($user['profile_complete'] == 0) {
                // Redirect to their profile page if incomplete
                switch ($user['user_type']) {
                    case 'mentor': header("Location: profiles/mentor.php"); break;
                    case 'dreamer': header("Location: profiles/dreamer.php"); break;
                    case 'investor': header("Location: profiles/investor.php"); break;
                    case 'company': header("Location: profiles/company.php"); break;
                }
            } else {
                // Profile already complete → send to dashboard
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No account found with that email!";
    }
}
?>






<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova | Login</title>
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
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <!-- mycss -->
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="container">
      <!-- <div class="img">
        <img
          src="image/file_00000000e1c461f9a425912a16c49f72-removebg-preview.png"
          alt=""
        />
      </div>
 -->
      <!-- Login Content -->
      <div class="login-content">
        <?php if (!empty($message)): ?>
          <div class="alert" style="color: red; text-align: center; margin-bottom: 10px;">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <form action="" method="POST">
          <img src="asset/img/logo.png" alt="" />
          <h2 class="title">Welcome Back</h2>

          <div class="input-div one">
            <div class="i">
              <i class="fas fa-user"></i>
            </div>
            <div class="div">
              <input type="text" class="input" name="email" placeholder="Email" />
            </div>
          </div>

          <!-- Input Password -->
          <div class="input-div pass">
            <div class="i">
              <i class="fas fa-lock"></i>
            </div>
            <div class="div">
              <input type="password" class="input" name="password" placeholder="Password" />
            </div>
          </div>
          <!-- End Input Password -->
          <div style="display: flex">
            <a
              href="signUp.php"
              style="margin-right: 30px; display: inline-block"
              >Don't  have an account?</a
            >
            <a href="#">Forgot Password? </a>
          </div>

          <input type="submit" class="btn" value="Login" />
        </form>
      </div>
    </div>

    <script type="text/javascript" src="assets/js/main.js"></script>
  </body>
</html>
