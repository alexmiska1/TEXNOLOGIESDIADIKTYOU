<?php
session_start();
require_once 'includes/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    header('Location: dashboard.php');
    exit();
} else {
    $_SESSION['login_error'] = 'Invalid email or password';
    header('Location: login.php');
    exit();
}
?>