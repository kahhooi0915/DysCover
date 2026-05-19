<?php
session_start();
include("db_connect.php");

// Get DysCover product from database
$product_id = 1;

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

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
                <a href="member.php">Dashboard</a>
                <a href="cart.php" class="cart-link">Cart</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.html">Login</a>
            <?php endif; ?>

            <a href="product.php" class="buy-btn active">Buy Now</a>
        </nav>
    </header>

    <!-- Main Product Page -->
    <main class="product-page">

        <!-- Hero Product Section -->
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
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        value="1" 
                        min="1" 
                        max="<?php echo $product["stock"]; ?>" 
                    />

                    <button type="submit" class="add-cart-btn">
                        Add to Cart →
                    </button>
                </form>
            </div>

            <div class="product-image-card">
                <img 
                    src="<?php echo htmlspecialchars($product["image"]); ?>" 
                    alt="DysCover Product Image" 
                />

                <div class="image-caption">
                    <h3>Official DysCover Product</h3>
                    <p>
                        A structured 3D game framework for identifying potential
                        dyscalculia students through engaging gameplay.
                    </p>
                </div>
            </div>
        </section>

        <!-- Trailer Section -->
        <section class="trailer-section">
            <div class="section-container">
                <span class="section-tag">Game Demo</span>

                <h2>Watch DysCover in Action</h2>

                <p>
                    Explore how DysCover uses a 3D gamified environment to support
                    dyscalculia screening in an engaging and interactive way.
                </p>

                <div class="video-wrapper">
                    <video controls>
                        <source src="assets/videos/DyscalculiaGames.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </section>

        <!-- Screenshots Section -->
        <section class="screenshots-section">
            <div class="section-container">
                <span class="section-tag">Game Preview</span>

                <h2>Game Screenshots</h2>

                <p>
                    Take a closer look at the DysCover gameplay experience, including
                    the main menu, 3D environment, math challenges, NPC interaction,
                    and screening report.
                </p>

                <div class="screenshot-grid">
                    <div class="screenshot-card large-card">
                        <img src="assets/images/main-screen.png" alt="Main screen">
                        <div class="screenshot-content">
                            <h3>Main Screen</h3>
                            <p>
                                A friendly game menu that welcomes students into the
                                DysCover screening experience.
                            </p>
                        </div>
                    </div>

                    <div class="screenshot-card">
                        <img src="assets/images/city-environment.png" alt="3D city game environment">
                        <div class="screenshot-content">
                            <h3>3D City / Game Environment</h3>
                            <p>
                                Students explore a colorful virtual city while completing
                                learning and screening tasks.
                            </p>
                        </div>
                    </div>

                    <div class="screenshot-card">
                        <img src="assets/images/math-challenge.png" alt="Math challenge screen">
                        <div class="screenshot-content">
                            <h3>Math Challenge Screen</h3>
                            <p>
                                Math questions are presented as interactive game challenges
                                instead of traditional tests.
                            </p>
                        </div>
                    </div>

                    <div class="screenshot-card">
                        <img src="assets/images/npc-interaction.png" alt="NPC interaction screen">
                        <div class="screenshot-content">
                            <h3>NPC Interaction Screen</h3>
                            <p>
                                Students interact with non-player characters to receive
                                guidance and complete story-based tasks.
                            </p>
                        </div>
                    </div>

                    <div class="screenshot-card">
                        <img src="assets/images/screening-report.jpg" alt="Screening report example">
                        <div class="screenshot-content">
                            <h3>Screening / Report Example</h3>
                            <p>
                                Screening results can help teachers and parents understand
                                student performance and learning needs.
                            </p>
                        </div>
                    </div>
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

        <!-- FAQ / Support Section -->
        <section class="support-section">
            <div class="section-container">
                <span class="section-tag">Support Center</span>

                <h2>FAQ & Support</h2>

                <p class="support-intro">
                    Learn more about DysCover, how the screening experience works,
                    and what users receive after purchase.
                </p>

                <div class="support-layout">

                    <!-- FAQ Column -->
                    <div class="faq-box">
                        <h3>Frequently Asked Questions</h3>

                        <div class="faq-item">
                            <h4>Who can use DysCover?</h4>
                            <p>
                                DysCover is suitable for schools, teachers, parents, and
                                early intervention centres that want to support early
                                identification of potential dyscalculia among children.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Is DysCover a diagnosis tool?</h4>
                            <p>
                                DysCover is designed as a screening support tool. It helps
                                identify possible learning difficulties in mathematics, but
                                a formal diagnosis should still be made by qualified specialists.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>How does the screening work?</h4>
                            <p>
                                Students complete math-related challenges inside a 3D game.
                                Their answers, problem-solving behaviour, and performance
                                are recorded to provide screening insights.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Can teachers use it in class?</h4>
                            <p>
                                Yes. Teachers can use DysCover as an engaging classroom
                                activity to observe student performance and support early
                                intervention planning.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>What happens after purchase?</h4>
                            <p>
                                After completing payment, users will receive an order
                                confirmation. The purchase record will be saved in the system
                                for future reference.
                            </p>
                        </div>
                    </div>

                    <!-- Support Column -->
                    <div class="support-box">
                        <h3>Support Information</h3>

                        <div class="support-card">
                            <div class="support-icon">🎮</div>
                            <div>
                                <h4>Technical Requirements</h4>
                                <p>
                                    Recommended for desktop or laptop devices with a modern
                                    browser, stable internet connection, and audio support.
                                </p>
                            </div>
                        </div>

                        <div class="support-card">
                            <div class="support-icon">💬</div>
                            <div>
                                <h4>Contact Support</h4>
                                <p>
                                    For product enquiries, school licensing, or technical help,
                                    please contact the DysCover support team.
                                </p>
                            </div>
                        </div>

                        <div class="support-card">
                            <div class="support-icon">↩️</div>
                            <div>
                                <h4>Refund Policy</h4>
                                <p>
                                    Refund requests may be reviewed based on purchase status,
                                    system access, and project policy.
                                </p>
                            </div>
                        </div>

                        <div class="support-card">
                            <div class="support-icon">🔒</div>
                            <div>
                                <h4>Privacy Policy</h4>
                                <p>
                                    User information and order details are handled securely
                                    and used only for system and purchase management.
                                </p>
                            </div>
                        </div>

                        <div class="support-card">
                            <div class="support-icon">📄</div>
                            <div>
                                <h4>Terms of Service</h4>
                                <p>
                                    By using DysCover, users agree to follow the platform
                                    guidelines, purchase policy, and educational usage terms.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
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