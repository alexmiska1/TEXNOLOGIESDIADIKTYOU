<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - PlaylistApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">PlaylistApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="playlists.php">Playlists</a></li>
        <li class="nav-item"><a class="nav-link" href="add_playlist.php">Add Playlist</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
      <button id="theme-toggle" class="btn btn-outline-light">🌙/☀️</button>
    </div>
  </div>
</nav>
<!-- Google Sign-In Button -->
<div id="g_id_onload"
     data-client_id="962879946280-7vb2d3mr1lu4ds652semkb1ctp44ji6i.apps.googleusercontent.com"
     data-callback="handleCredentialResponse">
    
</div>
<div class="g_id_signin"
     data-type="standard"
     data-shape="rectangular"
     data-theme="outline"
     data-text="sign_in_with"
     data-size="large"
     data-logo_alignment="left">
</div>
<main class="container py-4">
  <h2 class="mb-4">Search YouTube Videos</h2>
  <form id="search-form" class="mb-4">
    <div class="input-group">
      <input type="text" id="search-input" class="form-control" placeholder="Type something to search..." required />
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
  <div id="results" class="row"></div>
</main>

<script>
const apiKey = 'AIzaSyDdfhZNQVWUqT90vYLUf_xySfIgAgkU0EI'; // <-- Βάλε το σωστό API key!
const form = document.getElementById('search-form');
const input = document.getElementById('search-input');
const resultsDiv = document.getElementById('results');

form.onsubmit = async function(e) {
  e.preventDefault();
  const query = input.value.trim();
  if (!query) return;

  resultsDiv.innerHTML = "<p>Searching...</p>";
  const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=9&q=${encodeURIComponent(query)}&key=${apiKey}`;
  const res = await fetch(url);
  const data = await res.json();
  
  let html = '';
  if (data.items) {
    data.items.forEach(item => {
      html += `
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="${item.snippet.thumbnails.medium.url}" alt="">
          <div class="card-body">
            <h5 class="card-title">${item.snippet.title}</h5>
            <a class="btn btn-primary btn-sm" href="https://youtube.com/watch?v=${item.id.videoId}" target="_blank">Watch</a>
          </div>
        </div>
      </div>`;
    });
  } else {
    html = '<p>No results or API error.</p>';
  }
  resultsDiv.innerHTML = html;
};

// Theme toggle (dark/light)
const btn = document.getElementById('theme-toggle');
btn.onclick = () => {
  document.body.classList.toggle('dark');
  localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
};
if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function handleCredentialResponse(response) {
    const data = response.credential;

    const payload = JSON.parse(atob(data.split('.')[1]));
    alert("Hello, " + payload.email + "! (Google login successful)");
}
</script>
</body>

</html>