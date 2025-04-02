<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lekérjük az összes munkát, amit a bejelentkezett felhasználó töltött fel
$stmtJobs = $pdo->prepare("SELECT * FROM jobs WHERE user_id = ? ORDER BY created_at DESC");
$stmtJobs->execute([$user_id]);
$jobs = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/header.php'); ?>

<div class="applicants-page">
    <h2>Jelentkezők a munkáidra</h2>

    <?php if ($jobs): ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-block">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>

                <?php
                // Jelentkezők lekérése ehhez a munkához
                $stmtApplications = $pdo->prepare("SELECT applications.*, users.name, users.email, users.profile_picture FROM applications JOIN users ON applications.user_id = users.id WHERE applications.job_id = ?");
                $stmtApplications->execute([$job['id']]);
                $applicants = $stmtApplications->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if ($applicants): ?>
                    <ul class="applicant-list">
                        <?php foreach ($applicants as $applicant): ?>
                            <li class="applicant-item">
                                <img src="uploads/<?php echo htmlspecialchars($applicant['profile_picture'] ?? 'default_profile.jpg'); ?>" alt="Profilkép" width="50" style="border-radius: 50%;">
                                <strong><?php echo htmlspecialchars($applicant['name']); ?></strong> - <?php echo htmlspecialchars($applicant['email']); ?>
                                <br>
                                <span>Állapot: 
                                    <?php
                                    $status = $applicant['status'];
                                    $color = $status === 'accepted' ? 'green' : ($status === 'rejected' ? 'red' : 'gray');
                                    echo "<span style='color: $color;'>" . ucfirst($status) . "</span>";
                                    ?>
                                </span>

                                <?php if ($status === 'pending'): ?>
                                    <form action="update_application_status.php" method="POST" style="display:inline-block; margin-left:10px;">
                                        <input type="hidden" name="application_id" value="<?php echo $applicant['id']; ?>">
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" style="color: green;">Elfogadás</button>
                                    </form>

                                    <form action="update_application_status.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="application_id" value="<?php echo $applicant['id']; ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" style="color: red;">Elutasítás</button>
                                    </form>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nincs jelentkező erre a munkára.</p>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Még nem töltöttél fel munkát.</p>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
