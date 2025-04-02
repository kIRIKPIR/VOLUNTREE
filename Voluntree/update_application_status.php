<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$application_id = $_POST['application_id'] ?? null;
$new_status = $_POST['status'] ?? null;

if (!$application_id || !in_array($new_status, ['accepted', 'rejected'])) {
    echo "Érvénytelen kérés.";
    exit();
}

// Ellenőrizzük, hogy a jelentkezés a felhasználó saját munkájára érkezett-e
$stmt = $pdo->prepare("SELECT applications.job_id, applications.user_id AS applicant_id, jobs.user_id AS job_owner_id, jobs.title FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.id = ?");
$stmt->execute([$application_id]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application || $application['job_owner_id'] != $user_id) {
    echo "Nincs jogosultságod ehhez a művelethez.";
    exit();
}

// Státusz frissítése
$update = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
$update->execute([$new_status, $application_id]);

// Értesítés küldése a jelentkezőnek
$applicant_id = $application['applicant_id'];
$job_title = $application['title'];
$sender_name = $_SESSION['user_name'];

$message = ($new_status === 'accepted') 
    ? "$sender_name elfogadta a jelentkezésed a(z) '$job_title' munkára."
    : "$sender_name elutasította a jelentkezésed a(z) '$job_title' munkára.";

$stmtNotify = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
$stmtNotify->execute([$applicant_id, $message]);

header("Location: applicants.php");
exit();
?>
