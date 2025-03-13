
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        
        if (data.success) {
            window.location.href = "home.html";
            console.log("Sikeres bejelentkezés",data.user_id);
        }
        else {
            alert('Login failed:'+ data.message); //Hibaüzenet kiírása
           
        }
       
    })
    .catch(error => console.error('Error:', error));

});