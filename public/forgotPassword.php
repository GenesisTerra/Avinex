<?php 
require '../includes/db.php';
require '../includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../includes/navbar.php';

$otpSent = false;
$otpVerified = false; 
$message = '';
$status = false; 

function sendOtp($email, $otp, $user) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'xxxxxxxxxxxxxxxx';  // Your email
        $mail->Password = 'xxxxxxxxxxxxxxxxxxxx';     // Replace with your email app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 

        $mail->setFrom('your-email@gmail.com', 'blog');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Dear $user,<br>Your OTP code is: <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_otp'])) {
        $Userid = $_POST['Userid'];
        $_SESSION['Userid'] = $Userid;

        $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $Userid);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $email = $stmt->fetchColumn(); // Get the first column, which is email

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;

                if (sendOtp($email, $otp, $Userid)) {
                    $otpSent = true;
                    $message = "An OTP has been sent to your email.";
                    $color = 'green';
                } else {
                    $message = "Failed to send OTP. Please try again.";
                    $color = 'red';
                }
            } else {
                $message = "Invalid email format.";
                $color = 'red';
            }   
        } else {
            $message = "User not found.";
            $color = 'red';
        }

    // Handle OTP verification
    } elseif (isset($_POST['verify_otp'])) {
        $enteredOtp = $_POST['otp'];
        if ($enteredOtp == $_SESSION['otp']) {
            $otpVerified = true;
            $message = "OTP verified successfully!";
            $color = 'green';
        } else {
            $message = "Invalid OTP. Please try again.";
            $color = 'red';
        }

    // Handle password update
    } elseif (isset($_POST['password'])) {
        $Userid = $_SESSION['Userid'];
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $Userid);
        
        if ($stmt->execute()) {
            $message = "Password updated successfully!";
            $color = 'green';
            $status = true;
        } else {
            $message = "Error: " . $stmt->errorInfo()[2];
            $color = 'red';
        }
    }
}
?>

<body>
    <br>
    <div class="container">
        <!-- Display error or success messages -->
        <?php if ($message ): ?>
        <p style="color: <?php echo $color; ?>;"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form to send OTP -->
        <?php if (!$otpSent && !$otpVerified && !$status): ?>
        <form method="POST">
            <label for="Userid">Enter your User ID:</label><br>
            <input type="text" id="Userid" name="Userid" required><br><br>
            <input type="submit" name="send_otp" value="Send OTP">
        </form>
        <?php endif; ?>

        <!-- Form to verify OTP -->
        <?php if ($otpSent && !$otpVerified): ?>
        <form method="POST">
            <label for="otp">Enter the OTP sent to your email:</label><br>
            <input type="text" id="otp" name="otp" required><br><br>
            <input type="submit" name="verify_otp" value="Verify OTP">
        </form>
        <?php endif; ?>

        <!-- Form to change password -->
        <?php if ($otpVerified): ?>
        <form id="changePassword" method="POST">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <div id="verify_password" class="validation-message"></div>
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required><br><br>
            <span id="confirm_password" class="validation-message"></span>
            <button type="submit" class="log">SUBMIT</button>
        </form>
        <?php endif; ?>

        <?php if ($status):?>
        <br><br><button class="log" onclick="navigateButton('login.php')">Go to Login Page</button>"
        <?php endif; ?>
    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function verify_password(password) {
        const messages = [];
        if (password.length < 8) {
            messages.push("Password must be at least 8 characters long.");
        }
        if (!/[0-9]/.test(password)) {
            messages.push("Password must contain at least one number.");
        }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            messages.push("Password must contain at least one special character.");
        }

        const passwordMessages = document.getElementById('verify_password');
        if (messages.length === 0) {
            passwordMessages.innerHTML = ""; // Clear validation messages
        } else {
            passwordMessages.innerHTML = messages.join('<br>') + '<br><br>'; // Display validation messages
        }
        return messages.length === 0; // Return true if no validation errors
    }

    function confirm_password(password, confirmPassword) {
        const messages = [];
        if (password !== confirmPassword) {
            messages.push("Passwords do not match.");
        }

        const confirmPasswordMessages = document.getElementById('confirm_password');
        if (messages.length === 0) {
            confirmPasswordMessages.innerHTML = ""; // Clear validation messages
        } else {
            confirmPasswordMessages.innerHTML = messages.join('<br>') +
                '<br><br>'; // Display validation messages
        }

        return messages.length === 0; // Return true if passwords match
    }

    // Event listener for real-time password validation
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        verify_password(password);
    });

    // Event listener for real-time confirm password validation
    document.getElementById('confirm-password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        confirm_password(password, confirmPassword);
    });

    // Form submission event
    document.getElementById('changePassword').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        const isPasswordValid = verify_password(password);
        const isConfirmPasswordValid = confirm_password(password, confirmPassword);

        if (!isPasswordValid || !isConfirmPasswordValid) {
            event.preventDefault(); // Prevent form submission if validation fails
            console.log('Form submission prevented due to validation errors.');
        }
    });
});
</script>

</html>