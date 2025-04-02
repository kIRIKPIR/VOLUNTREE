<?php
require_once 'connect.php';

if (isset($_GET['query'])) {
    $search = "%" . $_GET['query'] . "%";
    
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE title LIKE ? OR description LIKE ?");
    $stmt->execute([$search, $search]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $job) {
            echo "<div class='job-result'>";
            echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
            echo "<p>" . htmlspecialchars($job['description']) . "</p>";
            echo "<a href='apply.php?job_id=" . $job['id'] . "' class='apply-btn'>Jelentkezés</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Nincs találat.</p>";
    }
} else {
    echo "<p>Kérlek, adj meg egy keresési kifejezést.</p>";
}
?>
