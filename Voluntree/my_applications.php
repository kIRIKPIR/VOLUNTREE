<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Jelentkezések lekérése
$stmt = $pdo->prepare("SELECT applications.*, jobs.title, jobs.deadline, jobs.description, jobs.job_image, jobs.category, users.name AS poster_name FROM applications JOIN jobs ON applications.job_id = jobs.id JOIN users ON jobs.user_id = users.id WHERE applications.user_id = ? ORDER BY applications.applied_at DESC");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/my_applications.css">

<div class="my-applications">
    <h2>Jelentkezéseim</h2>

    <?php if ($applications): ?>
        <?php foreach ($applications as $app): ?>
            <div class="job-card">
                <?php if (!empty($app['job_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($app['job_image']); ?>" alt="Munka képe" class="job-image">
                <?php endif; ?>

                <div class="job-content">
                    <h3><?php echo htmlspecialchars($app['title']); ?></h3>
                    <p><?php echo htmlspecialchars($app['description']); ?></p>
                    <p><strong>Feltöltő:</strong> <?php echo htmlspecialchars($app['poster_name']); ?></p>
                    <p><strong>Kategória:</strong> <?php echo htmlspecialchars($app['category'] ?? 'Egyéb'); ?></p>
                    <p><strong>Határidő:</strong> <?php echo htmlspecialchars($app['deadline']); ?></p>
                    <p><strong>Státusz:</strong> 
                        <?php
                            $status = $app['status'];
                            $color = $status === 'accepted' ? 'green' : ($status === 'rejected' ? 'red' : 'gray');
                            echo "<span style='color: $color;'>" . ucfirst($status) . "</span>";
                        ?>
                    </p>
                    <p><strong>Jelentkezés ideje:</strong> <?php echo htmlspecialchars($app['applied_at']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Még nem jelentkeztél egyetlen munkára sem.</p>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
