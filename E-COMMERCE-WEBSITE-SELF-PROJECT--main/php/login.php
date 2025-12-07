<?php
session_start();  // Start the session at the top

// Assuming you are connecting to the database
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database for user
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists, start a session and redirect
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        // Redirect to the checkout page or any other page you want
        header("Location: checkout.php");
        exit();
    } else {
        // If login fails, show an error
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/signup.css"> <!-- Shared CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh; position: relative;">
        <!-- Alert Container -->
        <?php if (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])): ?>
            <div class="alert-container">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="custom-alert custom-alert-danger">
                        <?= $_SESSION['error_message']; ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="custom-alert custom-alert-success">
                        <?= $_SESSION['success_message']; ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="form-container shadow p-4 rounded bg-white">
            <h2 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; color: #6a11cb;">Log In</h2>
            <form action="login_process.php" method="POST">
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                        style="font-family: 'Poppins', sans-serif; font-weight: 400;" required>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"
                            style="font-family: 'Poppins', sans-serif; font-weight: 400;" required>
                        <button type="button" class="btn" id="toggle-password"
                            style="font-family: 'Poppins', sans-serif; background-color: #ffea00; color: #333; border: none;">Show</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn w-100"
                    style="background: linear-gradient(90deg, #6a11cb, #2575fc); color: white; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; border: none; border-radius: 5px;">
                    Log In
                </button>

                <!-- Sign Up Link -->
                <div class="text-center mt-3">
                    <p style="font-family: 'Poppins', sans-serif;">Don’t have an account?
                        <a href="signup.php" style="color: #6a11cb; text-decoration: none;">Sign Up</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- JavaScript for Password Toggle -->
        <script>
            const togglePasswordButton = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');

            togglePasswordButton.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePasswordButton.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        </script>

        <script src="../js/login.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
