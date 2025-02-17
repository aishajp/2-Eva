document.getElementById('glucoseForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const glucoseLevel = document.getElementById('glucoseLevel').value;

    fetch('register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'glucoseLevel=' + glucoseLevel
    })
    .then(response => response.text())
    .then(data => {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-success';
        messageDiv.innerText = data;
        document.getElementById('glucoseForm').reset();
    })
    .catch(error => {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-danger';
        messageDiv.innerText = 'Error al registrar el nivel de glucosa.';
    });
});