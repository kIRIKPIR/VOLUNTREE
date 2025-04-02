<?php
include('connect.php'); // Adatbázis kapcsolat

// Ha POST kérést küldenek (azaz a felhasználó megpróbál bejelentkezni)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ellenőrizzük, hogy a megadott e-mail cím létezik-e az adatbázisban
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Ha nem találunk felhasználót a megadott e-mail címmel
    if (!$user) {
        echo "Hibás e-mail cím vagy jelszó!";
        exit();
    }

    // Ellenőrizzük a jelszót
    if (!password_verify($password, $user['password'])) {
        echo "Hibás e-mail cím vagy jelszó!";
        exit();
    }

    // Sikeres bejelentkezés esetén session indítása
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    
    // Átirányítás a főoldalra (például dashboard.php)
    header("Location: dashboard.php");
    exit();
}
?>

<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Jelszó" required>
    <button type="submit">Bejelentkezés</button>
</form>