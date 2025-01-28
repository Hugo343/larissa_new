<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

// Assuming we're using Stripe for payment processing
require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('your_stripe_secret_key');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $amount = $_POST['amount']; // Amount in cents
    $appointmentId = $_POST['appointment_id'];

    try {
        // Create a charge using Stripe's API
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'idr',
            'description' => 'Larissa Salon Studio - Appointment Payment',
            'source' => $token,
        ]);

        // If the charge is successful, update the appointment status
        if ($charge->status == 'succeeded') {
            $stmt = $pdo->prepare("UPDATE appointments SET status = 'paid' WHERE id = ?");
            $stmt->execute([$appointmentId]);

            // Redirect to a success page
            header('Location: payment_success.php');
            exit();
        } else {
            throw new Exception('Payment failed.');
        }
    } catch (\Stripe\Exception\CardException $e) {
        $error = $e->getMessage();
    } catch (Exception $e) {
        $error = 'An error occurred while processing your payment.';
    }
}

// Fetch appointment details
$appointmentId = $_GET['appointment_id'];
$stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price 
                       FROM appointments a 
                       JOIN services s ON a.service_id = s.id 
                       WHERE a.id = ? AND a.user_id = ?");
$stmt->execute([$appointmentId, $_SESSION['user_id']]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    header('Location: appointment_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Larissa Salon Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/main.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="container">
        <h1>Payment for Appointment</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="payment-details">
            <h2><?php echo htmlspecialchars($appointment['service_name']); ?></h2>
            <p>Date: <?php echo date('Y-m-d', strtotime($appointment['appointment_date'])); ?></p>
            <p>Time: <?php echo date('H:i', strtotime($appointment['appointment_date'])); ?></p>
            <p>Amount: Rp <?php echo number_format($appointment['price'], 0, ',', '.'); ?></p>
        </div>

        <form action="payment_gateway.php" method="post" id="payment-form">
            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
            <input type="hidden" name="amount" value="<?php echo $appointment['price'] * 100; ?>">
            <div class="form-row">
                <label for="card-element">
                    Credit or debit card
                </label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div>

            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Create a Stripe client.
        var stripe = Stripe('your_stripe_publishable_key');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Create an instance of the card Element.
        var card = elements.create('card');

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
</body>
</html>

