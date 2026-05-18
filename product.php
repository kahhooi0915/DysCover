<?php
session_start();
include("db_connect.php");

// Get DysCover product from database
$product_id = 1;

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $product = mysqli_fetch_assoc($result);
} else {
    die("Product not found.");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DysCover | Product</title>
    <link rel="stylesheet" href="css/product.css" />
</head>

<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">DysCover</div>

        <nav class="nav-links">
            <a href="index.php">Product</a>
            <a href="science.php">Science</a>
            <a href="testimonials.php">Testimonials</a>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="member/dashboard.php">Dashboard</a>
                <a href="cart.php" class="cart-link">Cart</a>
            <?php else: ?>
                <a href="login.html">Login</a>
            <?php endif; ?>

            <a href="product.php" class="buy-btn active">Buy Now</a>
        </nav>
    </header>

    <!-- Hero Product Section -->
    <main class="product-page">

        <section class="product-hero">
            <div class="product-info">
                <span class="tag">Gamified Dyscalculia Screening</span>

                <h1>
                    <?php echo htmlspecialchars($product["product_name"]); ?>
                </h1>

                <p class="description">
                    DysCover is a 3D educational screening game designed to identify
                    potential dyscalculia in students through interactive gameplay,
                    Petri Net storytelling, and data-driven assessment.
                </p>

                <div class="product-highlights">
                    <div>✓ Petri Net-driven 3D learning experience</div>
                    <div>✓ 8 gameplay levels for mathematical screening</div>
                    <div>✓ Student answer and misconception analysis</div>
                    <div>✓ Suitable for schools, teachers, and parents</div>
                </div>

                <div class="price-box">
                    <p>Price</p>
                    <h2>RM <?php echo number_format($product["price"], 2); ?></h2>
                </div>

                <form action="add_to_cart.php" method="POST" class="purchase-form">
                    <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>" />

                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product["stock"]; ?>" />

                    <button type="submit" class="add-cart-btn">
                        Add to Cart →
                    </button>
                </form>
            </div>

            <div class="product-image-card">
                <img src="<?php echo htmlspecialchars($product["image"]); ?>" alt="DysCover Product Image" />

                <div class="image-caption">
                    <h3>3D-DIG Framework</h3>
                    <p>
                        A structured 3D game framework for identifying potential
                        dyscalculia students through engaging gameplay.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <h2>Why Choose DysCover?</h2>
            <p class="section-subtitle">
                DysCover combines 3D game technology, educational psychology,
                and adaptive storytelling to support early dyscalculia identification.
            </p>

            <div class="features-grid">
                <div class="feature-card">
                    <h3>Interactive 3D Gameplay</h3>
                    <p>
                        Students complete math-related challenges in a visually rich
                        3D environment without feeling like they are taking a test.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>Petri Net Storytelling</h3>
                    <p>
                        The game uses Petri Net-driven mechanics to structure activities
                        and record mathematical difficulties during gameplay.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>Screening Reports</h3>
                    <p>
                        The system captures student answers, misconceptions, solving
                        duration, and gameplay behavior for further analysis.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>Educational Value</h3>
                    <p>
                        Teachers and parents can use the results to support early
                        intervention and personalized learning strategies.
                    </p>
                </div>
            </div>
        </section>

        <!-- Product Details Section -->
        <section class="details-section">
            <div class="details-content">
                <h2>How DysCover Works</h2>

                <p>
                    DysCover allows children to navigate through virtual worlds,
                    interact with non-player characters, and solve mathematical
                    challenges. The screening process is embedded naturally inside
                    the game experience.
                </p>

                <p>
                    The game includes 8 levels that assess common dyscalculia-related
                    mathematical characteristics. It can collect student answers,
                    analyze misconceptions, record solving duration, and generate
                    useful screening data.
                </p>

                <a href="cart.php" class="secondary-btn">View Cart</a>
            </div>

            <div class="details-box">
                <h3>Included Features</h3>
                <ul>
                    <li>3D dyscalculia screening game</li>
                    <li>Petri Net-based game flow</li>
                    <li>Student performance tracking</li>
                    <li>Screen activity recording concept</li>
                    <li>Teacher-friendly screening insights</li>
                    <li>AI-powered NPC interaction concept</li>
                </ul>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-brand">
            <h3>DysCover</h3>
            <p>
                © 2024 DysCover. Bridging clinical rigor with engagement.
            </p>
        </div>

        <div class="footer-links">
            <h4>Resources</h4>
            <a href="science.php">Research</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="footer-links">
            <h4>Legal</h4>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>

</body>

</html>