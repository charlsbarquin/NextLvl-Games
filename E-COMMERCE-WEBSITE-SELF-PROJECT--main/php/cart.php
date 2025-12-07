<?php
session_start();

// Assuming you're connected to your database
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NextLvl Games - Your ultimate destination for the best online and offline games.">
    <meta name="keywords" content="NextLvl, games, online games, offline games, best deals, gaming community">
    <meta name="author" content="NextLvl Team">
    <title>NextLvl Games</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/games.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/game_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../nextlvl_images/nextlvl-logo.jpg" alt="NextLvl Logo" width="50" height="50" class="me-2">
                NextLvl Games
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="gamesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Games
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="gamesDropdown">
                            <li>
                                <a class="dropdown-item" href="featured.php">Featured Games</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="recommended.php">Recommended</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="latest.php">Latest Games</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="special-offers.php">Special Offers</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <?php if (isset($_SESSION['username'])): ?>
                                <?= htmlspecialchars($_SESSION['username']); ?>
                            <?php else: ?>
                                Account
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <?php if (isset($_SESSION['username'])): ?>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="login.php">Log In</a></li>
                                <li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            Cart
                            <!-- Cart Indicator -->
                            <?php
                            $cart_count = 0;

                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                // Calculate the total quantity of all items in the cart
                                foreach ($_SESSION['cart'] as $item) {
                                    $cart_count += $item['quantity'];
                                }
                            } else {
                                // Reset cart count to 0 if the cart is empty
                                $_SESSION['cart'] = [];
                            }

                            if ($cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $cart_count; ?>
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            <?php endif; ?>

                        </a>
                    </li>
                </ul>
                <form class="d-flex ms-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="text" name="query" placeholder="Search games" aria-label="Search" required>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <section id="cart" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Your Cart</h2>

            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Game</th>
                                <th scope="col">Image</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $game_id => $game_info) {
                                $query = "SELECT * FROM games WHERE game_id = $game_id";
                                $result = mysqli_query($conn, $query);
                                $game = mysqli_fetch_assoc($result);
                                $game_name = $game['title'];
                                $game_price = $game['price'];
                                $game_image = $game['image'];

                                $subtotal = $game_price * $game_info['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($game_name); ?></td>
                                    <td>
                                        <img src="../nextlvl_images/<?php echo htmlspecialchars($game_image); ?>" alt="<?php echo htmlspecialchars($game_name); ?>" class="img-fluid" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                    <td>₱<?php echo number_format($game_price, 2); ?></td>
                                    <td><?php echo $game_info['quantity']; ?></td>
                                    <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <a href="remove_from_cart.php?game_id=<?php echo $game_id; ?>" class="btn buy-now">Remove</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="mt-4 text-end">
                    <h4 class="mb-3">Total: ₱<?php echo number_format($total, 2); ?></h4>
                    <a href="index.php" class="btn btn-warning add-to-cart">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-warning px-4 py-2 ms-2">Proceed to Checkout</a> <!-- Increased margin with ms-4 -->
                </div>

            <?php else: ?>
                <div class="text-center">
                    <p class="lead">Your cart is empty.</p>
                    <a href="index.php" class="btn btn-warning add-to-cart">Continue Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>