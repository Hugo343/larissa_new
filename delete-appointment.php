<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    if ($stmt->execute([$appointment_id])) {
        header('Location: admin.php?section=appointments&message=Appointment deleted successfully');
    } else {
        header('Location: admin.php?section=appointments&error=Failed to delete appointment');
    }
} else {
    header('Location: admin.php?section=appointments&error=Invalid appointment ID');
}
exit();

