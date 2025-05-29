<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();

// Διαγραφή playlist αν έχει ζητηθεί
if (isset($_GET['delete'])) {
    $playlist_id = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM playlists WHERE id = ? AND user_id = ?");
    $stmt->execute([$playlist_id, $user['id']]);
    header('Location: playlists.php');
    exit();
}

// Παίρνουμε τις λίστες του χρήστη
$stmt = $db->prepare("SELECT * FROM playlists WHERE user_id = ?");
$stmt->execute([$user['id']]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
// ... ο προηγούμενος κώδικας ...

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Playlists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
  <a class="navbar-brand" href="index.php">PlaylistApp</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="add_playlist.php">Add Playlist</a></li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
  <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
</nav>

<main class="container mt-4">
<h2>My Playlists</h2>
<a href="add_playlist.php" class="btn btn-primary mb-3">Add New Playlist</a>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Title</th>
      <th>Visibility</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($playlists as $playlist): ?>
    <tr>
      <td><?= htmlspecialchars($playlist['title']) ?></td>
      <td><?= $playlist['is_public'] ? 'Public' : 'Private' ?></td>
      <td>
        <a href="playlist_view.php?id=<?= $playlist['id'] ?>" class="btn btn-info btn-sm me-1">View Playlist</a>
        <a href="edit_playlist.php?id=<?= $playlist['id'] ?>" class="btn btn-warning btn-sm me-1">Edit</a>
        <a href="playlists.php?delete=<?= $playlist['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this playlist?');">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
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
