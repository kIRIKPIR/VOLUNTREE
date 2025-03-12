<?php
session_start();
require 'db.php';

$sql = "SELECT jobs.*, users.full_name FROM jobs 
        JOIN users ON jobs.posted_by = users.id 
        WHERE jobs.status = 'approved' 
        ORDER BY jobs.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Főoldal</title>
</head>
<body>
    <h1>Önkéntes munkák</h1>
    
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="job-post">
            <h3><?= htmlspecialchars($row['full_name']) ?></h3>
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Munka képe" style="max-width:100%;">
            <p><?= htmlspecialchars($row['description']) ?></p>
            <p>Helyszín: <?= htmlspecialchars($row['location']) ?></p>
            <p>Időpont: <?= htmlspecialchars($row['date']) ?></p>

            <form action="apply.php" method="POST">
                <input type="hidden" name="job_id" value="<?= $row['id'] ?>">
                <button type="submit">Jelentkezem</button>
            </form>
        </div>
    <?php endwhile; ?>

</body>
</html>