<?php
// Include Composer's autoloader (assuming you installed PHPMailer with Composer)
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form data processing
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $error = '';

    // Validation
    if (empty($name)) {
        $error .= 'Name is required.<br>';
    }

    if (empty($email)) {
        $error .= 'Email is required.<br>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= 'Invalid email format.<br>';
    }

    if (empty($message)) {
        $error .= 'Message is required.<br>';
    }

    // If there are no errors, proceed with email sending
    if (empty($error)) {
        try {
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'test@gmail.com';  
            $mail->Password   = 'pasword';   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            
            $mail->setFrom($email, $name);
            $mail->addAddress('test@gmail.com', 'haytam');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = 'Name: ' . $name . '<br>Email: ' . $email . '<br>Message: ' . nl2br($message);
            $mail->AltBody = 'Name: ' . $name . '\nEmail: ' . $email . '\nMessage: ' . $message;

            // Send the email
            if ($mail->send()) {
                echo "<script>alert('Message sent successfully')</script>";
                $name = '';
                $email = '';
                $message = '';
            } else {
                echo 'There was an error sending the email.';
            }

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<p style='color:red;'>$error</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px; }
        h1 { text-align: center; color: #333; }
        form { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 0 auto; }
        label { font-weight: bold; display: block; margin-bottom: 8px; }
        input, textarea { width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        input[type="submit"] { background-color: #007BFF; color: white; border: none; cursor: pointer; font-size: 16px; transition: background-color 0.3s; }
        input[type="submit"]:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <h1>Contact Us</h1>

    <!-- Contact Form -->
    <form method="POST" action="mail_php.php">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" value="<?= isset($name) ? htmlentities($name) : '' ?>">

        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlentities($email) : '' ?>">

        <label for="message">Your Message:</label>
        <textarea id="message" name="message" rows="5"><?= isset($message) ? htmlentities($message) : '' ?></textarea>

        <input type="submit" value="Send Message">
    </form>

</body>
</html>
