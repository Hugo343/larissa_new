<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: services.php');
    exit();
}

$serviceId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header('Location: services.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service['name']); ?> - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1><?php echo htmlspecialchars($service['name']); ?></h1>
        </div>
    </section>

    <section class="service-details">
        <div class="container">
            <div class="service-content" data-aos="fade-up">
                <img src="images/services/<?php echo $service['id']; ?>.jpeg" alt="<?php echo htmlspecialchars($service['name']); ?>" class="service-image">
                <div class="service-info">
                    <h2><?php echo htmlspecialchars($service['name']); ?></h2>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <p><strong>Price: Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></strong></p>
                    <p><strong>Duration: <?php echo $service['duration']; ?> minutes</strong></p>
                    <a href="booking.php?service_id=<?php echo $service['id']; ?>" class="btn">Book Now</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

