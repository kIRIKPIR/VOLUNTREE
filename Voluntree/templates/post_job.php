<?php
include('connect.php'); // Adatbázis kapcsolat
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ha az űrlap adatokat küldött, akkor dolgozzunk fel
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $image_url = $_FILES['image']['name'] ?? null;

    // Kép feltöltés kezelése
    if ($image_url) {
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image_url);
    }

    // Munka hozzáadása az adatbázisba
    $stmt = $pdo->prepare("INSERT INTO jobs (user_id, title, description, image_url, deadline) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $image_url, $deadline]);

    header("Location: dashboard.php"); // Átirányítás a főoldalra
    exit();
}
?>

<form action="post_job.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Munka címe" required>
    <textarea name="description" placeholder="Munka leírása" required></textarea>
    <input type="file" name="image">
    <input type="datetime-local" name="deadline" required>
    <button type="submit">Munka feltöltése</button>
</form>
