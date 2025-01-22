<?php 
require '../includes/db.php'; 
require '../includes/header.php';
require '../includes/navbar.php';

$message = '';
$status = 'fail';
$form_submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_submitted = true;
    // Fetching and sanitizing inputs
    $full_name = htmlspecialchars(trim($_POST['name']));
    $user_id = htmlspecialchars(trim($_POST['userId']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mobile_number = htmlspecialchars(trim($_POST['mobile']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $status = "fail";
    } else {
        try {
            // Check if user ID already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $message = "User ID already exists.";
                $status = "fail";
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT); // Hashing password
                // Insert new user record
                $stmt = $conn->prepare("INSERT INTO users (full_name, user_id, email, mobile_number, password) VALUES (:full_name, :user_id, :email, :mobile_number, :password)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mobile_number', $mobile_number);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    $message = "Registration successful!";
                    $status = "success";
                } else {
                    $message = "Error: " . $stmt->errorInfo()[2];
                    $status = "fail";
                }
            }
        } catch (PDOException $e) {
            $message = "An error occurred: " . $e->getMessage();
            $status = "fail";
        }
    }
}

?>

<body>
    <br>

    <form id="register" method="post" action="register.php">
        <div class="container" id="1" style="display: <?php echo $form_submitted ? 'none' : 'block'; ?>;">
            <h2>Register</h2><br>
            <button class="log" type="button" onclick="form_change('1','2')"
                style="width:20%; display: block; margin-left: auto;">NEXT</button>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="userId">User ID:</label>
            <input type="text" id="userId" name="userId" required><br><br>
        </div>

        <div class="container" id="2" style="display: <?php echo $form_submitted ? 'none' : 'none'; ?>;">
            <div style="display: flex; justify-content: space-between;">
                <button class="log" type="button" onclick="form_change('2','1')" style="width:20%;">PREVIOUS</button>
                <button class="log" type="button" onclick="form_change('2','3')" style="width:20%;">NEXT</button>
            </div><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="mobile">Mobile Number:</label>
            <input type="tel" id="mobile" name="mobile" required><br><br>
        </div>
        <div class="container" id="3" style="display: <?php echo $form_submitted ? 'none' : 'none'; ?>;">
            <button class="log" type="button" onclick="form_change('3','2')"
                style="width:20%; display: block; margin-left: 0;">PREVIOUS</button><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <div id="verify_password" class="validation-message"></div>
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required><br><br>
            <span id="confirm_password" class="validation-message"></span>
            <button type="submit" class="log">SUBMIT</button>
        </div>
    </form>

    <!-- Message Display -->
    <div class="container" id="4" style="display: <?php echo $form_submitted ? 'block' : 'none'; ?>;">
        <?php
    echo $message;
    if ($status == "success") {
        echo "<br><br><button class=\"log\" onclick=\"navigateButton('login.php')\">Go to Login Page</button>";
    } else {
        echo "<br><br><button class=\"log\" onclick=\"navigateButton('register.php')\">Go to Register Page</button>";
    }
    ?>
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
            passwordMessages.innerHTML = "";
        } else {
            passwordMessages.innerHTML = messages.join('<br>') + '<br><br>';
        }
        return messages.length === 0;
    }

    function confirm_password(password, confirmPassword) {
        const messages = [];
        if (password !== confirmPassword) {
            messages.push("Passwords do not match.");
        }
        const confirmPasswordMessages = document.getElementById('confirm_password');
        if (messages.length === 0) {
            confirmPasswordMessages.innerHTML = "";
        } else {
            confirmPasswordMessages.innerHTML = messages.join('<br>') + '<br><br>';
        }
        return messages.length === 0;
    }

    document.getElementById('password').addEventListener('input', function() {
        console.log('Form submission prevented due to validation errors.');
        const password = this.value;
        verify_password(password);
        const confirmPassword = document.getElementById('confirm-password').value;
        confirm_password(password, confirmPassword);
    });

    document.getElementById('confirm-password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        confirm_password(password, confirmPassword);
    });

    document.getElementById('register').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const isPasswordValid = verify_password(password);
        const isConfirmPasswordValid = confirm_password(password, confirmPassword);
        if (!isPasswordValid || !isConfirmPasswordValid) {
            event.preventDefault();
            console.log('Form submission prevented due to validation errors.');
        }
    });
});
</script>

</html>