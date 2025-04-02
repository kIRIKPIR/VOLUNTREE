<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Felhasználó profilképének lekérése
$stmtUser = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$userData = $stmtUser->fetch();
$profile_picture = !empty($userData['profile_picture']) ? 'uploads/' . $userData['profile_picture'] : 'uploads/default_profile.jpg';

// Munkák lekérése a hírcsatornához
$stmtJobs = $pdo->query("SELECT jobs.*, users.name AS poster_name, users.profile_picture AS poster_image, users.id AS poster_id 
                         FROM jobs 
                         JOIN users ON jobs.user_id = users.id 
                         ORDER BY jobs.created_at DESC");
$jobs = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>

<div class="dashboard">
    <!-- Munka feltöltése gomb -->
    <div class="post-job-btn" style="text-align: right; margin: 20px;">
        <a href="post_job.php" class="btn" style="background-color: #4CAF50; color: white; padding: 10px 16px; text-decoration: none; border-radius: 6px;">Munka feltöltése</a>
    </div>

    <!-- Felhasználói profil sáv -->
    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profilkép" class="profile-img">
        <h2><?php echo htmlspecialchars($user_name); ?></h2>
    </div>

    <!-- Munkák hírcsatornája -->
    <div class="job-feed">
        <?php if ($jobs): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <!-- Feltöltő adatai -->
                    <div class="poster-info">
                        <img src="uploads/<?php echo htmlspecialchars($job['poster_image'] ?? 'default_profile.jpg'); ?>" alt="Feltöltő profilképe" class="poster-img">
                        <span>
                            <a href="view_profile.php?user_id=<?php echo $job['poster_id']; ?>">
                                <?php echo htmlspecialchars($job['poster_name']); ?>
                            </a>
                        </span>
                    </div>

                    <!-- Munka képe -->
                    <?php if (!empty($job['job_image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($job['job_image']); ?>" alt="Munka képe" class="job-image">
                    <?php endif; ?>

                    <!-- Munka tartalom -->
                    <div class="job-content">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="apply-btn">Jelentkezés</a>

                        <!-- Törlés gomb csak a feltöltőnek -->
                        <?php if ($job['poster_id'] == $user_id): ?>
                            <form action="delete_job.php" method="POST" onsubmit="return confirm('Biztosan törlöd ezt a munkát?');" style="margin-top: 10px;">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <button type="submit" class="delete-btn" style="background-color: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
                                    Törlés
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Jelenleg nincs elérhető munka.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('templates/footer.php'); ?>
