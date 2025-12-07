<?php
session_start();

include 'db_config.php';

// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists
        $_SESSION['error_message'] = "Email is already registered. Please log in.";
        header("Location: signup.php");
        exit();
    }

    $stmt->close();

    // Insert data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error_message'] = "Database error: " . $conn->error;
        header("Location: signup.php");
        exit();
    }

    $stmt->bind_param('sss', $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: login.php");
    } else {
        $_SESSION['error_message'] = "Error: Unable to complete registration.";
        header("Location: signup.php");
    }

    $stmt->close();
}
$conn->close();
?>
