<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate the email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email already exists in the database
        $check_query = "SELECT * FROM newsletter_subscriptions WHERE email = '$email'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 0) {
            // Insert the email into the newsletter subscriptions table
            $query = "INSERT INTO newsletter_subscriptions (email) VALUES ('$email')";
            
            if ($conn->query($query) === TRUE) {
                // No email sending, just alert the user
                echo '<script>alert("Subscription successful! You will receive our newsletter shortly.");</script>';
            } else {
                echo '<script>alert("Error: ' . $conn->error . '");</script>'; // Display error if query fails
            }
        } else {
            echo '<script>alert("This email is already subscribed to the newsletter.");</script>';
        }
    } else {
        echo '<script>alert("Invalid email format. Please enter a valid email address.");</script>';
    }
}
?>
