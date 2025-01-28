<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT a.*, s.name as service_name FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.user_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-header" style="background-image: url('images/dash-1.png');">
        <div class="container">
            <h1>My Dashboard</h1>
        </div>
    </section>

    <section class="dashboard">
        <div class="container">
            <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
            <div class="dashboard-content">
                <div class="user-info">
                    <h3>Your Information</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <a href="edit-profile.php" class="btn">Edit Profile</a>
                </div>
                <div class="appointments">
                    <h3>Your Appointments</h3>
                    <?php if (empty($appointments)): ?>
                        <p>You have no upcoming appointments.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                        <td class="status-<?php echo strtolower($appointment['status']); ?>">
                                            <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add any dashboard-specific JavaScript here
        });
    </script>
</body>
</html>