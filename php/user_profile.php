<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = get_user();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Delete user and cascade delete playlists/videos
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        session_destroy();
        header('Location: index.php');
        exit();
    } elseif (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if ($email) {
            $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$email, $user['id']]);
            $success = 'Email updated!';
        } else {
            $error = 'Email required';
        }
    }
}
$user = get_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
    <button id="theme-toggle" style="margin-left:auto;">🌙/☀️</button>
</nav>
<main>
    <h2>User Profile</h2>
    <form method="post">
        <label>Username: <?= htmlspecialchars($user['username']) ?></label><br>
        <label>Email:<br><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label><br>
        <button type="submit">Update Email</button>
    </form>
    <hr>
    <form method="post" onsubmit="return confirm('Delete your account and all data?');">
        <button type="submit" name="delete" style="background:#d33;color:#fff;">Delete Account</button>
    </form>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
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
