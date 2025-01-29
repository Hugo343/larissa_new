<?php
session_start();
require_once 'config.php';
require_once 'email_notification.php'; // Updated file name

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php'); // Updated login page
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['service_id'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    // Fetch the service price
    $stmt = $pdo->prepare("SELECT price FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);
    $servicePrice = $stmt->fetchColumn();

    $appointmentDateTime = $appointmentDate . ' ' . $appointmentTime;

    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time, price, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    if ($stmt->execute([$userId, $serviceId, $appointmentDate, $appointmentTime, $servicePrice])) {
        
        $appointmentId = $pdo->lastInsertId();

        // Fetch appointment details for email
        $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, u.email, u.full_name 
                               FROM appointments a 
                               JOIN services s ON a.service_id = s.id 
                               JOIN users u ON a.user_id = u.id 
                               WHERE a.id = ?");
        $stmt->execute([$appointmentId]);
        $appointmentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send confirmation email
        $emailDetails = [
            'customer_name' => $appointmentDetails['full_name'],
            'service_name' => $appointmentDetails['service_name'],
            'date' => $appointmentDate,
            'time' => $appointmentTime
        ];
        sendAppointmentConfirmation($appointmentDetails['email'], $emailDetails);

        // Redirect to dashboard instead of appointment_management.php
        header('Location: dashboard.php?booking_success=1'); // Updated redirect
        exit();
    }
}

$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5e6d3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('images/salon-background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .booking-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 90%;
            max-width: 600px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .booking-container::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            right: -50px;
            bottom: -50px;
            background: linear-gradient(45deg, rgba(182, 145, 89, 0.1), rgba(182, 145, 89, 0.05));
            transform: rotate(5deg);
            z-index: -1;
        }

        h1 {
            color: #b69159;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group {
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #5d4431;
            font-weight: 500;
        }

        select, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #d4c39d;
            border-radius: 10px;
            font-size: 16px;
            color: #5d4431;
            background-color: #faf8f2;
            transition: all 0.3s ease;
        }

        select:focus, input[type="date"]:focus, input[type="time"]:focus {
            outline: none;
            border-color: #b69159;
            box-shadow: 0 0 10px rgba(182, 145, 89, 0.2);
        }

        button {
            background-color: #b69159;
            color: #ffffff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #a87e4e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(168, 126, 78, 0.3);
        }

        .decoration {
            position: absolute;
            font-size: 100px;
            color: rgba(182, 145, 89, 0.1);
            z-index: -1;
        }

        .decoration-1 {
            top: 20px;
            left: 20px;
            transform: rotate(-15deg);
        }

        .decoration-2 {
            bottom: 20px;
            right: 20px;
            transform: rotate(15deg);
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .nav-button {
            background-color: #5d4431;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .nav-button:hover {
            background-color: #7c5a40;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(93, 68, 49, 0.3);
        }

        .nav-button i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .booking-container {
                width: 95%;
                padding: 30px;
            }

            h1 {
                font-size: 2em;
            }

            .decoration {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="decoration decoration-1"><i class="fas fa-spa"></i></div>
    <div class="decoration decoration-2"><i class="fas fa-pump-soap"></i></div>

    <div class="booking-container">
        <h1>Book Your Appointment</h1>
        <form action="booking.php" method="POST">
            <div class="form-group">
                <label for="service_id">Select Service:</label>
                <select name="service_id" id="service_id" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['name']); ?> - Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date">Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" required>
            </div>
            <div class="form-group">
                <label for="appointment_time">Time:</label>
                <input type="time" name="appointment_time" id="appointment_time" required>
            </div>
            <button type="submit">Book Now</button>
        </form>
        <div class="navigation">
            <a href="index.php" class="nav-button"><i class="fas fa-home"></i> Home</a>
            <a href="services.php" class="nav-button"><i class="fas fa-list"></i> Services</a>
        </div>
    </div>

    <script>
       const today = new Date();
const todayString = today.toISOString().split('T')[0];
document.getElementById('appointment_date').setAttribute('min', todayString);

function updateMinTime() {
    if (appointmentDate.value === todayString) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        appointmentTime.setAttribute('min', `${hours}:${minutes}`);
    } else {
        appointmentTime.removeAttribute('min');
    }
}

appointmentDate.addEventListener('change', updateMinTime);
updateMinTime(); // Call initially to set the correct state
    </script>
</body>
</html>

