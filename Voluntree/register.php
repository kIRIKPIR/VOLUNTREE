<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_picture = "default_profile.jpg";

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, profile_picture) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $profile_picture]);

    header("Location: login.php");
    exit();
}
?>

<?php include('templates/header.php'); ?>
<link rel="stylesheet" href="styles/auth.css">

<div class="auth-container">
    <h2>Regisztráció</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Név" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <button type="submit">Regisztráció</button>
    </form>
    <p style="text-align:center; margin-top: 10px;">
        Már van fiókod? <a href="login.php">Bejelentkezem</a>
    </p>
</div>

<?php include('templates/footer.php'); ?>
