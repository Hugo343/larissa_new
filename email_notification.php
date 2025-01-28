<?php

function sendEmail($to, $subject, $body) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Larissa Salon Studio <noreply@larissasalon.com>' . "\r\n";

    return mail($to, $subject, $body, $headers);
}

function sendAppointmentConfirmation($to, $appointmentDetails) {
    $subject = "Appointment Confirmation - Larissa Salon Studio";
    $body = "
    <h2>Your appointment has been confirmed!</h2>
    <p>Dear {$appointmentDetails['customer_name']},</p>
    <p>Your appointment details are as follows:</p>
    <ul>
        <li>Service: {$appointmentDetails['service_name']}</li>
        <li>Date: {$appointmentDetails['date']}</li>
        <li>Time: {$appointmentDetails['time']}</li>
    </ul>
    <p>We look forward to seeing you!</p>
    <p>Best regards,<br>Larissa Salon Studio Team</p>
    ";

    return sendEmail($to, $subject, $body);
}

function sendAppointmentReminder($to, $appointmentDetails) {
    $subject = "Appointment Reminder - Larissa Salon Studio";
    $body = "
    <h2>Reminder: Your upcoming appointment</h2>
    <p>Dear {$appointmentDetails['customer_name']},</p>
    <p>This is a friendly reminder of your upcoming appointment:</p>
    <ul>
        <li>Service: {$appointmentDetails['service_name']}</li>
        <li>Date: {$appointmentDetails['date']}</li>
        <li>Time: {$appointmentDetails['time']}</li>
    </ul>
    <p>We look forward to seeing you soon!</p>
    <p>Best regards,<br>Larissa Salon Studio Team</p>
    ";

    return sendEmail($to, $subject, $body);
}

function sendAppointmentCancellation($to, $appointmentDetails) {
    $subject = "Appointment Cancellation - Larissa Salon Studio";
    $body = "
    <h2>Your appointment has been cancelled</h2>
    <p>Dear {$appointmentDetails['customer_name']},</p>
    <p>Your appointment has been cancelled as requested. The details were:</p>
    <ul>
        <li>Service: {$appointmentDetails['service_name']}</li>
        <li>Date: {$appointmentDetails['date']}</li>
        <li>Time: {$appointmentDetails['time']}</li>
    </ul>
    <p>If you wish to reschedule, please visit our website or contact us directly.</p>
    <p>Best regards,<br>Larissa Salon Studio Team</p>
    ";

    return sendEmail($to, $subject, $body);
}

