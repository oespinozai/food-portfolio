<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php include 'includes/meta-tags.php'; ?>
    
    <!-- Courier Prime from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="canonical" href="https://food.oscarespinoza.co.uk/portfolio.php">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <main class="portfolio-page">
        <section class="hero-portfolio">
            <div class="container">
                <div class="hero-badge">Photography Portfolio</div>
                <h1>Food Photography Portfolio | Surrey & London</h1>
                <p class="lead">Explore our work across restaurants, products, and editorial projects.</p>
            </div>
        </section>

        <section class="portfolio-filters">
            <div class="container">
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All Work</button>
                    <button class="filter-btn" data-filter="Beverages">Beverages</button>
                    <button class="filter-btn" data-filter="Food">Food</button>
                    <button class="filter-btn" data-filter="Products">Products</button>
                </div>
            </div>
        </section>

        <section class="portfolio-gallery">
            <div class="container">
                <div id="portfolio-grid" class="gallery-grid">
                    <!-- Images will be loaded dynamically by portfolio.js -->
                </div>
            </div>
        </section>

        <section class="portfolio-cta-section">
            <div class="container">
                <div class="portfolio-cta">
                    <h2>Bring Your Culinary Vision to Life</h2>
                    <p class="lead">From intimate restaurant moments to bold product campaigns, let's create stunning imagery that tells your story and captivates your audience.</p>
                    <a href="services.php" class="btn-secondary">View Packages</a>
                    <a href="contact.php" class="btn-primary">Let's Create Together</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Cookie Banner -->
    <div class="cookie-banner">
        <p>This website uses cookies to enhance your experience and ensure seamless performance.</p>
        <button class="cookie-accept">OK</button>
    </div>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
    <script src="portfolio.js"></script>
</body>
</html>
