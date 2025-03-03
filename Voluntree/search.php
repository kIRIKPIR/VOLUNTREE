<?php
require 'config.php'; // Az adatbázis kapcsolat

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    
    $stmt = $conn->prepare("SELECT id, munka_neve FROM munkak WHERE munka_neve LIKE ? LIMIT 5");
    $searchTerm = "%$query%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $jobs = [];

    while ($row = $result->fetch_assoc()) {
        $jobs[] = ["id" => $row['id'], "name" => $row['munka_neve']];
    }

    echo json_encode($jobs);
}
?>