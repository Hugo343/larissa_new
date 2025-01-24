<header class="header">
    <div class="container">
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="images/.png" alt="Larissa Salon Studio" class="logo-image">
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="booking.php">Book Now</a></li>
                <li><a href="#contact">Contact  |</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="dashboard.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="login.php">Login</a></li>
    <?php endif; ?>
            </ul>
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </div>
</header>