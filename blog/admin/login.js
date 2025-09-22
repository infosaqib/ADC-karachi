document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('login-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Fetch request to send username and password to the server
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Redirect to the admin dashboard if login is successful
                    window.location.href = 'admin-dashboard.php';
                } else {
                    // Show error message if login fails
                    document.querySelector('#login-error').textContent = 'Invalid username or password';
                }
            })
            .catch(error => {
                console.error('Error during login:', error);
            });
    });
});