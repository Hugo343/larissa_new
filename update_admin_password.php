<?php
require_once 'config.php';

$admin_username = 'adminhugo';
$new_password = 'hugo123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->execute([$hashed_password, $admin_username]);

echo "Password admin telah diupdate.";
?>