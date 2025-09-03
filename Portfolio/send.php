<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

// Check if form data is set
if (isset($_POST['name_text'], $_POST['email'], $_POST['phone_text'], $_POST['body_text'])) {
    $name  = $_POST['name_text'];
    $email = $_POST['email'];
    $phone = $_POST['phone_text'];
    $text  = $_POST['body_text'];

    $mail = new PHPMailer(true);
    echo "✅ PHPMailer loaded<br>";

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPDebug = 3; // detailed debug output
        $mail->Debugoutput = function($str, $level) {
            echo "Debug [$level]: $str<br>";
        };

        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'andradec.inquiries@gmail.com';  // your Gmail
        $mail->Password   = 'ascu hqnx tfth eruj';            // your App Password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('andradec.inquiries@gmail.com', 'Website');
        $mail->addAddress('andradec.inquiries@gmail.com'); // send to yourself
        $mail->addReplyTo($email, $name); // reply goes to sender

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission";
        $mail->Body    = "
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Message:</strong><br>{$text}</p>
        ";

        // Test connection first
        if (!$mail->smtpConnect()) {
            echo "❌ Could not connect to Gmail SMTP.<br>";
            exit;
        }
        echo "✅ Connected to Gmail SMTP.<br>";

        // Send
        if ($mail->send()) {
            echo "🎉 Email sent successfully!";
        } else {
            echo "❌ Email failed to send.";
        }

        header("location: index.html?status = true");

    } catch (Exception $e) {
        echo "❌ Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "⚠️ No form data received.";
}
