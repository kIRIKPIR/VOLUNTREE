<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntree</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Voluntree</h1>
        <nav>
            <a href="dashboard.php">Főoldal</a>
            <a href="post_job.php">Munka feltöltése</a>
            <a href="profile.php">Profilom</a>
            <a href="applicants.php">Jelentkezők</a>
            <a href="notifications.php">Értesítések</a>
            <a href="logout.php">Kijelentkezés</a>
        </nav>
    </header>
    <main>
