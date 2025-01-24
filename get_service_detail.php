<?php
require_once 'config.php';

if (isset($_GET['service_id'])) {
    $serviceId = $_GET['service_id'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        echo "<h2>" . htmlspecialchars($service['name']) . "</h2>";
        echo "<p>" . htmlspecialchars($service['description']) . "</p>";
        echo "<p>Price: Rp " . number_format($service['price'], 0, ',', '.') . "</p>";
        echo "<p>Duration: " . $service['duration'] . " minutes</p>";

        // Add booking form
        echo "<h3>Book this service</h3>";
        echo "<form id='booking-form'>";
        echo "<input type='hidden' name='service_id' value='" . $service['id'] . "'>";
        echo "<label for='booking_date'>Date:</label>";
        echo "<input type='date' id='booking_date' name='booking_date' required>";
        echo "<label for='booking_time'>Time:</label>";
        echo "<input type='time' id='booking_time' name='booking_time' required>";
        echo "<button type='submit' class='btn'>Book Now</button>";
        echo "</form>";
    } else {
        echo "<p>Service not found.</p>";
    }
}