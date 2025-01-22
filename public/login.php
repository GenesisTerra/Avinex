<?php
require '../includes/db.php';
require '../includes/header.php';
require '../includes/navbar.php';

$message = '';
$maxAttempts = 3;
$lockDuration = 10; 
$resetPeriod = 60; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['loginId'];
    $password = $_POST['password'];

    try {
        // Prepare the query to fetch user data
        $stmt = $conn->prepare("SELECT password, failed_attempts, lock_until, last_attempt FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $user['password'];
            $failed_attempts = $user['failed_attempts'];
            $lock_until = $user['lock_until'];
            $last_attempt = $user['last_attempt'];
            $current_time = time();

            // Reset failed attempts if the reset period has passed
            if ($current_time - $last_attempt > $resetPeriod) {
                $failed_attempts = 0;
            }

            // Check if the user is locked out
            if ($failed_attempts >= $maxAttempts && $current_time < $lock_until) {
                $message = 'Too many attempts. Please try again later.';
            } else {
                if (password_verify($password, $stored_password)) {
                    // Reset failed attempts and lockout timestamp on successful login
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, lock_until = 0 WHERE user_id = :user_id");
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();

                    $_SESSION['user_id'] = $user_id;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $message = "Invalid password!";
                    $failed_attempts++;

                    if ($failed_attempts >= $maxAttempts) {
                        $lock_until = $current_time + $lockDuration;
                        $message = 'Too many attempts. Please try again later.';
                    }

                    // Update failed attempts, lockout timestamp, and last attempt time
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = :failed_attempts, lock_until = :lock_until, last_attempt = :last_attempt WHERE user_id = :user_id");
                    $stmt->bindParam(':failed_attempts', $failed_attempts, PDO::PARAM_INT);
                    $stmt->bindParam(':lock_until', $lock_until, PDO::PARAM_INT);
                    $stmt->bindParam(':last_attempt', $current_time, PDO::PARAM_INT);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                }
            }
        } else {
            $message = "User ID not found!";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
    
    $conn = null; 
}
?>

<br>
<div class="container">
    <h2>Login</h2><br>
    <form id="login" method="POST" action="">
        <label for="loginId">Login ID:</label>
        <input type="text" id="loginId" name="loginId" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <a href="forgotPassword.php" style='float: right; color: var(--on--surface)'>Forgot Password?</a>
        <br><br>
        <button class="log" type="submit">Login</button>
    </form>
    <?php
    if ($message) {
        echo "<p>$message</p>";
    }
    ?>
</div>
</body>
</html>
