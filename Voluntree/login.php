<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo "Hibás e-mail cím vagy jelszó!";
        exit();
    }

    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    header("Location: dashboard.php");
    exit();
}
?>
<link rel="stylesheet" href="styles/auth.css">

<div class="auth-container">
    <h2>Bejelentkezés</h2>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <button type="submit">Bejelentkezés</button>
    </form>
    <p style="text-align:center; margin-top: 10px;">
        Nincs még fiókod? <a href="register.php">Regisztrálok</a>
    </p>
</div>

<?php include('templates/footer.php'); ?>

