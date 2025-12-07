<?php
session_start();

// Assuming you're connected to your database
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the order ID is passed in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the orders table
    $order_query = "SELECT * FROM orders WHERE order_id = $order_id";
    $order_result = $conn->query($order_query);

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();

        // Correct date formatting
        $order_date = !empty($order['order_date']) && strtotime($order['order_date']) > 0 ? date("F j, Y", strtotime($order['order_date'])) : "N/A";

        // Fetch the order items from the order_items table
        $items_query = "SELECT oi.*, g.title AS game_title, g.price AS game_price, g.image AS game_image FROM order_items oi
                        JOIN games g ON oi.game_id = g.game_id
                        WHERE oi.order_id = $order_id";
        $items_result = $conn->query($items_query);
    } else {
        echo "Order not found.";
        exit();
    }
} else {
    echo "Invalid order ID.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/games.css">
    <link rel="stylesheet" href="../css/order_confirmation.css">
    <link rel="stylesheet" href="../css/games.css">
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
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count"
                                style="<?php echo isset($_SESSION['cart']) && !empty($_SESSION['cart']) ? 'display: block;' : 'display: none;'; ?>">
                                <?= $cart_count; ?>
                                <span class="visually-hidden">items in cart</span>
                            </span>
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

    <div class="order-summary">
        <div class="header">
            <h2>Order Confirmation</h2>
        </div>

        <div class="details-section">
            <div class="order-details">
                <h4>Order Details</h4>
                <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                <p><strong>Order Date:</strong> <?php echo $order_date; ?></p>
                <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Subtotal:</strong> ₱<?php echo number_format($order['subtotal'], 2); ?></p>
                <p><strong>Tax (12%):</strong> ₱<?php echo number_format($order['tax'], 2); ?></p>
                <p><strong>Total:</strong> ₱<?php echo number_format($order['total'], 2); ?></p>
            </div>

            <div class="order-items">
                <h4>Order Items</h4>
                <?php if ($items_result->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Game</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $order_total = 0;
                            while ($item = $items_result->fetch_assoc()) {
                                $item_total = $item['game_price'] * $item['quantity'];
                                $order_total += $item_total;
                            ?>
                            <tr>
                                <td><img src="../nextlvl_images/<?php echo htmlspecialchars($item['game_image']); ?>" alt="<?php echo htmlspecialchars($item['game_title']); ?>" class="game-image"></td>
                                <td><?php echo htmlspecialchars($item['game_title']); ?></td>
                                <td>₱<?php echo number_format($item['game_price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₱<?php echo number_format($item_total, 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No items found for this order.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="thank-you">
            <p>Thank you for your order, <strong><?php echo htmlspecialchars($order['name']); ?></strong>! Your order is being processed.</p>
            <p>You will receive an email confirmation shortly.</p>
            <a href="index.php" class="btn btn-warning add-to-cart">Return to Home</a>
        </div>
    </div>

    <footer class="footer py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>NextLvl Games is the premier destination for online and offline games, bringing you the best
                        deals and an incredible community.</p>
                </div>
                <div class="col-md-4">
                    <h5>Subscribe to Newsletter</h5>
                    <form>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Subscribe</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li>Email: support@nextlvl.com</li>
                        <li>Phone: +1 123-456-7890</li>
                        <li>Address: 123 Gaming St, Game City</li>
                    </ul>
                    <div class="social-links mt-2">
                        <a href="#" class="text-white me-2" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-2" title="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-2" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white me-2" title="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
