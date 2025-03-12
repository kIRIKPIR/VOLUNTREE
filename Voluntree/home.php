<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Jóváhagyott munkák lekérése
$sql = "SELECT jobs.*, users.fullname FROM jobs 
        JOIN users ON jobs.user_id = users.user_id 
        WHERE jobs.approved = 1 
        ORDER BY jobs.date DESC";
$stmt = $pdo->query($sql);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Önkéntes munkák</h1>
        <a href="upload.php">Munka feltöltése</a>
        <a href="logout.php">Kijelentkezés</a>
    </header>

    <main>
        <?php foreach ($jobs as $job): ?>
            <div class="job-post">
                <h2><?= htmlspecialchars($job["fullname"]) ?></h2>
                <img src="../uploads/<?= htmlspecialchars($job["image"]) ?>" alt="Munkakép">
                <p><?= htmlspecialchars($job["description"]) ?></p>
                <button class="apply-btn" data-job="<?= $job['id'] ?>">Jelentkezem</button>
            </div>
        <?php endforeach; ?>
    </main>

    <script src="main.js"></script>
</body>
</html>