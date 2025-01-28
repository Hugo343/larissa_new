<?php
session_start();
require_once 'config.php';
require_once 'email_notification.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user's appointments
$stmt = $pdo->prepare("SELECT a.*, s.name as service_name 
                       FROM appointments a 
                       JOIN services s ON a.service_id = s.id 
                       WHERE a.user_id = ? 
                       ORDER BY a.appointment_date DESC");
$stmt->execute([$userId]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle appointment cancellation
if (isset($_POST['cancel_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$appointmentId, $userId])) {
        // Fetch appointment details for email
        $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, u.email, u.full_name 
                               FROM appointments a 
                               JOIN services s ON a.service_id = s.id 
                               JOIN users u ON a.user_id = u.id 
                               WHERE a.id = ?");
        $stmt->execute([$appointmentId]);
        $appointmentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send cancellation email
        $emailDetails = [
            'customer_name' => $appointmentDetails['full_name'],
            'service_name' => $appointmentDetails['service_name'],
            'date' => date('Y-m-d', strtotime($appointmentDetails['appointment_date'])),
            'time' => date('H:i', strtotime($appointmentDetails['appointment_date']))
        ];
        sendAppointmentCancellation($appointmentDetails['email'], $emailDetails);

        $successMessage = "Appointment cancelled successfully.";
    } else {
        $errorMessage = "Failed to cancel appointment. Please try again.";
    }
}

// Handle appointment rescheduling
if (isset($_POST['reschedule_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $newDate = $_POST['new_date'];
    $newTime = $_POST['new_time'];

    $newDateTime = date('Y-m-d H:i:s', strtotime("$newDate $newTime"));

    $stmt = $pdo->prepare("UPDATE appointments SET appointment_date = ? WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$newDateTime, $appointmentId, $userId])) {
        // Fetch appointment details for email
        $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, u.email, u.full_name 
                               FROM appointments a 
                               JOIN services s ON a.service_id = s.id 
                               JOIN users u ON a.user_id = u.id 
                               WHERE a.id = ?");
        $stmt->execute([$appointmentId]);
        $appointmentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send rescheduling confirmation email
        $emailDetails = [
            'customer_name' => $appointmentDetails['full_name'],
            'service_name' => $appointmentDetails['service_name'],
            'date' => $newDate,
            'time' => $newTime
        ];
        sendAppointmentConfirmation($appointmentDetails['email'], $emailDetails);

        $successMessage = "Appointment rescheduled successfully.";
    } else {
        $errorMessage = "Failed to reschedule appointment. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="container">
        <h1>Manage Your Appointments</h1>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="appointments-list">
            <?php foreach ($appointments as $appointment): ?>
                <div class="appointment-card">
                    <h2><?php echo htmlspecialchars($appointment['service_name']); ?></h2>
                    <p>Date: <?php echo date('Y-m-d', strtotime($appointment['appointment_date'])); ?></p>
                    <p>Time: <?php echo date('H:i', strtotime($appointment['appointment_date'])); ?></p>
                    <p>Status: <?php echo ucfirst($appointment['status']); ?></p>

                    <?php if ($appointment['status'] == 'scheduled'): ?>
                        <form method="post" class="cancel-form">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                            <button type="submit" name="cancel_appointment" class="btn btn-danger">Cancel Appointment</button>
                        </form>
                        
                        <form method="post" class="reschedule-form">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                            <input type="date" name="new_date" required>
                            <input type="time" name="new_time" required>
                            <button type="submit" name="reschedule_appointment" class="btn btn-primary">Reschedule</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/appointment-management.js"></script>
</body>
</html>

