<!-- footer.php -->
<footer>
    <p><a href="logout.php">Kilépés</a></p>
</footer>

<script>
    function searchJobs() {
        var query = document.getElementById('search').value;

        if (query.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search_jobs.php?query=" + query, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById('search-results').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        } else {
            document.getElementById('search-results').innerHTML = '';
        }
    }
</script>
</body>
</html>
