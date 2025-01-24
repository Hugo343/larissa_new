<?php
require_once 'config.php';

if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];
    
    $stmt = $pdo->prepare("SELECT * FROM services WHERE category_id = ?");
    $stmt->execute([$categoryId]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '<h2>Services</h2>';
    $output .= '<div class="services-grid">';
    foreach ($services as $service) {
        $output .= '<div class="service-card fade-in">';
        $output .= '<h3>' . htmlspecialchars($service['name']) . '</h3>';
        $output .= '<p>' . htmlspecialchars(substr($service['description'], 0, 100)) . '...</p>';
        $output .= '<p><strong>Price: Rp ' . number_format($service['price'], 0, ',', '.') . '</strong></p>';
        $output .= '<p><i class="fas fa-clock"></i> Duration: ' . $service['duration'] . ' minutes</p>';
        $output .= '<a href="#" class="btn view-service" data-service="' . $service['id'] . '">View Details</a>';
        $output .= '</div>';
    }
    $output .= '</div>';

    echo $output;
}

