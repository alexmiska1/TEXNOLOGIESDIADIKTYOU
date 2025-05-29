<?php
session_start();
require_once 'includes/db.php';

$query = trim($_GET['q'] ?? '');
$playlists = [];
if ($query !== '') {
    $stmt = $db->prepare("SELECT * FROM playlists WHERE title LIKE ? AND is_public = 1");
    $stmt->execute(['%' . $query . '%']);
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Playlists</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href="playlists.php">My Playlists</a>
    <a href="logout.php">Logout</a>
    <button id="theme-toggle" style="margin-left:auto;">🌙/☀️</button>
</nav>
<main>
    <h2>Search Public Playlists</h2>
    <form method="get">
        <input type="text" name="q" placeholder="Search playlists..." value="<?= htmlspecialchars($query) ?>">
        <button type="submit">Search</button>
    </form>
    <?php if ($query !== ''): ?>
        <ul>
            <?php foreach ($playlists as $playlist): ?>
                <li><a href="playlist_view.php?id=<?= $playlist['id'] ?>"><?= htmlspecialchars($playlist['title']) ?></a></li>
            <?php endforeach; ?>
            <?php if (empty($playlists)): ?>
                <li>No results found.</li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</main>
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
