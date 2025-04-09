<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/notifications.css">

<div class="notifications-page">
    <h2>Értesítéseid</h2>

    <?php if ($notifications): ?>
        <ul class="notification-list">
            <?php foreach ($notifications as $note): ?>
                <li class="notification-item <?php echo $note['is_read'] ? 'read' : 'unread'; ?>">
                    <?php echo htmlspecialchars($note['message']); ?>
                    <small>(<?php echo $note['created_at']; ?>)</small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nincsenek értesítéseid.</p>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
