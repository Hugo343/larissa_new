<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($service_id > 0) {
    // First, check if there are any appointments using this service
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $appointment_count = $stmt->fetchColumn();

    if ($appointment_count > 0) {
        header('Location: admin.php?section=services&error=Cannot delete service. It is associated with existing appointments.');
    } else {
        // If no appointments are using this service, proceed with deletion
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        if ($stmt->execute([$service_id])) {
            header('Location: admin.php?section=services&message=Service deleted successfully');
        } else {
            header('Location: admin.php?section=services&error=Failed to delete service');
        }
    }
} else {
    header('Location: admin.php?section=services&error=Invalid service ID');
}
exit();

