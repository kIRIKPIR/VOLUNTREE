<div class="search-container">
    <input type="text" id="searchInput" placeholder="Keress önkéntes munkát..." autocomplete="off">
    <div id="searchResults" class="search-results"></div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let query = this.value.trim();

    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = "";
        return;
    }

    fetch(`search_jobs.php?query=${encodeURIComponent(query)}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            let resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = "";

            if (data.length > 0) {
                let list = document.createElement('ul');
                data.forEach(job => {
                    let item = document.createElement('li');
                    let link = document.createElement('a');
                    link.href = `job_details.php?job_id=${job.id}`;
                    link.textContent = job.title;
                    item.appendChild(link);
                    list.appendChild(item);
                });
                resultsDiv.appendChild(list);
            } else {
                resultsDiv.innerHTML = "<p>Nincs találat.</p>";
            }
        });
});
</script>

<style>
.search-container {
    position: relative;
    max-width: 400px;
    margin: 20px auto;
}

#searchInput {
    width: 100%;
    padding: 10px;
