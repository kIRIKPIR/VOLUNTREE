<?php
session_start();
include('connect.php');

// Ha már be van jelentkezve, átirányítjuk a dashboardra
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Regisztrációs logika
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $profile_picture = "default_profile.jpg"; // alapértelmezett kép

    // Ellenőrizzük, hogy az email már létezik-e
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "Ez az email cím már regisztrálva van!";
        exit();
    }

    // Új felhasználó mentése
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, profile_picture) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, $profile_picture]);

    // Átirányítás a login oldalra
    header("Location: login.php");
    exit();
}
?>

<?php include('templates/header.php'); ?>

<div class="register-form">
    <h2>Regisztráció</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Név" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <button type="submit">Regisztráció</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>
