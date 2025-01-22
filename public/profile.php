<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php'; 

$user_id = $_SESSION['user_id']; 

try {
    $stmt = $conn->prepare("SELECT full_name, email, mobile_number, created_at FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $full_name = htmlspecialchars($user['full_name']);
        $email = htmlspecialchars($user['email']);
        $mobile_number = htmlspecialchars($user['mobile_number']);
        $joined = htmlspecialchars(date('F j, Y', strtotime($user['created_at'])));
    } else {
        echo "User not found!";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<body>
    <div id="main">

        <div class="container">
            <div class="profile-header">
                <img src="../assets/logo.png" alt="User Profile Picture">
                <h2><?php echo $full_name; ?></h2>
                <p style="color: #818181">Full Stack Developer | <?php echo $email; ?></p>
            </div>

            <div class="profile-body">
                <div class="profile-info">
                    <h3>About Me</h3>
                    <p><span>Name:</span> <?php echo $full_name; ?></p>
                    <p><span>Email:</span> <?php echo $email; ?></p>
                    <p><span>Phone:</span> <?php echo $mobile_number; ?></p>
                    <p><span>Joined:</span> <?php echo $joined; ?></p>
                </div>

                <div class="profile-actions">
                    <a href="profileEdit.php">Edit Profile</a>
                    <a href="profileLock.php">Change Password</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>