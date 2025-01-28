<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE appointments SET user_id = ?, service_id = ?, appointment_date = ?, appointment_time = ?, status = ? WHERE id = ?");
    if ($stmt->execute([$user_id, $service_id, $appointment_date, $appointment_time, $status, $appointment_id])) {
        header('Location: admin.php?section=appointments');
        exit();
    }
}

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, username FROM users WHERE is_admin = 0");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, name FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Edit Appointment</h1>
        <form action="edit-appointment.php?id=<?php echo $appointment_id; ?>" method="POST">
            <div class="form-group">
                <label for="user_id">Customer:</label>
                <select name="user_id" id="user_id" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo $user['id'] == $appointment['user_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="service_id">Service:</label>
                <select name="service_id" id="service_id" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>" <?php echo $service['id'] == $appointment['service_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($service['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date">Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" value="<?php echo date('Y-m-d', strtotime($appointment['appointment_date'])); ?>" required>
            </div>
            <div class="form-group">
                <label for="appointment_time">Time:</label>
                <input type="time" name="appointment_time" id="appointment_time" value="<?php echo date('H:i', strtotime($appointment['appointment_date'])); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="pending" <?php echo $appointment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo $appointment['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="cancelled" <?php echo $appointment['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
        </form>
    </div>
</body>
</html>

