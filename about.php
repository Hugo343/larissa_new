<?php
session_start();
require_once 'config.php';

// Fetch team members
$stmt = $pdo->query("SELECT * FROM team_members ORDER BY id ASC");
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/salon-bg.jpg') no-repeat center center; background-size: cover; height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff;">
            <div>
                <h1 style="font-size: 3.5rem; margin-bottom: 10px; animation: fadeInDown 1s ease-out;">About Larissa Salon Studio</h1>
                <p style="font-size: 1.5rem; animation: fadeInUp 1s ease-out;">Empowering beauty since 2023</p>
            </div>
        </section>

        <section style="padding: 80px 0; background-color: #f9f9f9;">
            <div class="container" style="display: flex; align-items: center; gap: 40px;">
                <div style="flex: 1;">
                    <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #b69159;">Our Story</h2>
                    <p style="font-size: 1.1rem; margin-bottom: 20px;">Larissa Salon Studio was founded in 2023 by Clarisa Immanuela Siregar with a vision to provide high-quality beauty services in a welcoming and professional environment. Located in Kota Binjai, we've quickly become a beloved beauty destination for clients from Binjai and Medan.</p>
                    <p style="font-size: 1.1rem;">Our team of 6 skilled professionals (5 women and 1 man) is dedicated to helping you look and feel your best. We believe that everyone deserves to feel beautiful, and we're here to make that happen.</p>
                </div>
                <div style="flex: 1; position: relative;">
                    <img src="images/dash-4.jpg" alt="Larissa Salon Studio Interior" style="width: 100%; border-radius: 10px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: transform 0.3s ease-in-out;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="position: absolute; top: -20px; right: -20px; background-color: #b69159; color: #fff; padding: 10px 20px; border-radius: 50px; font-weight: bold;">Est. 2023</div>
                </div>
            </div>
        </section>

        <section style="padding: 80px 0; background-color: #fff;">
    <div class="container">
        <h2 style="font-size: 2.5rem; text-align: center; margin-bottom: 40px; color: #b69159;">Meet Our Team</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <?php
            // Fetch team members
            $stmt = $pdo->query("SELECT * FROM team_members ORDER BY id ASC");
            $teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($teamMembers as $member): ?>
                <div style="text-align: center; background-color: #f9f9f9; padding: 20px; border-radius: 10px; transition: transform 0.3s ease-in-out;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="<?php echo htmlspecialchars($member['image_url']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 5px;"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p style="font-size: 1rem; color: #b69159; margin-bottom: 10px;"><?php echo htmlspecialchars($member['position']); ?></p>
                    <p style="font-size: 0.9rem;"><?php echo htmlspecialchars($member['bio']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

        <section style="padding: 80px 0; background-color: #f9f9f9;">
            <div class="container">
                <h2 style="font-size: 2.5rem; text-align: center; margin-bottom: 40px; color: #b69159;">Our Values</h2>
                <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
                    <?php
                    $values = [
                        ['icon' => 'fa-heart', 'title' => 'Passion', 'description' => 'We are passionate about beauty and dedicated to our craft.'],
                        ['icon' => 'fa-users', 'title' => 'Customer-Centric', 'description' => 'Our clients are at the heart of everything we do.'],
                        ['icon' => 'fa-graduation-cap', 'title' => 'Continuous Learning', 'description' => 'We stay updated with the latest trends and techniques.'],
                        ['icon' => 'fa-gem', 'title' => 'Quality', 'description' => 'We strive to provide the highest quality services and products.']
                    ];
                    foreach ($values as $value):
                    ?>
                        <div style="text-align: center; width: 200px;">
                            <i class="fas <?php echo $value['icon']; ?>" style="font-size: 3rem; color: #b69159; margin-bottom: 15px;"></i>
                            <h3 style="font-size: 1.2rem; margin-bottom: 10px;"><?php echo $value['title']; ?></h3>
                            <p style="font-size: 0.9rem;"><?php echo $value['description']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section style="padding: 80px 0; background-color: #fff; text-align: center;">
            <div class="container">
                <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #b69159;">Contact Us</h2>
                <p style="font-size: 1.1rem; margin-bottom: 30px;">We'd love to hear from you! Reach out to us through any of the following channels:</p>
                <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                    <a href="https://www.instagram.com/larissasalonstudio" target="_blank" style="text-decoration: none; color: #333; display: flex; align-items: center; font-size: 1.1rem;">
                        <i class="fab fa-instagram" style="font-size: 2rem; margin-right: 10px; color: #b69159;"></i> @larissasalonstudio
                    </a>
                    <a href="https://wa.me/6282268777018" target="_blank" style="text-decoration: none; color: #333; display: flex; align-items: center; font-size: 1.1rem;">
                        <i class="fab fa-whatsapp" style="font-size: 2rem; margin-right: 10px; color: #b69159;"></i> +6282268777018
                    </a>
                    <a href="mailto:makeuprisa3@gmail.com" style="text-decoration: none; color: #333; display: flex; align-items: center; font-size: 1.1rem;">
                        <i class="far fa-envelope" style="font-size: 2rem; margin-right: 10px; color: #b69159;"></i> makeuprisa3@gmail.com
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Add smooth scrolling
        $(document).ready(function(){
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top
                    }, 1000);
                }
            });
        });

        // Add animation classes on scroll
        $(window).scroll(function() {
            $(".animate").each(function() {
                var position = $(this).offset().top;
                var scroll = $(window).scrollTop();
                var windowHeight = $(window).height();
                if (scroll > position - windowHeight + 200) {
                    $(this).addClass("animate-active");
                }
            });
        });
    </script>
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate { opacity: 0; transition: all 0.5s; }
        .animate-active { opacity: 1; }
    </style>
</body>
</html>