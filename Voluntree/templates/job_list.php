<!-- job_list.php -->
<h2>Önkéntes munkák</h2>
<ul>
    <?php foreach ($jobs as $job): ?>
        <li>
            <strong><?php echo htmlspecialchars($job['title']); ?></strong><br>
            <?php echo htmlspecialchars($job['description']); ?><br>
            <a href="apply.php?job_id=<?php echo $job['id']; ?>">Jelentkezés</a>
        </li>
    <?php endforeach; ?>
</ul>
