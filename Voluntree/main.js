document.addEventListener("DOMContentLoaded", function () {
    let applyButtons = document.querySelectorAll(".apply-btn");

    applyButtons.forEach(button => {
        button.addEventListener("click", function () {
            let jobId = this.getAttribute("data-job");

            fetch("apply.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "job_id=" + jobId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Sikeresen jelentkeztél a munkára!");
                } else {
                    alert("Hiba történt: " + data.error);
                }
            })
            .catch(error => console.error("Hiba:", error));
        });
    });
});