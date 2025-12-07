<?php
session_start();
include('db_config.php'); // Assuming you have this to connect to your database

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];

    // Get the game details from the database
    $query = "SELECT * FROM games WHERE game_id = '$game_id'";
    $result = mysqli_query($conn, $query);
    $game = mysqli_fetch_assoc($result);

    if ($game) {
        // Add the game to the cart
        if (isset($_SESSION['cart'][$game_id])) {
            // If the game is already in the cart, increase the quantity
            $_SESSION['cart'][$game_id]['quantity']++;
        } else {
            // If the game is not in the cart, add it with quantity 1
            $_SESSION['cart'][$game_id] = [
                'title' => $game['title'],
                'price' => $game['price'],
                'quantity' => 1
            ];
        }

        // Redirect to the checkout page
        header('Location: checkout.php');
        exit();
    }
}

// In case of any error (game not found or other issues), you can show an error message or redirect.
header('Location: index.php');
exit();
?>
