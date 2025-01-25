<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $pdo->quote($_POST['username']);
    $email = $pdo->quote($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $pdo->quote($_POST['full_name']);
    $phone = $pdo->quote($_POST['phone']);

    $sql = "INSERT INTO users (username, email, password, full_name, phone) VALUES ($username, $email, '$password', $full_name, $phone)";

    if ($pdo->exec($sql)) {
        $_SESSION['message'] = "Registration successful. Please log in.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $pdo->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="auth-container">
        <div class="auth-image">
            <img src="https://source.unsplash.com/random/800x600?beauty,makeup" alt="Larissa Salon Studio">
        </div>
        <div class="auth-form-container">
            <h1>Create an Account</h1>
            <p>Join Larissa Salon Studio today</p>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="" method="post" class="auth-form">
                <div class="form-group">
                    <label for="full_name"><i class="fas fa-user"></i></label>
                    <input type="text" id="full_name" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user-circle"></i></label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i></label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i></label>
                    <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Sign In</a></p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

