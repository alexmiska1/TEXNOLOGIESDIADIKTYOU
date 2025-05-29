<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();
$playlist_id = $_GET['playlist_id'] ?? null;

$stmt = $db->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $user['id']]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$playlist) {
    die('Playlist not found or permission denied');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $youtube_id = trim($_POST['youtube_id'] ?? '');
    $title = trim($_POST['title'] ?? '');
    if ($youtube_id && $title) {
        $stmt = $db->prepare("INSERT INTO videos (playlist_id, youtube_id, title) VALUES (?, ?, ?)");
        $stmt->execute([$playlist_id, $youtube_id, $title]);
        header('Location: playlist_view.php?id=' . $playlist_id);
        exit();
    } else {
        $error = 'All fields are required';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Video to <?= htmlspecialchars($playlist['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
  <a class="navbar-brand" href="index.php">PlaylistApp</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto">
      <li class="nav-item"><a class="nav-link" href="playlists.php">My Playlists</a></li>
      <li class="nav-item"><a class="nav-link" href="playlist_view.php?id=<?= $playlist_id ?>">Back to Playlist</a></li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
  <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
</nav>

<main class="container mt-4">
    <h2>Add Video to <?= htmlspecialchars($playlist['title']) ?></h2>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <input type="hidden" name="youtube_id" id="youtube_id" />
        <input type="hidden" name="title" id="title" />
        <button type="submit" class="btn btn-primary">Add Video</button>
    </form>

    <div>
        <input
          type="text"
          id="search-query"
          placeholder="Search YouTube videos..."
          class="form-control mb-3"
        />
        <button id="search-btn" class="btn btn-primary mb-3">Search</button>
    </div>

    <div id="youtube-results" class="row row-cols-1 row-cols-md-2 g-3"></div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const btn = document.getElementById('theme-toggle');
btn.onclick = () => {
  document.body.classList.toggle('dark');
  localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
};
if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');

const apiKey = 'AIzaSyDdfhZNQVWUqT90vYLUf_xySfIgAgkU0EI'; // Βάλε εδώ το API key σου

document.getElementById('search-btn').addEventListener('click', async () => {
  const query = document.getElementById('search-query').value.trim();
  if (!query) return alert('Please enter a search term');

  const res = await fetch(
    `https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=10&q=${encodeURIComponent(query)}&key=${apiKey}`
  );
  const data = await res.json();
  const resultsDiv = document.getElementById('youtube-results');
  resultsDiv.innerHTML = '';

  if (!data.items || data.items.length === 0) {
    resultsDiv.innerHTML = '<p>No results found.</p>';
    return;
  }

  data.items.forEach(item => {
    const videoId = item.id.videoId;
    const title = item.snippet.title;

    const col = document.createElement('div');
    col.className = 'col';

    const card = document.createElement('div');
    card.className = 'card h-100';

    const thumbnail = document.createElement('img');
    thumbnail.src = item.snippet.thumbnails.medium.url;
    thumbnail.className = 'card-img-top';
    thumbnail.alt = title;

    const cardBody = document.createElement('div');
    cardBody.className = 'card-body d-flex flex-column';

    const cardTitle = document.createElement('h5');
    cardTitle.className = 'card-title';
    cardTitle.textContent = title;

    const addButton = document.createElement('button');
    addButton.textContent = 'Add to Playlist';
    addButton.className = 'btn btn-success mt-auto';
    addButton.addEventListener('click', () => {
      document.getElementById('youtube_id').value = videoId;
      document.getElementById('title').value = title;
      document.forms[0].submit();
    });

    cardBody.appendChild(cardTitle);
    cardBody.appendChild(addButton);
    card.appendChild(thumbnail);
    card.appendChild(cardBody);
    col.appendChild(card);
    resultsDiv.appendChild(col);
  });
});
</script>
</body>
</html>
