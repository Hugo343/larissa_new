<?php
session_start();
require_once 'config.php';

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'login';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mode === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            if ($user['is_admin']) {
                echo json_encode(['success' => true, 'redirect' => 'admin.php']);
            } else {
                echo json_encode(['success' => true, 'redirect' => 'index.php']);
            }
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
            exit;
        }
    } elseif ($mode === 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $password, $full_name, $phone])) {
            echo json_encode(['success' => true, 'message' => 'Registration successful. Please log in.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Registration failed. Please try again.']);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $mode === 'login' ? 'Login' : 'Register'; ?> - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        :root {
            --tan-50: #faf8f2;
            --tan-100: #f3eee1;
            --tan-200: #e5dbc3;
            --tan-300: #d4c39d;
            --tan-400: #c7ad7f;
            --tan-500: #b69159;
            --tan-600: #a87e4e;
            --tan-700: #8c6642;
            --tan-800: #72533a;
            --tan-900: #5d4431;
            --tan-950: #312319;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--tan-100);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: var(--tan-50);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(49, 35, 25, 0.1);
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: flex;
            overflow: hidden;
            position: relative;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-image {
            flex: 1;
            background-size: cover;
            background-position: center;
            position: relative;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(49, 35, 25, 0.3);
            z-index: 1;
        }

        .auth-form-container {
            flex: 1;
            padding: 40px;
            position: relative;
            min-width: 450px;
            background: var(--tan-50);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-container.register-mode {
            flex-direction: row-reverse;
        }

        .form-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-form, .register-form {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(50px);
        }

        .login-form.active,
        .register-form.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        h1 {
            font-size: 28px;
            color: var(--tan-950);
            margin-bottom: 8px;
            font-weight: 600;
        }

        p {
            color: var(--tan-800);
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1.5px solid var(--tan-200);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: var(--tan-50);
            color: var(--tan-950);
        }

        .form-group input:focus {
            border-color: var(--tan-400);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(199, 173, 127, 0.1);
            outline: none;
        }

        .form-group label {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--tan-600);
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--tan-500);
            color: var(--tan-50);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--tan-600);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(168, 126, 78, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        .auth-links {
            text-align: center;
            margin-top: 20px;
        }

        .auth-links a {
            color: var(--tan-600);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .auth-links a:hover {
            color: var(--tan-700);
            text-decoration: underline;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert-danger {
            background-color: #fff2f2;
            border: 1px solid #ffcdd2;
            color: #d32f2f;
        }

        .alert-success {
            background-color: #f0f9f0;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .toggle-mode {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .toggle-mode a {
            color: var(--tan-600);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .toggle-mode a:hover {
            color: var(--tan-700);
            text-decoration: underline;
        }

        .floating-icon {
            position: absolute;
            z-index: 2;
            color: var(--tan-50);
            font-size: 24px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .icon-1 { top: 20%; left: 15%; animation-delay: 0s; }
        .icon-2 { top: 50%; right: 15%; animation-delay: 0.5s; }
        .icon-3 { bottom: 20%; left: 25%; animation-delay: 1s; }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column !important;
                max-width: 400px;
            }

            .auth-image {
                display: none;
            }

            .auth-form-container {
                min-width: unset;
                width: 100%;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container <?php echo $mode === 'register' ? 'register-mode' : ''; ?>">
        <div class="auth-image" style="background-image: url('images/dash-2')">
            <div class="floating-icon icon-1"><i class="fas fa-spa"></i></div>
            <div class="floating-icon icon-2"><i class="fas fa-pump-soap"></i></div>
            <div class="floating-icon icon-3"><i class="fas fa-hand-sparkles"></i></div>
        </div>
        <div class="auth-form-container">
            <div class="form-wrapper">
                <div class="login-form <?php echo $mode === 'login' ? 'active' : ''; ?>">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account</p>
                    <div id="login-alert" class="alert"></div>
                    <form id="login-form" action="?mode=login" method="post">
                        <div class="form-group">
                            <label for="username"><i class="fas fa-user-circle"></i></label>
                            <input type="text" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i></label>
                            <input type="password" id="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn">Login</button>
                    </form>
                    <div class="auth-links">
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <div class="toggle-mode">
                        <p>Don't have an account? <a href="#" class="switch-mode" data-mode="register">Sign Up</a></p>
                    </div>
                </div>
                <div class="register-form <?php echo $mode === 'register' ? 'active' : ''; ?>">
                    <h1>Create an Account</h1>
                    <p>Join Larissa Salon Studio today</p>
                    <div id="register-alert" class="alert"></div>
                    <form id="register-form" action="?mode=register" method="post">
                        <div class="form-group">
                            <label for="full_name"><i class="fas fa-user"></i></label>
                            <input type="text" id="full_name" name="full_name" placeholder="Full Name" required>
                        </div>
                        <div class="form-group">
                            <label for="reg_username"><i class="fas fa-user-circle"></i></label>
                            <input type="text" id="reg_username" name="username" placeholder="Username" required>
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
                            <label for="reg_password"><i class="fas fa-lock"></i></label>
                            <input type="password" id="reg_password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn">Register</button>
                    </form>
                    <div class="toggle-mode">
                        <p>Already have an account? <a href="#" class="switch-mode" data-mode="login">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle input focus effects
            $('.form-group input').on('focus', function() {
                $(this).parent().find('label').css('color', '#a87e4e');
            }).on('blur', function() {
                $(this).parent().find('label').css('color', '#a87e4e');
            });

            // Handle form submission
            $('.auth-form-container form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $alert = $form.siblings('.alert');
                var originalText = $button.text();

                $button.prop('disabled', true).text('Processing...');
                $alert.hide();

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Show pop-up for successful registration
                                alert(response.message);
                                $form[0].reset();
                                // Switch to login form after successful registration
                                $('.switch-mode[data-mode="login"]').click();
                            }
                        } else {
                            $alert.removeClass('alert-success').addClass('alert-danger').text(response.error).fadeIn();
                        }
                    },
                    error: function() {
                        $alert.removeClass('alert-success').addClass('alert-danger').text('An error occurred. Please try again.').fadeIn();
                    },
                    complete: function() {
                        $button.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Handle mode switching
            $('.switch-mode').on('click', function(e) {
                e.preventDefault();
                var mode = $(this).data('mode');
                $('.auth-container').toggleClass('register-mode', mode === 'register');
                $('.login-form').toggleClass('active', mode === 'login');
                $('.register-form').toggleClass('active', mode === 'register');
                history.pushState(null, '', '?mode=' + mode);
                $('.alert').hide();
            });
        });
    </script>
</body>
</html>

