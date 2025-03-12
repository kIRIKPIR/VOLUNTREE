<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: home.php"); // Sikeres bejelentkezés után főoldalra dob
            exit();
        } else {
            echo "Hibás jelszó.";
        }
    } else {
        echo "Nincs ilyen felhasználó.";
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Email cím" required><br>
    <input type="password" name="password" placeholder="Jelszó" required><br>
    <button type="submit">Bejelentkezés</button>
</form>