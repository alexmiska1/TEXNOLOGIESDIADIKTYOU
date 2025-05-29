<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!is_logged_in()) {
  http_response_code(401);
  echo json_encode(['message' => 'Unauthorized']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$playlist_id = $data['playlist_id'] ?? null;
$youtube_id = $data['youtube_id'] ?? null;
$title = $data['title'] ?? null;

if (!$playlist_id || !$youtube_id || !$title) {
  http_response_code(400);
  echo json_encode(['message' => 'Missing data']);
  exit;
}

$user = get_user();

// Επιβεβαίωση ιδιοκτησίας playlist
$stmt = $db->prepare("SELECT id FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $user['id']]);
if (!$stmt->fetch()) {
  http_response_code(403);
  echo json_encode(['message' => 'Forbidden']);
  exit;
}

$stmt = $db->prepare("INSERT INTO videos (playlist_id, youtube_id, title) VALUES (?, ?, ?)");
$stmt->execute([$playlist_id, $youtube_id, $title]);

echo json_encode(['message' => 'Video added successfully']);
