<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $valid_username = "slm";
    $valid_password = "123";

    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Login - Clinic Sok Leap Metrey</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <style>
    body {
      background: linear-gradient(135deg, #6b73ff, #000dff);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Segoe UI", sans-serif;
    }

    .login-box {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-header h2 {
      font-weight: 700;
      color: #3f51b5;
    }

    .form-control:focus {
      border-color: #3f51b5;
      box-shadow: 0 0 0 0.25rem rgba(63, 81, 181, 0.25);
    }

    .btn-primary {
      background-color: #3f51b5;
      border-color: #3f51b5;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #2c3eb8;
      border-color: #2c3eb8;
    }

    .form-footer {
      text-align: center;
      margin-top: 25px;
    }

    .form-footer small a {
      color: #3f51b5;
      text-decoration: none;
    }

    .form-footer small a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="login-header">
      <h2>Clinic Login</h2>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center" role="alert">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          name="username"
          placeholder="Enter your username"
          required
        />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          placeholder="Enter your password"
          required
        />
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="form-footer">
      <small>Forgot your password? <a href="#">Click here</a></small>
    </div>
  </div>
</body>
</html>
