<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch user details based on email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error_message'] = "Database error: " . $conn->error;
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_logged_in'] = true;
            $_SESSION['success_message'] = "Login successful! Welcome, " . $user['username'];

            // Check if the user was redirected to checkout earlier
            if (isset($_SESSION['redirect_to_checkout']) && $_SESSION['redirect_to_checkout'] === true) {
                unset($_SESSION['redirect_to_checkout']);
                header("Location: process_checkout.php");
                exit();
            } else {
                header("Location: index.php"); // Redirect to homepage after login
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Invalid password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email!";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
