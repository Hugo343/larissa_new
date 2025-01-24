<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "Please log in to book a service.";
        exit;
    }

    $userId = $_SESSION['user_id'];
    $serviceId = $_POST['service_id'];
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];

    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$userId, $serviceId, $bookingDate, $bookingTime]);

    if ($result) {
        echo "Booking successful! We'll see you on " . $bookingDate . " at " . $bookingTime . ".";
    } else {
        echo "There was an error processing your booking. Please try again.";
    }
}