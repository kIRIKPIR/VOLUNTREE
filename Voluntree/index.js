document.addEventListener("DOMContentLoaded", function () {
    let searchBar = document.getElementById("search-bar");
    let searchResults = document.querySelector(".search-results");
    let selectedIndex = -1; // Nyíl gombokhoz

    // Keresés esemény
    searchBar.addEventListener("input", function () {
        let query = searchBar.value.trim();

        if (query.length > 0) {
            fetch(search.php?q=$:{query})
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach((job, index) => {
                            let div = document.createElement("div");
                            div.textContent = job.name;
                            div.classList.add("search-item");
                            div.dataset.id = job.id;

                            // Kattintásra kitölti a keresőmezőt
                            div.addEventListener("click", function () {
                                searchBar.value = job.name;
                                searchResults.style.display = "none";
                            });

                            searchResults.appendChild(div);
                        });
                        searchResults.style.display = "block";
                    } else {
                        searchResults.style.display = "none";
                    }
                });
        } else {
            searchResults.style.display = "none";
        }
    });

    // Nyíl gombok kezelése
    searchBar.addEventListener("keydown", function (e) {
        let items = document.querySelectorAll(".search-item");

        if (e.key === "ArrowDown") {
            selectedIndex = (selectedIndex + 1) % items.length;
            updateSelection(items);
        } else if (e.key === "ArrowUp") {
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateSelection(items);
        } else if (e.key === "Enter" && selectedIndex >= 0) {
            e.preventDefault();
            searchBar.value = items[selectedIndex].textContent;
            searchResults.style.display = "none";
            selectedIndex = -1;
        }
    });

    // Kijelölt elem kiemelése
    function updateSelection(items) {
        items.forEach(item => item.classList.remove("selected"));
        if (items.length > 0) {
            items[selectedIndex].classList.add("selected");
        }
    }
});