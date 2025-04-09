<?php
include('connect.php');
include('templates/header.php');

$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT jobs.*, users.name AS poster_name, users.profile_picture AS poster_image FROM jobs JOIN users ON jobs.user_id = users.id WHERE (jobs.title LIKE :query OR jobs.description LIKE :query)";
$params = [':query' => "%$query%"];

if (!empty($category)) {
    $sql .= " AND jobs.category = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY jobs.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="search-results">
    <h2>Keresési eredmények</h2>

    <?php if ($jobs): ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <?php if (!empty($job['job_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($job['job_image']); ?>" alt="Munka képe" class="job-image">
                <?php endif; ?>

                <div class="job-content">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p><strong>Kategória:</strong> <?php echo htmlspecialchars($job['category'] ?? 'Egyéb'); ?></p>
                    <p><strong>Feltöltő:</strong> <?php echo htmlspecialchars($job['poster_name']); ?></p>
                    <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="apply-btn">Jelentkezés</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nincs találat a keresésre.</p>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
