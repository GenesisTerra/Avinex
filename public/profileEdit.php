<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php';

$user_id = $_SESSION['user_id'];

try {
    // Fetch current user data
    $stmt = $conn->prepare("SELECT full_name, email, mobile_number FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }

    // Store user data
    $full_name = htmlspecialchars($user['full_name']);
    $email = htmlspecialchars($user['email']);
    $mobile_number = htmlspecialchars($user['mobile_number']);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_full_name = trim($_POST['full_name']);
    $new_email = trim($_POST['email']);
    $new_mobile_number = trim($_POST['mobile_number']);

    try {
        // Update user data
        $updateStmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, mobile_number = ? WHERE user_id = ?");
        $updateStmt->execute([$new_full_name, $new_email, $new_mobile_number, $user_id]);

        // Redirect to profile page after update
        header("Location: profile.php");
        exit;
    } catch (PDOException $e) {
        echo "Error updating profile: " . $e->getMessage();
        exit;
    }
}
?>

<body>
    <div id="main">

        <div class="container">
            <h2>Edit Profile</h2><br>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number:</label>
                    <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $mobile_number; ?>"
                        required>
                </div><br>
                <button class='log' type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>

</html>