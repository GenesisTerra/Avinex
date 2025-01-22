<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
} else {
    header("Location: public/login.php");
}
exit();
?>
