<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!is_logged_in()) {
  http_response_code(401);
  echo json_encode([]);
  exit;
}

$user = get_user();
$stmt = $db->prepare("SELECT id, title FROM playlists WHERE user_id = ?");
$stmt->execute([$user['id']]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($playlists);
