document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Alapértelmezett küldés letiltása

    let formData = new FormData(this);
    let messageDiv = document.getElementById("message");

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => { console.log(data)
       /* if (data.startsWith("error:")) { console.log("error")
            console.log(error); messageDiv.innerHTML = <p class="error">${data.substring(6)}</p>;
        } else if (data.startsWith("success:")) {
           console.log("success"); messageDiv.innerHTML = <p class="success">${data.substring(8)}</p>;
            setTimeout(() => window.location.href = "login.html", 2000); // Átirányítás a login oldalra 2 mp után
        }*/
        window.location.href="login.html";
    })
    //.catch(error => console.error("Hiba:", error));
});