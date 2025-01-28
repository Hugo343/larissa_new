<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT a.*, s.name as service_name FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.user_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle appointment cancellation
if (isset($_POST['cancel_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$appointmentId, $_SESSION['user_id']])) {
        $successMessage = "Appointment cancelled successfully.";
        // Refresh appointments list
        $stmt = $pdo->prepare("SELECT a.*, s.name as service_name FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.user_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorMessage = "Failed to cancel appointment. Please try again.";
    }
}

// Handle appointment rescheduling
if (isset($_POST['reschedule_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $newDate = $_POST['new_date'];
    $newTime = $_POST['new_time'];

    $stmt = $pdo->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ? WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$newDate, $newTime, $appointmentId, $_SESSION['user_id']])) {
        $successMessage = "Appointment rescheduled successfully.";
        // Refresh appointments list
        $stmt = $pdo->prepare("SELECT a.*, s.name as service_name FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.user_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorMessage = "Failed to reschedule appointment. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background-image: url('images/dash-1.png');
            background-size: cover;
            background-position: center;
            color: #fff;
            text-align: center;
            padding: 100px 0 60px; /* Increased top padding to accommodate fixed navbar */
            margin-top: 60px; /* Added margin-top to push content below fixed navbar */
        }

        h1, h2, h3 {
            color: #b69159;
        }

        .dashboard-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .user-info, .appointments {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            flex: 1;
            min-width: 300px;
        }

        .btn {
            display: inline-block;
            background-color: #b69159;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #a87e4e;
        }

        .btn-cancel {
            background-color: #dc3545;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .status-pending {
            color: #ffa500;
        }

        .status-confirmed {
            color: #008000;
        }

        .status-cancelled {
            color: #ff0000;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .dashboard-content {
                flex-direction: column;
            }

            .user-info, .appointments {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1>My Dashboard</h1>
        </div>
    </section>

    <section class="dashboard">
        <div class="container">
            <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($appointment['appointment_date'])); ?></td>
                                        <td><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?></td>
                                        <td class="status-<?php echo strtolower($appointment['status']); ?>">
                                            <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                                        </td>
                                        <td>
                                            <?php if ($appointment['status'] == 'pending' || $appointment['status'] == 'confirmed'): ?>
                                                <button onclick="openRescheduleModal(<?php echo $appointment['id']; ?>, '<?php echo $appointment['appointment_date']; ?>', '<?php echo $appointment['appointment_time']; ?>')" class="btn">Reschedule</button>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <button type="submit" name="cancel_appointment" class="btn btn-cancel" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
                                                </form>
                                            <?php endif; ?>
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

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reschedule Appointment</h2>
            <form method="post">
                <input type="hidden" name="appointment_id" id="reschedule_appointment_id">
                <div class="form-group">
                    <label for="new_date">New Date:</label>
                    <input type="date" id="new_date" name="new_date" required>
                </div>
                <div class="form-group">
                    <label for="new_time">New Time:</label>
                    <input type="time" id="new_time" name="new_time" required>
                </div>
                <button type="submit" name="reschedule_appointment" class="btn">Reschedule</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Get the modal
        var modal = document.getElementById("rescheduleModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Function to open the reschedule modal
        function openRescheduleModal(appointmentId, currentDate, currentTime) {
            document.getElementById("reschedule_appointment_id").value = appointmentId;
            document.getElementById("new_date").value = currentDate;
            document.getElementById("new_time").value = currentTime;
            modal.style.display = "block";
        }

        // Set minimum date to today for the date input
        var today = new Date().toISOString().split('T')[0];
        document.getElementById("new_date").setAttribute('min', today);

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.getElementsByClassName('alert');
            for (var i = 0; i < alerts.length; i++) {
                alerts[i].style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>

