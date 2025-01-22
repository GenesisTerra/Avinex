<?php
require '../includes/db.php'; 
session_start();

// Get the blog post ID from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION["user_id"]; // Assuming user_id is stored in the session

try {
    // Fetch the blog post to verify ownership before deletion
    $stmt = $conn->prepare("SELECT user_id FROM blog_table WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the blog post exists and if the user is authorized to delete it
    if (!$blog) {
        echo "Blog post not found.";
        exit();
    } elseif ($blog['user_id'] !== $user_id) {
        echo "You are not authorized to delete this blog post.";
        exit();
    }

    // Proceed with deletion if authorized
    $stmt = $conn->prepare("DELETE FROM blog_table WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
