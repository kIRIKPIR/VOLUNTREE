<?php
include 'db.php'; // Adatbázis kapcsolat

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Ellenőrizzük, hogy az email már létezik-e az adatbázisban
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "error: Az emailcímhez már tartozik egy felhasználói fiók.";
        exit();
    }

    // Jelszavak egyezésének ellenőrzése
    if ($password !== $confirm_password) {
        echo "error: A két jelszó nem egyezik.";
        exit();
    }

    // Jelszó titkosítása
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Felhasználó beszúrása az adatbázisba
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "success: Sikeres regisztráció!";
    } else {
        echo "error: Hiba történt a regisztráció során.";
    }

    $stmt->close();
    $conn->close();
}
?>