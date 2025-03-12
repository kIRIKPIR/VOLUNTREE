<?php
session_start();
require 'db.php'; // Adatbázis kapcsolat

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['mail']);
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Jelszó titkosítása    

    // Ellenőrizzük, hogy az email vagy a felhasználónév már létezik-e
    $check_sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Hiba: Ez az email vagy felhasználónév már létezik!";
    } else {
        // Új felhasználó mentése az adatbázisba
        $sql = "INSERT INTO users (username, email, password) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Sikeres regisztráció! Most már bejelentkezhetsz.";
        } else {
            echo "Hiba történt a regisztráció során.";
        }
    }
}
?>
