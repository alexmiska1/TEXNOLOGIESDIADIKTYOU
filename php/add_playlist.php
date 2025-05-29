<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    if ($title !== '') {
        $stmt = $db->prepare("INSERT INTO playlists (title, is_public, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $is_public, $user['id']]);
        header('Location: playlists.php');
        exit();
    } else {
        $error = 'Title is required';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Playlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">PlaylistApp</a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="playlists.php">Playlists</a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="add_playlist.php">Add Playlist</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
      <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
    </div>
  </div>
</nav>

<main class="container" style="max-width: 500px;">
  <h2 class="mb-3">Add Playlist</h2>
  <p>Create a new playlist to organize your videos.</p>

  <?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Playlist Title</label>
      <input
        type="text"
        id="title"
        name="title"
        class="form-control"
        placeholder="Enter playlist title"
        required
      />
    </div>

    <div class="form-check mb-3">
      <input
        class="form-check-input"
        type="checkbox"
        id="is_public"
        name="is_public"
        checked
      />
      <label class="form-check-label" for="is_public">Public</label>
    </div>

    <button type="submit" class="btn btn-primary w-100">Add Playlist</button>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const btn = document.getElementById('theme-toggle');
btn.onclick = () => {
  document.body.classList.toggle('dark');
  localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
};
if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');
</script>
</body>
</html>
