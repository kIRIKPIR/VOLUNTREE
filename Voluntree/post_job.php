<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $job_image = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $job_image = basename($_FILES["image"]["name"]);
    }

    $stmt = $pdo->prepare("INSERT INTO jobs (user_id, title, description, job_image, deadline, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $title, $description, $job_image, $deadline]);

    header("Location: dashboard.php");
    exit();
}
?>

<?php include('templates/header.php'); ?>

<div class="post-job-form">
    <h2>Új önkéntes munka feltöltése</h2>
    <form action="post_job.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Cím" required>
        <textarea name="description" placeholder="Leírás" required></textarea>
        <input type="file" name="image">
        <label>Jelentkezési határidő:</label>
        <input type="date" name="deadline" required>
        <button type="submit">Feltöltés</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>
