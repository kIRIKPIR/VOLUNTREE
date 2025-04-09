<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];

// Ellenőrizzük, hogy a munka a bejelentkezett felhasználóhoz tartozik-e
$stmt = $pdo->prepare("SELECT id FROM jobs WHERE id = ? AND user_id = ?");
$stmt->execute([$job_id, $user_id]);
$job = $stmt->fetch();

if ($job) {
    // Jelentkezések törlése először (ha vannak)
    $pdo->prepare("DELETE FROM applications WHERE job_id = ?")->execute([$job_id]);

    // Majd a munka törlése
    $stmtDelete = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
    $stmtDelete->execute([$job_id]);
}

header("Location: dashboard.php");
exit();
?>
