<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (!$username || !$email || !$password || !$password_confirm) {
        $_SESSION['register_error'] = 'Please fill all fields';
        header('Location: register.php');
        exit();
    }
    if ($password !== $password_confirm) {
        $_SESSION['register_error'] = 'Passwords do not match';
        header('Location: register.php');
        exit();
    }
    // Check for existing email
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['register_error'] = 'Email already registered';
        header('Location: register.php');
        exit();
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Save to DB
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$username, $email, $passwordHash]);

    header('Location: login.php');
    exit();
} else {
    header('Location: register.php');
    exit();
}
?>