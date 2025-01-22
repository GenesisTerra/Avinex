<?php
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'Light';
$newTheme = ($theme === 'Dark') ? 'Light' : 'Dark';
setcookie('theme', $newTheme, time() + (30 * 24 * 60 * 60), '/');
$referrer = $_SERVER['HTTP_REFERER'] ?? '/';
header("Location: $referrer");
?>
