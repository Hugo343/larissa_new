<?php
session_start();
require_once 'config.php';

// Fetch categories for the navigation menu
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch featured services (limit to 3)
$stmt = $pdo->query("SELECT * FROM services ORDER BY RAND() LIMIT 3");
$featuredServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch special packages
$stmt = $pdo->query("SELECT * FROM services WHERE name LIKE '%paket%' LIMIT 3");
$specialPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larissa Salon Studio - Salon Kecantikan & Make Up Artist</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="hero" id="home">
        <div class="hero-content" data-aos="fade-up">
            <h1>Larissa Salon Studio</h1>
            <p>Salon Kecantikan & Make Up Artist (MUA) di Kota Binjai</p>
            <p>Buka Setiap Hari: 10.00 - 19.00 WIB</p>
            <a href="services.php" class="btn">Explore Our Services</a>
        </div>
    </section>

    <section class="featured-services" id="featured-services">
        <div class="container">
            <h2 data-aos="fade-up">Featured Services</h2>
            <div class="services-grid">
                <?php foreach ($featuredServices as $service): ?>
                    <div class="service-card" data-aos="fade-up">
                        <img src="https://source.unsplash.com/400x300/?beauty,salon,<?php echo urlencode($service['name']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" class="service-image">
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></p>
                        <p><strong>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></strong></p>
                        <a href="service_details.php?id=<?php echo $service['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="services.php" class="btn btn-outline">View All Services</a>
            </div>
        </div>
    </section>

    <section class="packages" id="packages">
        <div class="container">
            <h2 data-aos="fade-up">Special Packages</h2>
            <div class="package-grid">
                <?php foreach ($specialPackages as $package): ?>
                    <div class="package-card" data-aos="fade-up">
                        <img src="https://source.unsplash.com/400x300/?beauty,package,<?php echo urlencode($package['name']); ?>" alt="<?php echo htmlspecialchars($package['name']); ?>" class="package-image">
                        <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?></p>
                        <p><strong>Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></strong></p>
                        <a href="service_details.php?id=<?php echo $package['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="services.php#packages" class="btn btn-outline">View All Packages</a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>