<?php
session_start();

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Database connection and game fetching logic
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all games for the catalog page
$all_games = $conn->query("SELECT * FROM games");

// Check if there's a session message for adding a game
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);  // Clear the message after showing the alert
}

// Calculate the total items in the cart
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? $item['quantity'] : 0;
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"> <!-- AOS Library -->
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

    <!-- Games Section -->
    <section id="games-section" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4" data-aos="fade-up">All Games</h2>
            <p class="text-center mb-4">Discover our diverse collection of online and offline games!</p>
            <div class="row g-4">
                <?php if ($all_games && $all_games->num_rows > 0): ?>
                    <?php while ($game = $all_games->fetch_assoc()): ?>
                        <div class="col-md-3" data-aos="fade-up">
                            <div class="card shadow-lg">
                                <img src="../nextlvl_images/<?= htmlspecialchars($game['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($game['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($game['title']); ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($game['description']); ?></p>
                                    <p class="card-text text-primary">Price: ₱<?= number_format($game['price'], 2); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="game_details.php?id=<?= htmlspecialchars($game['game_id']); ?>" class="btn btn-warning">Learn More</a>
                                        <a href="add_to_cart.php?game_id=<?= htmlspecialchars($game['game_id']); ?>" class="btn btn-warning add-to-cart">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No games available at the moment.</p>
                <?php endif; ?>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../js/script.js"></script>
    <script>
        AOS.init({
            duration: 1200,
            once: true
        });
    </script>
</body>

</html>