<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $user_id = $_SESSION['user_id'];
    $blog_title = htmlspecialchars(trim($_POST['blogTitle']));
    $blog_para = htmlspecialchars(trim($_POST['blogPara']));
    $image_filename = 'NONE'; // Default image name

    // Check if an image is uploaded
    if (!empty($_FILES['blogImage']['name'])) {
        // Define the upload directory
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["blogImage"]["name"]);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["blogImage"]["tmp_name"], $target_file)) {
            $image_filename = htmlspecialchars(basename($_FILES["blogImage"]["name"]));
        } else {
            echo "<p>Error uploading image.</p>";
        }
    }

    // Insert the blog post into the database
    try {
        $stmt = $conn->prepare("INSERT INTO blog_table (user_id, topic_title, image_filename, topic_para) VALUES (:user_id, :blog_title, :image_filename, :blog_para)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindParam(':blog_title', $blog_title, PDO::PARAM_STR);
        $stmt->bindParam(':image_filename', $image_filename, PDO::PARAM_STR);
        $stmt->bindParam(':blog_para', $blog_para, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

?>
<div id="main">
    <div class='container'>
        <h2>Create a New Blog Post</h2><br>
        <form method="POST" enctype="multipart/form-data">
            <label for="blogTitle">Blog Title:</label><br>
            <input type="text" id="blogTitle" name="blogTitle" required><br><br>
            <label for="blogPara">Blog Content:</label><br><br>
            <textarea class="expandable-input" id="blogPara" name="blogPara" rows="3" required></textarea><br><br>
            <label for="blogImage">Upload Image (Optional):</label><br><br>
            <input type="file" id="blogImage" name="blogImage" accept="image/*"><br><br><br>
            <button class='log' type="submit">Create Post</button>
        </form>
    </div>
</div>
</body>

</html>