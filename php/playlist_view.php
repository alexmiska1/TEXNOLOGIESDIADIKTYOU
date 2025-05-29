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
  die('Playlist ID missing');
}

// Handle video deletion
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video_id'])) {
    $delete_id = $_POST['delete_video_id'];
    
    // Verify video belongs to logged-in user
    $stmt = $db->prepare("
        SELECT v.id FROM videos v
        JOIN playlists p ON v.playlist_id = p.id
        WHERE v.id = ? AND p.user_id = ?
    ");
    $stmt->execute([$delete_id, $user['id']]);
    if ($stmt->fetch()) {
        $stmt = $db->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: playlist_view.php?id=" . urlencode($playlist_id));
        exit();
    } else {
        $error = "You don't have permission to delete this video.";
    }
}

// Check playlist ownership
$stmt = $db->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $user['id']]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playlist) {
  die('Playlist not found or access denied');
}

// Fetch playlist videos
$stmt = $db->prepare("SELECT * FROM videos WHERE playlist_id = ? ORDER BY id DESC");
$stmt->execute([$playlist_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Playlist: <?= htmlspecialchars($playlist['title'], ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="assets/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
        <li class="nav-item"><a class="nav-link" href="add_playlist.php">Add Playlist</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
      <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <h1><?= htmlspecialchars($playlist['title'], ENT_QUOTES, 'UTF-8') ?></h1>
  <a href="add_video.php?playlist_id=<?= htmlspecialchars($playlist_id, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success mb-3">Add Video</a>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (empty($videos)): ?>
    <p>No videos in this playlist yet.</p>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($videos as $video): ?>
        <div class="col">
          <div class="card h-100">
            <iframe class="card-img-top" width="100%" height="180" 
              src="https://www.youtube.com/embed/<?= htmlspecialchars($video['youtube_id'], ENT_QUOTES, 'UTF-8') ?>" 
              frameborder="0" allowfullscreen></iframe>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></h5>
              <form method="post" class="mt-auto" onsubmit="return confirm('Are you sure you want to delete this video?');">
                <input type="hidden" name="delete_video_id" value="<?= htmlspecialchars($video['id']) ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
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
