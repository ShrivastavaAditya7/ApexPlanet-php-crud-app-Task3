<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if ($username === '' || $password === '' || $confirm === '') {
        $message = "All fields are required.";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashedPassword);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Error registering user.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - ApexPlanet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .register-container {
      max-width: 420px;
      margin: auto;
      margin-top: 5%;
      background-color: #1e293b;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
    }

    .form-control {
      background-color: #334155;
      color: #fff;
      border: none;
    }

    .form-control:focus {
      background-color: #475569;
      color: #fff;
      border: 1px solid #38bdf8;
      box-shadow: none;
    }

    .btn-custom {
      background-color: #38bdf8;
      color: #000;
      font-weight: bold;
    }

    .btn-custom:hover {
      background-color: #0ea5e9;
    }

    .form-title {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }

    .error-message {
      color: #f87171;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="form-title">Create Account</div>

    <?php if ($message): ?>
      <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="username" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>

      <div class="mb-4">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-custom">Register</button>
      </div>

      <p class="mt-3 text-center">
        Already registered? <a href="login.php" class="text-info">Login</a>
      </p>
    </form>
  </div>
</body>
</html>
