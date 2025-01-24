<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: services.php');
    exit();
}

$categoryId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header('Location: services.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE category_id = ?");
$stmt->execute([$categoryId]);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-header" style="background-image: url('https://source.unsplash.com/1600x900/?beauty,salon,<?php echo urlencode($category['name']); ?>');">
        <div class="container">
            <h1><?php echo htmlspecialchars($category['name']); ?></h1>
        </div>
    </section>

    <section class="category-services">
        <div class="container">
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card" data-aos="fade-up">
                        <img src="https://source.unsplash.com/400x300/?beauty,salon,<?php echo urlencode($service['name']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" class="service-image">
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></p>
                        <p><strong>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></strong></p>
                        <a href="service_details.php?id=<?php echo $service['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>