<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();

$playlist_id = $_GET['id'] ?? null;
if (!$playlist_id) {
    die('Playlist ID is required');
}

// Πάρε playlist
$stmt = $db->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $user['id']]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$playlist) {
    die('Playlist not found or permission denied');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    if ($title !== '') {
        $stmt = $db->prepare("UPDATE playlists SET title = ?, is_public = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $is_public, $playlist_id, $user['id']]);
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
  <title>Edit Playlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">PlaylistApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="playlists.php">My Playlists</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
      <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="mb-4">Edit Playlist</h2>

  <?php if ($error): ?>
  <div class="alert alert-danger" role="alert">
    <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="post" class="w-100" style="max-width: 500px;">
    <div class="mb-3">
      <label for="title" class="form-label">Title:</label>
      <input 
        type="text" 
        class="form-control" 
        id="title" 
        name="title" 
        value="<?= htmlspecialchars($playlist['title']) ?>" 
        required>
    </div>

    <div class="form-check mb-3">
      <input 
        type="checkbox" 
        class="form-check-input" 
        id="is_public" 
        name="is_public" 
        <?= $playlist['is_public'] ? 'checked' : '' ?>>
      <label class="form-check-label" for="is_public">Public</label>
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>
  </form>
</div>

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
