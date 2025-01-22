<?php
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'Light';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../assets/webapp.js"></script>
    <?php if ($currentTheme === "Dark") : ?>
        <link rel="stylesheet" href="../assets/webapp-dark.css">
    <?php else : ?>
        <link rel="stylesheet" href="../assets/webapp-light.css">
    <?php endif; ?>

</head>