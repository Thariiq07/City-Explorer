<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

require 'config.php';

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST["Name"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    try {
        require_once "dbh.inc.php";

        // Check if the email already exists in the database
        $query_check_email = "SELECT * FROM users WHERE Email = ?";
        $stmt_check_email = $pdo->prepare($query_check_email);
        $stmt_check_email->execute([$Email]);

        if ($stmt_check_email->rowCount() > 0) {
            // Email already exists, notify the user
            $_SESSION["signup_error"] = "Email is already registered.";
            header("Location: ../User.html"); // Redirect back to the signup page
            exit();
        }

        // Email is not registered, proceed with signup
        $OTP = generateOTP();
        $query_insert_user = "INSERT INTO users (Name, Email, Password, OTP) VALUES (?, ?, ?, ?)";
        $stmt_insert_user = $pdo->prepare($query_insert_user);
        $stmt_insert_user->execute([$Name, $Email, $Password, $OTP]);

        // Store email in session variable
        $_SESSION["user_email"] = $Email;

        $subject = "Registration successful";
        $message = "Dear $Name,<br><br>Thank you for signing up on our Project.<br><br>Your One-Time Password (OTP) is: $OTP<br><br>Best regards,<br>Team HR";
        $mail_sent = sendMail($Email, $subject, $message);

        if ($mail_sent === true) {
            header("location:../success.html");
            exit();
        } else {
            die("Failed to send email: " . $mail_sent);
        }
    } catch (PDOException $e) {
        die("Query failed:" . $e->getMessage());
    }
} else {
    header("location:../failed.html");
}

function sendMail($email, $subject, $message)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAILHOST;
        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
        $mail->addAddress($email);
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Sending email
        if ($mail->send()) {
            return true;
        } else {
            return $mail->ErrorInfo; // Return error message
        }
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function generateOTP()
{
    return rand(0000, 9999);
}

