<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($customer_id > 0) {
    // First, delete all appointments associated with this customer
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE user_id = ?");
    $stmt->execute([$customer_id]);

    // Then, delete the customer
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND is_admin = 0");
    if ($stmt->execute([$customer_id])) {
        header('Location: admin.php?section=customers&message=Customer and associated appointments deleted successfully');
    } else {
        header('Location: admin.php?section=customers&error=Failed to delete customer');
    }
} else {
    header('Location: admin.php?section=customers&error=Invalid customer ID');
}
exit();

