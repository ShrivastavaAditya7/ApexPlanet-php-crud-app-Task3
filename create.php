<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'auth_session.php';
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $message = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $message = "Error adding post.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f5f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
      margin-top: 80px;
      max-width: 600px;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .navbar {
      background-color: #0f172a;
    }

    .navbar-brand, .nav-link {
      color: #f8fafc !important;
    }

    .btn-primary {
      background-color: #0ea5e9;
      border: none;
    }

    .error {
      color: #e11d48;
    }
  </style>
</head>
<body>

<nav class="navbar fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">ApexPlanet</a>
    <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
  </div>
</nav>

<div class="container">
  <div class="card p-4">
    <h3 class="mb-4">📝 Add New Blog Post</h3>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="create.php">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" rows="5" class="form-control" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Publish</button>
    </form>
  </div>
</div>

</body>
</html>
