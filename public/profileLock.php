<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php';

$user_id = $_SESSION['user_id'];
$otpVerified = true; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['password']);
    try {
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $updateStmt->execute([$hashed_password, $user_id]);
            header("Location: profile.php");
            exit;
        } else {
            $error_message = "Current password is incorrect.<br>";
        }
    } catch (PDOException $e) {
        echo "Error updating password: <br>" . $e->getMessage();
        exit;
    }
}
?>

<body>
    <div id="main">

        <div class="container">
            <h2>Change Password</h2><br>
            <?php if ($otpVerified): ?>
            <form id="changePassword" method="POST">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required><br>
                <span style="color: red;"><?php echo isset($error_message) ? $error_message : ''; ?></span><br>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <div id="verify_password" class="validation-message"></div>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required><br><br>
                <span id="confirm_password" class="validation-message"></span>

                <button type="submit" class="log">SUBMIT</button>
            </form>
            <?php endif; ?>
        </div>
    </div>

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
</body>

</html>