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

// Fetch testimonials
$stmt = $pdo->query("SELECT * FROM testimonials ORDER BY RAND() LIMIT 3");
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch about us content
$stmt = $pdo->query("SELECT * FROM about_us LIMIT 1");
$aboutUs = $stmt->fetch(PDO::FETCH_ASSOC);

// If no about us content, use default
if (!$aboutUs) {
    $aboutUs = [
        'description' => 'Welcome to Larissa Salon Studio. We are dedicated to providing high-quality beauty services.',
        'image_url' => 'images/default-salon.jpg'
    ];
}
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
            <a href="#featured-services" class="btn btn-primary">Explore Our Services</a>
        </div>
    </section>

    <section class="about-us" id="about" style="padding: 80px 0; background-color: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <div class="about-content" style="display: flex; align-items: center; flex-wrap: wrap; gap: 40px; justify-content: space-between;">
            <div class="about-text" style="flex: 1; min-width: 300px; max-width: 600px;">
                <h2 style="font-size: 32px; color: #333; margin-bottom: 20px; position: relative; padding-bottom: 15px;">
                    About Larissa Salon Studio
                    <span style="content: ''; position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background-color: #b69159;"></span>
                </h2>
                <p style="font-size: 16px; line-height: 1.8; color: #666; margin-bottom: 25px;">
                    <?php echo isset($aboutUs['description']) ? htmlspecialchars($aboutUs['description']) : 'Description not available.'; ?>
                </p>
                <a href="about.php" class="btn btn-secondary" style="display: inline-block; padding: 10px 25px; background-color: #b69159; color: #fff; text-decoration: none; font-weight: 600; border-radius: 5px; transition: all 0.3s ease; font-size: 16px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">Learn More</a>
            </div>
            <div class="about-image" style="flex: 0 0 auto; width: 300px; position: relative;">
                <?php
                $imagePath = isset($aboutUs['image']) ? htmlspecialchars($aboutUs['image']) : 'images/logo3.jpeg';
                ?>
                <img src="<?php echo $imagePath; ?>" alt="Larissa Salon Studio" style="width: 100%; height: 300px; object-fit: cover; border-radius: 10px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                <div style="position: absolute; top: -15px; right: -15px; width: 80px; height: 80px; background-color: #b69159; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #fff; font-size: 12px; font-weight: bold; text-align: center; line-height: 1.2; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    Years of<br>Excellence
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="packages" id="packages">
        <div class="container">
            <h2 data-aos="fade-up">Special Packages</h2>
            <div class="package-grid">
                <?php foreach ($featuredServices as $services): ?>
                    <div class="package-card" data-aos="fade-up">
                        <div class="package-image-container">
                            <img src="images/services/<?php echo $services['id']; ?>.jpeg" alt="<?php echo htmlspecialchars($services['name']); ?>" class="package-image">
                        </div>
                        <div class="package-content">
                            <h3><?php echo htmlspecialchars($services['name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($services['description'], 0, 100)) . '...'; ?></p>
                            <p class="package-price">Rp <?php echo number_format($services['price'], 0, ',', '.'); ?></p>
                            <a href="service_details.php?id=<?php echo $services['id']; ?>" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="services.php#packages" class="btn btn-primary">View All Packages</a>
            </div>
        </div>
    </section>

    <section class="packages" id="packages">
        <div class="container">
            <h2 data-aos="fade-up">Special Packages</h2>
            <div class="package-grid">
                <?php foreach ($specialPackages as $package): ?>
                    <div class="package-card" data-aos="fade-up">
                        <div class="package-image-container">
                            <img src="images/services/<?php echo $package['id']; ?>.jpeg" alt="<?php echo htmlspecialchars($package['name']); ?>" class="package-image">
                        </div>
                        <div class="package-content">
                            <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?></p>
                            <p class="package-price">Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></p>
                            <a href="service_details.php?id=<?php echo $package['id']; ?>" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="services.php#packages" class="btn btn-primary">View All Packages</a>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials" style="padding: 40px 0; background-color: #f8f8f8;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <h2 style="text-align: center; font-size: 28px; margin-bottom: 30px; color: #333;">What Our Clients Say</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php 
            $testimonials = [
                [
                    'id' => 1,
                    'name' => 'Sarah Johnson',
                    'position' => 'Regular Client',
                    'content' => 'Amazing service! The staff is very professional and friendly.',
                    'image' => 'client1.jpg'
                ],
                [
                    'id' => 2,
                    'name' => 'Michael Chen',
                    'position' => 'Loyal Customer',
                    'content' => 'Best salon in town! Always satisfied with their service.',
                    'image' => 'client2.jpg'
                ],
                [
                    'id' => 3,
                    'name' => 'Emily Rodriguez',
                    'position' => 'VIP Member',
                    'content' => 'Excellent attention to detail and great atmosphere.',
                    'image' => 'client3.jpg'
                ]
            ];
            
            foreach ($testimonials as $testimonial): ?>
                <div style="background: white; 
                            border-radius: 8px; 
                            padding: 15px; 
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
                            display: flex; 
                            flex-direction: column; 
                            gap: 10px;">
                    
                    <!-- Profile Section -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="images/testimonials/<?php echo $testimonial['id']; ?>.jpeg" 
                             alt="<?php echo htmlspecialchars($testimonial['name']); ?>"
                             style="width: 40px; 
                                    height: 40px; 
                                    border-radius: 50%; 
                                    object-fit: cover; 
                                    border: 2px solid #b69159;">
                        
                        <div style="display: flex; flex-direction: column;">
                            <h4 style="margin: 0; 
                                       font-size: 14px; 
                                       color: #333; 
                                       font-weight: 600;">
                                <?php echo htmlspecialchars($testimonial['name']); ?>
                            </h4>
                            <span style="font-size: 12px; 
                                       color: #666;">
                                <?php echo htmlspecialchars($testimonial['position']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Testimonial Content -->
                    <div style="position: relative;">
                        <p style="margin: 0; 
                                 font-size: 13px; 
                                 line-height: 1.5; 
                                 color: #555; 
                                 font-style: italic;">
                            "<?php echo htmlspecialchars($testimonial['content']); ?>"
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="location" id="location" style="padding: 60px 0; background-color: #f8f8f8;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <h2 style="text-align: center; font-size: 32px; margin-bottom: 30px; color: #333; font-weight: 600;">Our Location</h2>
        <div class="map-container" style="position: relative; overflow: hidden; padding-top: 56.25%; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.0746921826!2d98.48921491475855!3d3.6145499973669!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312f1a3d8a8a6d%3A0x4a4e8e2c8b4d8b0a!2sJl.%20Teuku%20Umar%20No.53%2C%20Nangka%2C%20Kec.%20Binjai%20Utara%2C%20Kota%20Binjai%2C%20Sumatera%20Utara%2020742!5e0!3m2!1sen!2sid!4v1625147200000!5m2!1sen!2sid" 
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                    allowfullscreen="" 
                    loading="lazy">
            </iframe>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 16px; color: #666;">
                <strong>Address:</strong> Jl. Teuku Umar No.53, Nangka, Kec. Binjai Utara, Kota Binjai, Sumatera Utara 20742
            </p>
        </div>
    </div>
</section>

<section class="cta" id="cta" style="padding: 80px 0; background: linear-gradient(135deg, #b69159, #8c6d3f); color: white; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <div class="cta-content" style="max-width: 800px; margin: 0 auto;">
            <h2 style="font-size: 36px; margin-bottom: 20px; font-weight: 600; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Ready to Transform Your Look?</h2>
            <p style="font-size: 18px; margin-bottom: 30px; line-height: 1.6;">Experience the magic of our expert stylists and indulge in our premium services. Your perfect look awaits!</p>
            <a href="booking.php" class="btn btn-light" style="display: inline-block; padding: 12px 30px; background-color: white; color: #b69159; text-decoration: none; font-weight: 600; border-radius: 50px; transition: all 0.3s ease; font-size: 18px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Book Now</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/main.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>
</body>
</html>

