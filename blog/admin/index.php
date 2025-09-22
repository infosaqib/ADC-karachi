<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
   <style>
    #login {
    background: url('https://images.unsplash.com/photo-1439792675105-701e6a4ab6f0?w=1200&auto=format&fit=crop&q=60') no-repeat center / cover;
    min-height: 100vh;
  }
   </style>
</head>

<body>
    <section id="login" class="flex items-center justify-center px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full mt-10 mb-10 backdrop-blur-sm bg-opacity-90">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Admin Login</h2>
            <form id="login-form" action="login.php" method="POST" novalidate>
                <div class="mb-4">
                    <label for="username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-600 focus:outline-none"
                        placeholder="Enter your username" />
                    <p class="mt-1 text-sm text-red-600 hidden" id="usernameError">Username is required.</p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-600 focus:outline-none"
                        placeholder="••••••••" />
                    <p class="mt-1 text-sm text-red-600 hidden" id="passwordError">Password must be at least 6 characters.</p>
                </div>
                <button type="submit"
                    class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2.5 rounded-lg shadow transition duration-300">
                    Sign In
                </button>
            </form>
            <div class="error-message" id="login-error"></div>
        </div>
    </section>

    <script>
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
    </script>
</body>

</html>
