<?php
session_start();
require_once 'config.php';

// Fetch all categories and services
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group services by category
$servicesByCategory = [];
foreach ($services as $service) {
    $categoryId = $service['category_id'];
    if (!isset($servicesByCategory[$categoryId])) {
        $servicesByCategory[$categoryId] = [];
    }
    $servicesByCategory[$categoryId][] = $service;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="page-header" style="background-image: url('images/services-header.jpg');">
        <div class="container">
            <h1>Our Services</h1>
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <?php foreach ($categories as $category): ?>
                <div class="category-section" data-aos="fade-up">
                    <h2><?php echo htmlspecialchars($category['name']); ?></h2>
                    <div class="services-grid">
                        <?php if (isset($servicesByCategory[$category['id']])): ?>
                            <?php foreach (array_slice($servicesByCategory[$category['id']], 0, 3) as $service): ?>
                                <div class="service-card">
                                    <img src="images/services/<?php echo $service['id']; ?>.jpeg" alt="<?php echo htmlspecialchars($service['name']); ?>" class="service-image">
                                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                                    <p><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></p>
                                    <p><strong>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></strong></p>
                                    <a href="service_details.php?id=<?php echo $service['id']; ?>" class="btn">View Details</a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="text-center">
                        <a href="category_details.php?id=<?php echo $category['id']; ?>" class="btn btn-outline">View All <?php echo htmlspecialchars($category['name']); ?> Services</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

