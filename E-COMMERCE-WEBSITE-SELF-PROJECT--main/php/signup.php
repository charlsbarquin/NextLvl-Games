<?php
session_start(); // Start session for messages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css"> <!-- Shared CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
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

        <!-- Signup Form -->
        <div class="form-container shadow p-4 rounded bg-white">
            <h2 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; color: #6a11cb;">Sign Up</h2>
            <form action="signup_process.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" style="font-family: 'Poppins', sans-serif; font-weight: 400;" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" style="font-family: 'Poppins', sans-serif; font-weight: 400;" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" style="font-family: 'Poppins', sans-serif; font-weight: 400;" required>
                        <button type="button" class="btn" id="toggle-password" style="font-family: 'Poppins', sans-serif; background-color: #ffea00; color: #333; border: none;">Show</button>
                    </div>
                </div>
                <button type="submit" class="btn w-100" style="background: linear-gradient(90deg, #6a11cb, #2575fc); color: white; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; border: none; border-radius: 5px;">Sign Up</button>
                <div class="text-center mt-3">
                    <p style="font-family: 'Poppins', sans-serif;">Already have an account? <a href="login.php" style="color: #6a11cb; text-decoration: none;">Log In</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
