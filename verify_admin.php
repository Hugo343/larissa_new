<?php
require_once 'config.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Admin user found:\n";
        echo "Username: " . $user['username'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
        echo "Hashed Password: " . $user['password'] . "\n";
    } else {
        echo "Admin user not found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}