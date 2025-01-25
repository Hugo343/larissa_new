<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_GET['service_id'])) {
    $serviceId = $_GET['service_id'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['service_id'];
    $bookingDate = $_POSpT['booking_date'];
    $bookingTime = $_POST['booking_time'];

    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$userId, $serviceId, $bookingDate, $bookingTime]);

    if ($result) {
        $successMessage = "Booking successful! We'll see you on " . $bookingDate . " at " . $bookingTime . ".";
    } else {
        $errorMessage = "There was an error processing your booking. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1>Book an Appointment</h1>
        </div>
    </section>

    <section class="booking-form">
        <div class="container">
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success" data-aos="fade-up"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error" data-aos="fade-up"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form action="booking.php" method="POST" data-aos="fade-up">
                <?php if (isset($service)): ?>
                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                    <div class="form-group">
                        <label for="service">Selected Service:</label>
                        <input type="text" id="service" value="<?php echo htmlspecialchars($service['name']); ?>" readonly>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="service_id">Select Service:</label>
                        <select name="service_id" id="service_id" required>
                            <option value="">Choose a service</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service['id']; ?>"><?php echo htmlspecialchars($service['name']); ?> - Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="booking_date">Date:</label>
                    <input type="date" id="booking_date" name="booking_date" required>
                </div>
                <div class="form-group">
                    <label for="booking_time">Time:</label>
                    <input type="time" id="booking_time" name="booking_time" required>
                </div>
                <button type="submit" class="btn">Book Appointment</button>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>