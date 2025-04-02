<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Felhasználói adatok lekérése
$stmt = $pdo->prepare("SELECT name, email, profile_picture, bio, contact FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$profile_picture = !empty($user['profile_picture']) ? "uploads/" . $user['profile_picture'] : "uploads/default_profile.jpg";

// Profil frissítése
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = $_POST['bio'] ?? '';
    $contact = $_POST['contact'] ?? '';

    // Ha van új profilkép
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $new_picture = basename($_FILES["profile_picture"]["name"]);
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ?, bio = ?, contact = ? WHERE id = ?");
            $stmt->execute([$new_picture, $bio, $contact, $user_id]);
            $profile_picture = "uploads/" . $new_picture;
        }
    } else {
        $stmt = $pdo->prepare("UPDATE users SET bio = ?, contact = ? WHERE id = ?");
        $stmt->execute([$bio, $contact, $user_id]);
    }

    header("Location: profile.php");
    exit();
}
?>

<?php include('templates/header.php'); ?>

<div class="profile-container">
    <h2>Profil szerkesztése</h2>
    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <div class="profile-image">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profilkép" class="profile-img" width="120">
            <input type="file" name="profile_picture">
        </div>

        <label>Bemutatkozás:</label>
        <textarea name="bio" rows="4" cols="50"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

        <label>Elérhetőség:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>">

        <button type="submit">Profil frissítése</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>
