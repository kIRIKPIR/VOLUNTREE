<?php
session_start();
include('connect.php');

if (!isset($_GET['user_id'])) {
    echo "Nincs megadva felhasználó azonosító.";
    exit();
}

$user_id = $_GET['user_id'];

// Felhasználói adatok lekérése
$stmt = $pdo->prepare("SELECT name, profile_picture, bio, contact FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "A felhasználó nem található.";
    exit();
}

$profile_picture = !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'uploads/default_profile.jpg';
$bio = trim($user['bio'] ?? '');
$contact = trim($user['contact'] ?? '');
$name = trim($user['name'] ?? '');

// Felhasználó által feltöltött munkák lekérése
$stmtJobs = $pdo->prepare("SELECT * FROM jobs WHERE user_id = ? ORDER BY created_at DESC");
$stmtJobs->execute([$user_id]);
$userJobs = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/view_profile.css">

<div class="view-profile">
    <h2><?php echo !empty($name) ? htmlspecialchars($name) . ' profilja' : 'Ismeretlen felhasználó'; ?></h2>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profilkép" class="profile-img" width="150">

    <div class="profile-details">
        <h3>Bemutatkozás</h3>
        <p><?php echo !empty($bio) ? nl2br(htmlspecialchars($bio)) : 'Nincs megadva.'; ?></p>

        <h3>Elérhetőség</h3>
        <p><?php echo !empty($contact) ? htmlspecialchars($contact) : 'Nincs megadva.'; ?></p>
    </div>

    <hr>

    <h3>Feltöltött önkéntes munkák</h3>
    <?php if ($userJobs): ?>
        <div class="job-feed">
            <?php foreach ($userJobs as $job): ?>
                <div class="job-card">
                    <?php if (!empty($job['job_image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($job['job_image']); ?>" alt="Munka képe" class="job-image">
                    <?php endif; ?>

                    <div class="job-content">
                        <h4><?php echo htmlspecialchars($job['title']); ?></h4>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="apply-btn">Jelentkezés</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Ez a felhasználó még nem töltött fel munkát.</p>
    <?php endif; ?>

    <br><a href="dashboard.php" class="back-link">Vissza a főoldalra</a>
</div>

<?php include('templates/footer.php'); ?>
