<?php
require '../includes/header.php'; // Include your header
require '../includes/navbar.php'; // Include your navigation bar
require '../vendor/autoload.php'; // Load Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables
$messageSent = false;
$errorMessage = '';

// Process the form if it was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                          // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                 // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = 'xxxxxxxxxxxxxxxxx';           // SMTP username
        $mail->Password   = 'xxxxxxxxxxxxxxxxxxx';              // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
        $mail->Port       = 587;                                // TCP port to connect to

        // Recipients
        $mail->setFrom('xxxxxxxxxxxxxxxxx', 'blog');  // Sender's email and name
        $mail->addAddress('xxxxxxxxxxxxxxxxx', $name); // Add a recipient

        // Content
        $mail->isHTML(true);                                     // Set email format to HTML
        $mail->Subject = 'Contact Us Form Submission';
        $mail->Body    = "<strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><strong>Message:</strong> $message";

        $mail->send();
        $messageSent = true;
    } catch (Exception $e) {
        $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<body>
    <div id="main">
        <div class="container">
            <h2>Contact Us</h2><br>
            <?php if ($messageSent): ?>
            <div style="color: green;">Your message has been sent successfully!</div>
            <?php elseif ($errorMessage): ?>
            <div style="color: red;"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form action="contactUs.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" CLASS="expandable-input" required></textarea><br><br>
                <input type="submit" value="Send Message">
            </form>
        </div>
    </div>
</body>

</html>