<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onkentes_munka";

//Adatbáziskapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
//error_reporting(E_ALL); ini_set('display_errors', 1);
?>