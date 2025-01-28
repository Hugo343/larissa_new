<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: auth.php');
    exit();
}

// Fetch various statistics
$stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
$totalAppointments = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE is_admin = 0");
$totalCustomers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM services");
$totalServices = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(price) FROM appointments WHERE status = 'confirmed'"); // Updated: SUM(price) instead of SUM(total_revenue)
$totalRevenue = $stmt->fetchColumn();

// Fetch recent appointments
$stmt = $pdo->query("SELECT a.*, u.username, s.name as service_name 
                   FROM appointments a 
                   JOIN users u ON a.user_id = u.id 
                   JOIN services s ON a.service_id = s.id 
                   ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 5"); // Updated: added ORDER BY appointment_time
$recentAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch top services
$stmt = $pdo->query("SELECT s.name, COUNT(*) as count 
                   FROM appointments a 
                   JOIN services s ON a.service_id = s.id 
                   GROUP BY s.id 
                   ORDER BY count DESC LIMIT 5");
$topServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to get content based on section
function getSectionContent($section) {
    global $pdo;
    
    switch ($section) {
        case 'appointments':
            $stmt = $pdo->query("SELECT a.*, u.username, s.name as service_name
                                 FROM appointments a 
                                 JOIN users u ON a.user_id = u.id 
                                 JOIN services s ON a.service_id = s.id 
                                 ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 20"); // Updated: added ORDER BY appointment_time
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        case 'customers':
            $stmt = $pdo->query("SELECT * FROM users WHERE is_admin = 0 ORDER BY created_at DESC LIMIT 20");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        case 'services':
            $stmt = $pdo->query("SELECT * FROM services ORDER BY category_id, name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        case 'reports':
            // You can add more complex reports here
            $revenueByMonth = $pdo->query("SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, SUM(price) as revenue 
                                          FROM appointments 
                                          WHERE status = 'confirmed' 
                                          GROUP BY DATE_FORMAT(appointment_date, '%Y-%m') 
                                          ORDER BY month DESC LIMIT 12")->fetchAll(PDO::FETCH_ASSOC); // Updated: appointment_date instead of completed_at
            
            $topCustomers = $pdo->query("SELECT u.username, COUNT(*) as visit_count, SUM(a.price) as total_spent 
                                         FROM appointments a 
                                         JOIN users u ON a.user_id = u.id 
                                         WHERE a.status = 'confirmed' 
                                         GROUP BY a.user_id 
                                         ORDER BY total_spent DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC); // Updated: a.price instead of a.total_revenue
            
            return ['revenueByMonth' => $revenueByMonth, 'topCustomers' => $topCustomers];
        
        default:
            return [];
    }
}

$currentSection = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$sectionContent = getSectionContent($currentSection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #b69159;
            --secondary-color: #a87e4e;
            --accent-color: #d4c39d;
            --background-color: #faf8f2;
            --text-color: #5d4431;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-nav {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-nav h1 {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-nav ul {
            list-style-type: none;
        }
        
        .admin-nav ul li {
            margin-bottom: 15px;
        }
        
        .admin-nav ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        .admin-nav ul li a:hover, .admin-nav ul li a.active {
            background-color: rgba(255,255,255,0.2);
        }
        
        .admin-nav ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .admin-content {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
        }
        
        h2 {
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-size: 28px;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            color: var(--primary-color);
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .dashboard-charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .chart {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .chart h3 {
            color: var(--primary-color);
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        table th {
            background-color: var(--accent-color);
            color: var(--text-color);
        }
        
        ul {
            list-style-type: none;
        }
        
        ul li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                width: 100%;
                height: auto;
                position: static;
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .dashboard-stats, .dashboard-charts {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h1>Larissa Salon Studio</h1>
            <ul>
                <li><a href="?section=dashboard" class="<?php echo $currentSection === 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="?section=appointments" class="<?php echo $currentSection === 'appointments' ? 'active' : ''; ?>"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="?section=customers" class="<?php echo $currentSection === 'customers' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="?section=services" class="<?php echo $currentSection === 'services' ? 'active' : ''; ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
                <li><a href="?section=reports" class="<?php echo $currentSection === 'reports' ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <main class="admin-content">
            <?php if ($currentSection === 'dashboard'): ?>
                <section id="dashboard">
                    <h2>Dashboard</h2>
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <h3>Total Appointments</h3>
                            <p><?php echo $totalAppointments; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Total Customers</h3>
                            <p><?php echo $totalCustomers; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Total Services</h3>
                            <p><?php echo $totalServices; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Total Revenue</h3>
                            <p>Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    <div class="dashboard-charts">
                        <div class="chart">
                            <h3>Recent Appointments</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentAppointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo date('Y-m-d H:i', strtotime($appointment['appointment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['username']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                        <td><?php echo ucfirst($appointment['status']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="chart">
                            <h3>Top Services</h3>
                            <ul>
                                <?php foreach ($topServices as $service): ?>
                                <li>
                                    <span><?php echo htmlspecialchars($service['name']); ?></span>
                                    <span><?php echo $service['count']; ?> bookings</span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </section>
            <?php elseif ($currentSection === 'appointments'): ?>
                <section id="appointments">
                    <h2>Appointments</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionContent as $appointment): ?>
                            <tr>
                                <td><?php echo date('Y-m-d', strtotime($appointment['appointment_date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?></td>  <!-- Updated: Display appointment time -->
                                <td><?php echo htmlspecialchars($appointment['username']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                <td>Rp <?php echo number_format($appointment['price'], 0, ',', '.'); ?></td>
                                <td><?php echo ucfirst($appointment['status']); ?></td>
                                <td>
                                    <a href="edit-appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete-appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php elseif ($currentSection === 'customers'): ?>
                <section id="customers">
                    <h2>Customers</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionContent as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td><?php echo htmlspecialchars($customer['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></td>
                                <td>
                                    <a href="edit-customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete-customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php elseif ($currentSection === 'services'): ?>
                <section id="services">
                    <h2>Services</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionContent as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['name']); ?></td>
                                <td><?php echo htmlspecialchars($service['category_id']); ?></td>
                                <td><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . '...'; ?></td>
                                <td>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $service['duration']; ?> minutes</td>
                                <td>
                                    <a href="edit-service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete-service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php elseif ($currentSection === 'reports'): ?>
                <section id="reports">
                    <h2>Reports</h2>
                    <div class="chart">
                        <h3>Monthly Revenue</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sectionContent['revenueByMonth'] as $revenue): ?>
                                <tr>
                                    <td><?php echo $revenue['month']; ?></td>
                                    <td>Rp <?php echo number_format($revenue['revenue'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="chart">
                        <h3>Top Customers</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Visit Count</th>
                                    <th>Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sectionContent['topCustomers'] as $customer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                    <td><?php echo $customer['visit_count']; ?></td>
                                    <td>Rp <?php echo number_format($customer['total_spent'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endif; ?>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/admin.js"></script>
</body>
</html>

