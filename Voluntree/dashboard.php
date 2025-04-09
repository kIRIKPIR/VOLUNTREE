<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

include('connect.php');

// Felhasználó profilképének lekérése
$stmtUser = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$userData = $stmtUser->fetch();
$profile_image = $userData['profile_picture'] ?? 'default_profile.jpg';

// Munkák lekérése a hírcsatornához (időrendi sorrendben)
$stmtJobs = $pdo->query("SELECT jobs.*, users.name AS poster_name, users.profile_picture AS poster_image FROM jobs JOIN users ON jobs.user_id = users.id ORDER BY jobs.created_at DESC");
$jobs = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/dashboard.css">

<div class="dashboard">
    <!-- Felhasználói profil sáv -->
    <div class="profile-header">
        <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profilkép" class="profile-img">
        <h2><?php echo htmlspecialchars($user_name); ?></h2>
    </div>

    <!-- Munkák hírcsatornája -->
    <div class="job-feed">
        <?php if ($jobs): ?>
            <?php foreach ($jobs as $job): ?>
                <?php
                $deadline = new DateTime($job['deadline']);
                $now = new DateTime();
                $interval = $now->diff($deadline);
                $daysLeft = (int)$interval->format('%r%a');
                $deadlineColor = 'green';
                if ($daysLeft < 0) {
                    $deadlineColor = 'red';
                } elseif ($daysLeft <= 2) {
                    $deadlineColor = 'orange';
                }
                ?>
                <div class="job-card">
                    <!-- Munka feltöltőjének profilja -->
                    <div class="poster-info">
                        <img src="uploads/<?php echo htmlspecialchars($job['poster_image'] ?? 'default_profile.jpg'); ?>" alt="Feltöltő profilképe" class="poster-img">
                        <a href="view_profile.php?user_id=<?php echo $job['user_id']; ?>">
                            <span><?php echo htmlspecialchars($job['poster_name']); ?></span>
                        </a>
                    </div>

                    <!-- Munka tartalom -->
                    <?php if (!empty($job['job_image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($job['job_image']); ?>" alt="Munka képe" class="job-image">
                    <?php endif; ?>

                    <div class="job-content">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <p><strong>Kategória:</strong> <?php echo htmlspecialchars($job['category'] ?? 'Egyéb'); ?></p>
                        <p style="color: <?php echo $deadlineColor; ?>; font-weight: bold;">
                            Jelentkezési határidő: <?php echo htmlspecialchars($job['deadline']); ?>
                        </p>
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="apply-btn">Jelentkezés</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Jelenleg nincs elérhető munka.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('templates/footer.php'); ?>
