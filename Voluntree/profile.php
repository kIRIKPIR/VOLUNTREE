<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Profil frissítése
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bio = $_POST['bio'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $profile_picture = null;

    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = 'uploads/';
        $profile_picture = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $profile_picture;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);

        $stmt = $pdo->prepare("UPDATE users SET profile_picture = ?, bio = ?, contact = ? WHERE id = ?");
        $stmt->execute([$profile_picture, $bio, $contact, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET bio = ?, contact = ? WHERE id = ?");
        $stmt->execute([$bio, $contact, $user_id]);
    }

    header("Location: profile.php");
    exit();
}

// Jelenlegi adatok lekérése
$stmt = $pdo->prepare("SELECT name, email, profile_picture, bio, contact FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/profile.css">

<div class="profile-container">
    <h2>Profilom szerkesztése</h2>

    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default_profile.jpg'); ?>" alt="Profilkép">

    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <label for="profile_picture">Új profilkép:</label>
        <input type="file" name="profile_picture">

        <label for="bio">Bemutatkozás:</label>
        <textarea name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

        <label for="contact">Elérhetőség:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>">

        <button type="submit">Profil módosítása</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>
