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

    // Fetch featured games
    $featured_games = $conn->query("SELECT * FROM games WHERE category_id = 1 LIMIT 3");

    // Fetch recommended games
    $recommended_games = $conn->query("SELECT * FROM games WHERE category_id = 2 LIMIT 3");

    // Fetch latest games
    $latest_games = $conn->query("SELECT * FROM games WHERE category_id = 3 LIMIT 3");

    // Fetch special offers
    $special_offers = $conn->query("SELECT * FROM special_offers LIMIT 3"); // Fetch 3 offers

    // Ensure the cart is initialized as an empty array if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

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

        <!-- Hero Section -->
        <header id="hero" class="hero text-center text-white d-flex align-items-center justify-content-center">
            <div class="container" data-aos="fade-up">
                <h1 class="display-4 animate-title">Welcome to <span class="text-warning">NextLvl Games</span></h1>
                <p class="lead animate-fade">Your ultimate destination for online and offline games.</p>
                <a href="games.php" class="btn btn-warning btn-lg mt-3 animate-bounce">Explore Games</a>
                <div class="scroll-indicator mt-5">
                    <span>↓ Scroll Down</span>
                </div>
            </div>
        </header>

        <!-- Featured Games Section -->
        <section id="featured-games" class="py-5">
            <div class="container">
                <h2 class="text-center mb-4" data-aos="fade-up">Featured Games</h2>
                <div class="row g-4">
                    <?php while ($game = $featured_games->fetch_assoc()): ?>
                        <div class="col-md-4" data-aos="fade-up">
                            <div class="card shadow-lg">
                                <img src="../nextlvl_images/<?= htmlspecialchars($game['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($game['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($game['title']); ?></h5>
                                    <p class="card-text justify-text"><?= htmlspecialchars($game['description']); ?></p>
                                    <p class="card-text price-text text-primary">Price: ₱<?= number_format($game['price'], 2); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="game_details.php?id=<?= htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning">Learn More</a>
                                        <a href="add_to_cart.php?game_id=<?= htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning add-to-cart">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Latest Games Section -->
        <section id="latest-games" class="py-5">
            <div class="container">
                <h2 class="text-center mb-4" data-aos="fade-up">Latest Games</h2>
                <div class="row g-4">
                    <?php while ($game = $latest_games->fetch_assoc()): ?>
                        <div class="col-md-4" data-aos="fade-up">
                            <div class="card shadow-lg">
                                <img src="../nextlvl_images/<?= htmlspecialchars($game['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($game['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($game['title']); ?></h5>
                                    <p class="card-text justify-text"><?= htmlspecialchars($game['description']); ?></p>
                                    <p class="card-text price-text text-primary">Price: ₱<?= number_format($game['price'], 2); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="game_details.php?id=<?= htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning">Learn More</a>
                                        <a href="add_to_cart.php?game_id=<?= htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning add-to-cart">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Special Offers Section -->
        <section id="special-offers" class="py-5">
            <div class="container text-center">
                <h2 class="mb-4" data-aos="fade-up">Special Offers</h2>
                <div class="row g-4">
                    <?php while ($offer = $special_offers->fetch_assoc()): ?>
                        <div class="col-md-4" data-aos="zoom-in">
                            <div class="card shadow-lg">
                                <div class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">50% OFF</div>
                                <img src="../nextlvl_images/<?= htmlspecialchars($offer['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($offer['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($offer['title']); ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($offer['description']); ?></p>
                                    <p class="card-text price-text text-primary">Price: ₱<?= number_format($offer['price'], 2); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="game_details.php?id=<?= htmlspecialchars($offer['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning">Learn More</a>
                                        <a href="add_to_cart.php?game_id=<?= htmlspecialchars($offer['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning add-to-cart">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Footer Section -->
        <footer class="footer py-5 bg-dark text-white">
            <div class="container">
                <div class="row">
                    <!-- About Us Section -->
                    <div class="col-md-4">
                        <h5>About Us</h5>
                        <p>NextLvl Games is the premier destination for online and offline games, bringing you the best deals and an incredible community.</p>
                    </div>

                    <!-- Subscribe to Newsletter Section -->
                    <div class="col-md-4">
                        <h5>Subscribe to Newsletter</h5>
                        <form method="POST" action="subscribe.php">
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Subscribe</button>
                        </form>
                    </div>

                    <!-- Contact Us Section -->
                    <div class="col-md-4">
                        <h5>Contact Us</h5>
                        <ul class="list-unstyled">
                            <li>Email: support@nextlvl.com</li>
                            <li>Phone: +1 123-456-7890</li>
                            <li>Address: 123 Gaming St, Game City</li>
                        </ul>
                        <div class="social-links mt-2">
                            <a href="https://www.facebook.com/NextLvl" class="text-white me-2" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://twitter.com/NextLvl" class="text-white me-2" title="Twitter"><i class="bi bi-twitter"></i></a>
                            <a href="https://www.instagram.com/NextLvl" class="text-white me-2" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.youtube.com/NextLvl" class="text-white me-2" title="YouTube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
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