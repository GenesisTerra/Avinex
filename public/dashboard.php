<?php
require '../includes/header.php';
require '../includes/navbar.php';
require '../includes/db.php'; 

$user_id = $_SESSION["user_id"];
?>

<body>
    <div id="main" class="all-posts-container">
        <?php
        try {
            $sql = "SELECT id, user_id, topic_title, image_filename, topic_para, created_at FROM blog_table";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    echo "<div class='post-container' style=\"position: relative; \" data-id='" . $row['id'] . "' onclick='zoomIn(this)'>";
                    echo "<div class='displayTitle'><strong>".htmlspecialchars($row["topic_title"])."</strong><br>".str_repeat("&nbsp;", 10)." - by ".htmlspecialchars($row["user_id"])."</div>";
                    if ($row["image_filename"] !== "NONE") {
                        echo "<br><div style='text-align:center;'><img class='displayImage' src='../images/" . htmlspecialchars($row["image_filename"]) . "' alt='Blog Image' onclick='zoomInImage(event, this)'></div><br>";
                    }
                    echo "<p class='displayPara'>"
                        . htmlspecialchars($row["topic_para"]) . "</p><br>";
                    
                    echo "<div style=\"position: absolute; bottom: 0;  width: 87%;\">";
                        echo "<span><strong>Posted On:</strong> " . htmlspecialchars($row["created_at"]) . "</span><br><br>";
                    if ($row["user_id"] === $user_id) {
                        echo "<div class='edit-delete-btn'>";
                        echo "<a href='formUpdate.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a>";
                        echo "<div> | </div>";
                        echo "<a href='formDelete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='center-text'><span>No Blog Posts Found</span></div>";
            }

            // Close the connection
            $conn = null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>

    <!-- Zoomed-in view container -->
    <div id="zoomed-in" class="zoomed-in" style="display:none;">
        <div class="zoomed-in-content">
            <button class="close-btn" onclick="closeZoom()">Close</button>
            <div id="zoomed-content"></div> <!-- Content will be populated here -->
        </div>
    </div>

    <script>
    // function zoomIn(element) {
    //     const zoomedInContainer = document.getElementById('zoomed-in');
    //     const zoomedContent = document.getElementById('zoomed-content');
    //     zoomedContent.innerHTML = element.innerHTML;
    //     element.classList.add('zoom');
    //     element.classList.add('no-position'); // Add the new class to remove position styles
    //     zoomedInContainer.style.display = 'flex';
    // }

    function closeZoom() {
        const zoomedInContainer = document.getElementById('zoomed-in');
        zoomedInContainer.style.display = 'none';
        const posts = document.querySelectorAll('.post-container');
        posts.forEach(post => {
            post.classList.remove('zoom');
            post.classList.remove('no-position'); // Remove the class to restore original styles
        });
    }

    function zoomInImage(event, imgElement) {
        event.stopPropagation(); // Prevent event bubbling to parent
        const zoomedInContainer = document.getElementById('zoomed-in');
        const zoomedContent = document.getElementById('zoomed-content');
        zoomedContent.innerHTML = `<img class='zoomed-image' src='${imgElement.src}' alt='Zoomed Image'>`;
        zoomedInContainer.style.display = 'flex';
    }
    </script>
</body>

</html>