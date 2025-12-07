<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $total = 0;
        foreach ($_SESSION['cart'] as $game_id => $game_info) {
            $total += $game_info['quantity'] * $game_info['price'];
        }

        // Prepare order details
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $payment_method = $conn->real_escape_string($_POST['payment_method']);
        $status = 'Pending';
        $subtotal = $total;
        $tax = $subtotal * 0.12; // 12% tax
        $total_with_tax = $subtotal + $tax;

        // Insert into orders table
        $order_query = "INSERT INTO orders (name, email, payment_method, status, subtotal, tax, total) 
                        VALUES ('$name', '$email', '$payment_method', '$status', '$subtotal', '$tax', '$total_with_tax')";
        if ($conn->query($order_query) === TRUE) {
            $order_id = $conn->insert_id;

            // Insert into order_items table
            foreach ($_SESSION['cart'] as $game_id => $game_info) {
                $quantity = $game_info['quantity'];
                $price = $game_info['price'];

                $order_item_query = "INSERT INTO order_items (order_id, game_id, quantity, price) 
                                     VALUES ('$order_id', '$game_id', '$quantity', '$price')";
                $conn->query($order_item_query);
            }

            unset($_SESSION['cart']); // Clear the cart
            header("Location: order_confirmation.php?order_id=$order_id");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "Your cart is empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NextLvl Games</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/games.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/checkout-system.css">
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

    <!-- Checkout Section -->
    <section id="checkout" class="py-5 bg-light">
        <div class="container">
        <h2 class="text-center mb-4 fs-2">Checkout</h2>

            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <!-- Order Summary -->
                <div class="mb-4">
                    <h4 class="mb-3">Order Summary</h4>
                    <table class="table table-bordered checkout-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Image</th>
                                <th>Game</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
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
                                $game_image = $game['image']; // Assuming 'image' is the column name in the 'games' table

                                $subtotal = $game_price * $game_info['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <img src="../nextlvl_images/<?php echo htmlspecialchars($game_image); ?>" alt="<?php echo htmlspecialchars($game_name); ?>" class="img-fluid" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                    <td><?php echo htmlspecialchars($game_name); ?></td>
                                    <td><?php echo $game_info['quantity']; ?></td>
                                    <td>₱<?php echo number_format($game_price, 2); ?></td>
                                    <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-start">Subtotal</th>
                                <td class="text-start">₱<?php echo number_format($total, 2); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-start">Tax (12%)</th>
                                <td class="text-start">₱<?php echo number_format($total * 0.12, 2); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-start">Total</th>
                                <td class="text-start">₱<?php echo number_format($total + ($total * 0.12), 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Checkout Form -->
                <form method="POST" action="">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>Select a Payment Method</option>
                            <option value="gcash">GCash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="grabpay">GrabPay</option>
                            <option value="maya">Maya</option>
                            <option value="paymaya">PayMaya</option>
                        </select>
                    </div>
                    <div id="additional-fields" style="display: none;"></div>

                    <!-- Buttons: Cancel Order and Place Order with the same gap as example -->
                    <div class="d-flex justify-content-end mt-4">
                        <a href="index.php" class="btn btn-warning add-to-cart">Cancel Order</a> <!-- Use me-2 for margin between buttons -->
                        <button type="submit" class="btn btn-warning px-4 py-2 ms-2" name="place_order">Place Order</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">Your cart is empty. <a href="index.php">Continue Shopping</a></p>
            <?php endif; ?>
        </div>
    </section>

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

    <script src="../js/checkout-system.js"></script>
</body>

</html>