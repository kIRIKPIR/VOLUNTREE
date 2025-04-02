<?php
session_start();
session_destroy(); // Session törlése
header("Location: login.php"); // Visszairányítás a bejelentkezés oldalra
exit();
?>
