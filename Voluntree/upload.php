<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $description = $_POST["description"];
    $image = "";

    // Kép feltöltése
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]); 
        $target_file = $target_dir . $image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Csak bizonyos fájltípusokat engedélyezünk
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $success = "A kép sikeresen feltöltve.";
            } else {
                $error = "Hiba történt a kép feltöltése közben.";
            }
        } else {
            $error = "Csak JPG, JPEG, PNG és GIF formátumokat fogadunk el.";
        }
    }

    // Ha nincs hiba, adatbázisba mentés
    if (!$error) {
        $sql = "INSERT INTO jobs (user_id, description, image, approved) VALUES (?, ?, ?, 0)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id, $description, $image])) {
            $success = "A munka sikeresen feltöltve, jóváhagyásra vár.";
        } else {
            $error = "Hiba történt az adatbázisba mentés során.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munka feltöltése</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Munka feltöltése</h1>
        <a href="home.php">Vissza a főoldalra</a>
    </header>

    <main>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="description">Leírás:</label>
            <textarea id="description" name="description" placeholder="Adj meg leírást a munkához" required></textarea>

            <label for="image">Kép feltöltése:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Feltöltés</button>
        </form>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
    </main>

</body>
</html>