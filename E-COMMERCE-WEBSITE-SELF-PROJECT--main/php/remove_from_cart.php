<?php
session_start();

// Check if game_id is passed
if (isset($_GET['game_id'])) {
    $game_id = intval($_GET['game_id']); // Ensure game_id is an integer

    // Remove the game from the cart if it exists
    if (isset($_SESSION['cart'][$game_id])) {
        unset($_SESSION['cart'][$game_id]);
    }
}

// If game_id is missing or invalid, redirect with an error message
else {
    $_SESSION['error'] = "Invalid game ID!";
}

// Redirect back to the cart page
header("Location: cart.php");
exit;
?>
