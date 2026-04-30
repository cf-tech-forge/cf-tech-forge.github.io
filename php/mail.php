<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

require_once 'config.php';
require '../PHPMailer/OAuth.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$name = $email = $message = "";

function cleanInput($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    (empty($_POST['message'])) ? $message = "" : $message = cleanInput($_POST['message']);

    $mail = new PHPMailer(true); // enable exceptions

    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = 'tls';
    $mail->Port = MAIL_PORT;

    // Sender and recipient settings
    $mail->setFrom(MAIL_USERNAME, MAIL_FROM);
    $mail->addAddress(MAILTO_ADDR, MAILTO_NAME);

    // Create HTML email
    $body = "From: $name<br><br>Email: $email<br><br>Message:<br>$message";
    $mail->Subject = "Form Submission from $email";
    $mail->Body = html_entity_decode($body);
    $mail->isHTML(true);

    // Send email
    if (!$mail->send()) {
        printf("Message could not be sent. Mailer Error: $mail->ErrorInfo");
    } else {
        printf("Message has been sent.<br>");
        printf("Redirecting to CF Tech Forge Home page in 5 seconds<br>");
        printf("<a href='../index.html>Home</a>");
        header("refresh:5,url=../index.html");
    }
} else {
    header("Location: ../index.html");
    exit;
}
