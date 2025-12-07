<?php
session_start(); // Start the session

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

// Check if the game ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $game_id = $_GET['id'];

    // Prepare SQL query to fetch the game details securely
    $query = "SELECT * FROM games WHERE game_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful and data exists
    if ($result && $result->num_rows > 0) {
        // Fetch the game data
        $game = $result->fetch_assoc();
    } else {
        // If no game found
        echo "<h2>Game not found.</h2>";
        exit;
    }

    // Close the statement for game details
    $stmt->close();

    // Prepare SQL query to fetch reviews for this game
    $query_reviews = "
        SELECT reviews.comment, reviews.rating, users.username
        FROM reviews
        JOIN users ON reviews.user_id = users.user_id
        WHERE reviews.game_id = ?";
    $stmt_reviews = $conn->prepare($query_reviews);
    $stmt_reviews->bind_param("i", $game_id);
    $stmt_reviews->execute();

    // Bind the result of reviews query
    $stmt_reviews->bind_result($comment, $rating, $username);

    // Store the reviews in an array
    $reviews = [];
    while ($stmt_reviews->fetch()) {
        $reviews[] = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment
        ];
    }

    // Close the statement for reviews
    $stmt_reviews->close();
} else {
    // If no valid game ID is passed in the URL
    echo "<h2>No game selected.</h2>";
    exit;
}

// Handle "Add to Cart" form submission
if (isset($_POST['add_to_cart'])) {
    $game_id = $_POST['game_id']; // Get the game ID from the POST request
    $game_quantity = 1; // Default quantity (can be adjusted later for user input)

    // Check if the game is already in the cart
    if (isset($_SESSION['cart'][$game_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$game_id]['quantity'] += $game_quantity;
    } else {
        // Otherwise, add the game to the cart
        $_SESSION['cart'][$game_id] = [
            'game_id' => $game_id,
            'quantity' => $game_quantity,
        ];
    }

    // Set a session message to indicate success
    $_SESSION['message'] = "Game added to the cart successfully!";

    // Redirect back to the current page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $game_id);
    exit;
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
    <link rel="stylesheet" href="../css/game_details.css">
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

    <!-- Display session message if available -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']); // Clear message after displaying
    }
    ?>

    <!-- Game Details Section -->
    <section class="game-details">
        <div class="game-image">
            <img src="../nextlvl_images/<?php echo htmlspecialchars($game['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="game-content">
            <div class="game-header">
                <h1 class="game-title"><?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="price">₱<?php echo number_format($game['price'], 2); ?></p>
            </div>

            <p class="game-description"><?php echo htmlspecialchars($game['description'], ENT_QUOTES, 'UTF-8'); ?></p>

            <p class="release-date"><strong>Release Date:</strong> <?php echo date("F j, Y", strtotime($game['game_release_date'])); ?></p>

            <div class="additional-details">
                <h2>Game Details</h2>
                <ul>
                    <li><strong>Category:</strong> <?php echo htmlspecialchars($game['category'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>Developer:</strong> <?php echo htmlspecialchars($game['developer'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>Publisher:</strong> <?php echo htmlspecialchars($game['publisher'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>Platforms:</strong> <?php echo htmlspecialchars($game['platforms'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>Game Features:</strong> <?php echo htmlspecialchars($game['features'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>System Requirements:</strong> <?php echo htmlspecialchars($game['system_requirements'], ENT_QUOTES, 'UTF-8'); ?></li>
                </ul>
            </div>

            <div class="action-buttons">
                <!-- Add to Cart Form -->
                <form method="POST" action="">
                    <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>">

                    <!-- Add to Cart Button -->
                    <button type="submit" name="add_to_cart" class="btn btn-warning add-to-cart">Add to Cart</button>
                </form>

                <a href="buy_now.php?game_id=<?php echo htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn buy-now">Buy Now</a>
            </div>

            <div class="reviews-section">
                <button id="reviewToggle" class="reviews-toggle">View Reviews</button>
                <div id="reviewsContent" class="reviews-content" style="display: none;">
                    <h2>Reviews</h2>
                    <p><strong>Overall Rating:</strong> <?php echo htmlspecialchars($game['rating'], ENT_QUOTES, 'UTF-8'); ?> / 5</p>
                    <ul>
                        <?php
                        if (!empty($reviews)):
                            foreach ($reviews as $review): ?>
                                <li>
                                    <p><strong><?php echo htmlspecialchars($review['username'], ENT_QUOTES, 'UTF-8'); ?> (Rating: <?php echo htmlspecialchars($review['rating'], ENT_QUOTES, 'UTF-8'); ?>):</strong> <?php echo htmlspecialchars($review['comment'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </li>
                            <?php endforeach;
                        else: ?>
                            <p>No reviews available.</p>
                        <?php endif; ?>
                    </ul>
                </div>
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

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // AOS Initialization
        AOS.init();

        // Toggle Reviews Visibility
        document.getElementById("reviewToggle").addEventListener("click", function() {
            var reviewsContent = document.getElementById("reviewsContent");
            if (reviewsContent.style.display === "none" || reviewsContent.style.display === "") {
                reviewsContent.style.display = "block";
                this.textContent = "Hide Reviews";
            } else {
                reviewsContent.style.display = "none";
                this.textContent = "View Reviews";
            }
        });
    </script>
</body>

</html>