<?php
// register.php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $sql = "INSERT INTO users (username, email, password, full_name, phone) VALUES ('$username', '$email', '$password', '$full_name', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Registration successful. Please log in.";
        header("Location: login.php");
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>