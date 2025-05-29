<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();

$id = $_GET['id'] ?? null;
$stmt = $db->prepare("DELETE FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user['id']]);
header('Location: playlists.php');
exit();
