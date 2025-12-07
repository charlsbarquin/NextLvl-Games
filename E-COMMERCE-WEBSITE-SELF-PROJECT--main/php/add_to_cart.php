<?php
session_start();

// Assuming you're connected to your database
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add game to cart via GET method
if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];

    // Fetch game details from the database
    $query = "SELECT * FROM games WHERE game_id = $game_id";
    $result = mysqli_query($conn, $query);
    $game = mysqli_fetch_assoc($result);

    // Check if the game already exists in the cart
    if (isset($_SESSION['cart'][$game_id])) {
        // If it exists, increase the quantity
        $_SESSION['cart'][$game_id]['quantity']++;
    } else {
        // Otherwise, add it to the cart with a quantity of 1
        $_SESSION['cart'][$game_id] = [
            'title' => $game['title'],
            'price' => $game['price'],
            'quantity' => 1,
            'image' => $game['image']
        ];
    }

    // Set a session message to show the alert
    $_SESSION['message'] = "Game added to cart successfully!";
    
    // Redirect to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Add game to cart via POST method (for AJAX requests)
if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Fetch game details from the database
    $query = "SELECT * FROM games WHERE game_id = $game_id";
    $result = mysqli_query($conn, $query);
    $game = mysqli_fetch_assoc($result);

    // Check if the game already exists in the cart
    if (isset($_SESSION['cart'][$game_id])) {
        // If it exists, increase the quantity
        $_SESSION['cart'][$game_id]['quantity']++;
        $message = 'Game quantity increased!';
    } else {
        // Otherwise, add it to the cart with a quantity of 1
        $_SESSION['cart'][$game_id] = [
            'title' => $game['title'],
            'price' => $game['price'],
            'quantity' => 1,
            'image' => $game['image']
        ];
        $message = 'Game added to cart!';
    }

    // Return response as JSON
    echo json_encode([
        'success' => true,
        'message' => $message,
        'cart_count' => count($_SESSION['cart']) // Return the updated cart count
    ]);
    exit;
}
?>
