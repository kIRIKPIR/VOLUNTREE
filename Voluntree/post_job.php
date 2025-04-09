<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $category = $_POST['category'] ?? 'Egyéb';
    $image_name = null;

    if (!empty($_FILES['job_image']['name'])) {
        $upload_dir = 'uploads/';
        $image_name = basename($_FILES['job_image']['name']);
        $target_file = $upload_dir . $image_name;
        move_uploaded_file($_FILES['job_image']['tmp_name'], $target_file);
    }

    $stmt = $pdo->prepare("INSERT INTO jobs (user_id, title, description, deadline, job_image, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $deadline, $image_name, $category]);

    header("Location: dashboard.php");
    exit();
}
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/post_job.css">

<div class="post-job-form">
    <h2>Új munka feltöltése</h2>
    <form action="post_job.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Cím" required>
        <textarea name="description" placeholder="Leírás" required></textarea>
        <input type="date" name="deadline" required>
        <label>Kategória:</label>
        <select name="category" required>
            <option value="Környezetvédelem">Környezetvédelem</option>
            <option value="Oktatás">Oktatás</option>
            <option value="Egészségügy">Egészségügy</option>
            <option value="Szociális munka">Szociális munka</option>
            <option value="Rendezvényszervezés">Rendezvényszervezés</option>
            <option value="Egyéb" selected>Egyéb</option>
        </select>
        <input type="file" name="job_image">
        <button type="submit">Feltöltés</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>
