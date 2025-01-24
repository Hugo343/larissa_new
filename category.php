<?php
session_start();
require_once 'config.php';
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$categoryId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header('Location: index.php');
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
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <!-- Navigation content -->
    </nav>

    <section class="category-header">
        <h1 data-aos="fade-up"><?php echo htmlspecialchars($category['name']); ?></h1>
    </section>

    <section class="services">
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card" data-aos="fade-up">
                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <p><strong>Price: Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></strong></p>
                    <p><i class="fas fa-clock"></i> Duration: <?php echo $service['duration']; ?> minutes</p>
                    <a href="book.php?service_id=<?php echo $service['id']; ?>" class="btn btn-primary">Book Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        <!-- Footer content -->
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>

