<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Értesítések lekérése (legújabb elöl)
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Olvasatlanként megjelöltek frissítése olvasottra
$pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0")
    ->execute([$user_id]);
?>

<?php include('templates/header.php'); ?>

<div class="notifications-container">
    <h2>Értesítéseim</h2>

    <?php if ($notifications): ?>
        <ul class="notification-list">
            <?php foreach ($notifications as $note): ?>
                <li style="background-color: <?php echo $note['is_read'] ? '#f9f9f9' : '#d9f0ff'; ?>; padding: 10px; margin-bottom: 8px; border-radius: 5px;">
                    <strong><?php echo date("Y.m.d H:i", strtotime($note['created_at'])); ?>:</strong><br>
                    <?php echo htmlspecialchars($note['message']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nincs értesítésed.</p>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
