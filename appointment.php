<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $service_id = $conn->real_escape_string($_POST['service_id']);
    $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
    $appointment_time = $conn->real_escape_string($_POST['appointment_time']);

    $sql = "INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time) VALUES ('$user_id', '$service_id', '$appointment_date', '$appointment_time')";

    if ($conn->query($sql) === TRUE) {
        $message = "Appointment booked successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$services_sql = "SELECT * FROM services";
$services_result = $conn->query($services_sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Larissa Salon Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Book an Appointment</h1>
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="service_id">
                    Service
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="service_id" name="service_id" required>
                    <option value="">Select a service</option>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?> - Rp<?php echo number_format($service['price'], 0, ',', '.'); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_date">
                    Date
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="appointment_date" type="date" name="appointment_date" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_time">
                    Time
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="appointment_time" type="time" name="appointment_time" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Book Appointment
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="dashboard.php">
                    Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</body>
</html>