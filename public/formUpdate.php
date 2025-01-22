<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php'; 

// Retrieve the blog post ID from the query string
if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']);  // Sanitize the input
    
    // Fetch the existing blog post data
    try {
        $stmt = $conn->prepare("SELECT * FROM blog_table WHERE id = :id");
        $stmt->bindParam(':id', $blog_id, PDO::PARAM_INT);
        $stmt->execute();
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blog) {
            echo "<p>Blog post not found.</p>";
            exit;
        }

        // Check if the user is authorized to edit this blog post
        if ($blog['user_id'] !== $_SESSION['user_id']) {
            echo "<p>You are not authorized to edit this blog post.</p>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}

// Handle form submission for blog post update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = htmlspecialchars(trim($_POST['blogTitle']));
    $new_content = htmlspecialchars(trim($_POST['blogPara']));
    $image_filename = $blog['image_filename']; // Keep the existing image filename

    // Check if a new image is uploaded
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

    try {
        // Update the blog post in the database
        $stmt = $conn->prepare("UPDATE blog_table SET topic_title = :title, topic_para = :content, image_filename = :image WHERE id = :id");
        $stmt->bindParam(':title', $new_title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $new_content, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image_filename, PDO::PARAM_STR);
        $stmt->bindParam(':id', $blog_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to the dashboard after successful update
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
<div id="main">
<div class='container'>
    <h2>Update Blog Post</h2><br>
    <form method="POST" enctype="multipart/form-data">
        <label for="blogTitle">Blog Title:</label><br>
        <input type="text" id="blogTitle" name="blogTitle" value="<?= htmlspecialchars($blog['topic_title']) ?>" required><br><br>

        <label for="blogPara">Blog Content:</label><br>
        <textarea class="expandable-input" id="blogPara" name="blogPara" rows="10" required><?= htmlspecialchars($blog['topic_para']) ?></textarea><br><br>

        <label for="blogImage">Upload New Image (Optional):</label><br><br>
        <input type="file" id="blogImage" name="blogImage" accept="image/*"><br><br><br>

        <?php if ($blog['image_filename'] !== 'NONE'): ?>
            <p>Current Image:</p>
            <img style='width: 100px; height: auto' src="../images/<?= htmlspecialchars($blog['image_filename']) ?>" alt="Current Image"><br><br>
        <?php endif; ?>

        <button class='log' type="submit">Update Post</button>
    </form>
</div>
</div>
</body>

</html>
