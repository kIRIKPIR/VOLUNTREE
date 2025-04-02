<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['job_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_GET['job_id'];

// Ellenőrizzük, hogy a felhasználó nem a saját munkájára próbál-e jelentkezni
$stmtJob = $pdo->prepare("SELECT user_id FROM jobs WHERE id = ?");
$stmtJob->execute([$job_id]);
$job = $stmtJob->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "A munka nem található.";
    exit();
}

if ($job['user_id'] == $user_id) {
    echo "Nem tudsz általad feltöltött munkára jelentkezni.";
    exit();
}

// Ellenőrizzük, hogy már jelentkezett-e erre a munkára
$stmtCheck = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
$stmtCheck->execute([$user_id, $job_id]);

if ($stmtCheck->fetch()) {
    echo "Már jelentkeztél erre a munkára.";
    exit();
}

// Jelentkezés mentése
$stmt = $pdo->prepare("INSERT INTO applications (user_id, job_id, applied_at) VALUES (?, ?, NOW())");
$stmt->execute([$user_id, $job_id]);

// Munka feltöltőjének lekérdezése és értesítés létrehozása
$stmtPoster = $pdo->prepare("SELECT user_id FROM jobs WHERE id = ?");
$stmtPoster->execute([$job_id]);
$jobPoster = $stmtPoster->fetch();

if ($jobPoster) {
    $poster_id = $jobPoster['user_id'];
    $message = $_SESSION['user_name'] . " jelentkezett a(z) \"" . $job_id . "\" számú munkádra.";

    $stmtNotify = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmtNotify->execute([$poster_id, $message]);
}

header("Location: dashboard.php");
exit();
?>
