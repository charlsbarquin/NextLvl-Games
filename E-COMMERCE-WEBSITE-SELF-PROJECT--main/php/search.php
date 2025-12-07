<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "nextlvl_games");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch search term
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// HTML structure
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"> <!-- AOS Library -->
    <link rel="stylesheet" href="../css/styles.css">
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
                <li class="nav-item"><a class="nav-link" href="#cart">Cart</a></li>
            </ul>
            <form class="d-flex ms-3" action="search.php" method="GET">
                <input class="form-control me-2" type="text" name="query" placeholder="Search games" aria-label="Search" required>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4">Search Results</h2>

    <?php if (empty($query)): ?>
        <p class="text-center text-danger">No search term provided.</p>
    <?php else: ?>
        <?php
        // Prepare SQL query to search for games by title
        $sql = "SELECT * FROM games WHERE title LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $query . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4"> <!-- Updated to show 4 items per row on medium screens -->
                <?php while ($game = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card shadow-lg">
                            <img src="../nextlvl_images/<?= htmlspecialchars($game['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($game['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($game['title']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($game['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-warning">View Details</a>
                                    <button class="btn btn-warning add-to-cart">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-danger">No games found for your search.</p>
        <?php endif; ?>
    <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
