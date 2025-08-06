<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php include 'includes/meta-tags.php'; ?>
    
    <!-- Courier Prime from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <!-- Main Content -->
    <main class="services-page">
        <!-- Hero Section -->
        <section class="hero-services">
            <div class="container">
                <div class="hero-badge">Food Photography Services</div>
                <h1>From chef-corner shoots to full-scale brand campaigns—<span class="highlight">food photography built around you.</span></h1>
                <p class="hero-subtitle">Solo on-site packages from £700</p>
                <p class="hero-description">On-site photography service. Professional lighting and editing included.</p>
                <div class="hero-actions">
                    <a href="#pricing" class="btn-primary">View Packages</a>
                    <a href="contact.php" class="btn-secondary">Book Consultation</a>
                </div>
            </div>
        </section>

        <!-- Package Comparison Cards -->
        <section id="pricing" class="pricing-section">
            <div class="container">
                <div class="pricing-header">
                    <h2 class="section-title">Service Packages</h2>
                </div>
                
                <div class="pricing-cards">
                    <div class="pricing-card">
                        <div class="card-header">
                            <h3>Quick Plate</h3>
                            <div class="price">£700</div>
                            <div class="scope">Half-day shoot</div>
                        </div>
                        <div class="card-body">
                            <p class="package-description">For menu updates and social content</p>
                            <ul class="features">
                                <li>3–4 dishes photographed</li>
                                <li>1 angle per dish</li>
                                <li>Basic colour grading</li>
                                <li>Digital delivery</li>
                            </ul>
                            <div class="ideal-for">
                                <strong>Suitable for:</strong> Independent restaurants, menu updates
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="https://buy.stripe.com/00w00k5BncPD1nl1Of2oE01" target="_blank" class="card-btn btn-primary" onclick="trackStripeClick('Quick Plate', 350)">Pay Deposit £350</a>
                            <a href="contact.php" class="card-btn btn-secondary">Get Quote</a>
                        </div>
                    </div>

                    <div class="pricing-card featured">
                        <div class="popular-badge">Most Popular</div>
                        <div class="card-header">
                            <h3>Menu Refresh</h3>
                            <div class="price">£1,300</div>
                            <div class="scope">Full-day shoot</div>
                        </div>
                        <div class="card-body">
                            <p class="package-description">Comprehensive menu photography</p>
                            <ul class="features">
                                <li>Up to 8 dishes photographed</li>
                                <li>2 angles per dish</li>
                                <li>Full retouching included</li>
                                <li>Web-optimized files</li>
                                <li>Standard usage rights</li>
                            </ul>
                            <div class="ideal-for">
                                <strong>Suitable for:</strong> Restaurant chains, menu launches
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="https://buy.stripe.com/6oUcN6e7T2aZ8PNakL2oE02" target="_blank" class="card-btn btn-primary" onclick="trackStripeClick('Menu Refresh', 650)">Pay Deposit £650</a>
                            <a href="contact.php" class="card-btn btn-secondary">Get Quote</a>
                        </div>
                    </div>

                    <div class="pricing-card">
                        <div class="card-header">
                            <h3>Campaign Day</h3>
                            <div class="price">£1,500</div>
                            <div class="scope">Full-day shoot</div>
                        </div>
                        <div class="card-body">
                            <p class="package-description">Menu photography plus additional content</p>
                            <ul class="features">
                                <li>Everything in Menu Refresh</li>
                                <li>5 additional final images</li>
                                <li>Multiple file formats</li>
                                <li>Social media sizes</li>
                                <li>Extended usage rights</li>
                            </ul>
                            <div class="ideal-for">
                                <strong>Suitable for:</strong> Marketing campaigns, brand launches
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="https://buy.stripe.com/bJecN6fbX16Vgif0Kb2oE03" target="_blank" class="card-btn btn-primary" onclick="trackStripeClick('Campaign Day', 750)">Pay Deposit £750</a>
                            <a href="contact.php" class="card-btn btn-secondary">Get Quote</a>
                        </div>
                    </div>

                    <div class="pricing-card premium">
                        <div class="premium-badge">Premium</div>
                        <div class="card-header">
                            <h3>Full Production</h3>
                            <div class="price">£2,500<span class="price-note">+</span></div>
                            <div class="scope">Full-day production</div>
                        </div>
                        <div class="card-body">
                            <p class="package-description">Full production team service</p>
                            <ul class="features">
                                <li>Director of Photography</li>
                                <li>Food stylist</li>
                                <li>Lighting technician</li>
                                <li>Prop sourcing & set build</li>
                                <li>2–3 retouching staff</li>
                            </ul>
                            <div class="ideal-for">
                                <strong>Suitable for:</strong> Editorial shoots, larger brands, cookbooks
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="contact.php" class="card-btn btn-primary">Get Custom Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <div class="container">
                <div class="how-it-works-header">
                    <h2 class="section-title">How It Works</h2>
                </div>
                
                <div class="process-steps-grid">
                    <div class="process-step-card">
                        <div class="step-number">1</div>
                        <h3>Consultation</h3>
                        <p>We discuss your requirements, timeline, and vision for the shoot</p>
                    </div>
                    
                    <div class="process-step-card">
                        <div class="step-number">2</div>
                        <h3>On-Site Setup</h3>
                        <p>Professional lighting and equipment brought to your location</p>
                    </div>
                    
                    <div class="process-step-card">
                        <div class="step-number">3</div>
                        <h3>Photography</h3>
                        <p>Chef-prepared dishes photographed with attention to detail</p>
                    </div>
                    
                    <div class="process-step-card">
                        <div class="step-number">4</div>
                        <h3>Post-Production & Delivery</h3>
                        <p>Professional editing, color correction, and high-resolution digital delivery</p>
                    </div>
                </div>
                
                <div class="availability-note">
                    <p><strong>Every project is unique. Get a tailored quote that fits your vision and budget.</strong></p>
                </div>
            </div>
        </section>

        <!-- Enhanced Add-Ons & Licensing -->
        <section class="addons-section">
            <div class="container">
                <div class="addons-header">
                    <h2 class="section-title">Add-Ons & Licensing</h2>
                </div>
                
                <div class="addons-grid">
                    <div class="addon-item highlight">
                        <div class="addon-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M21 15l-5-5L5 21" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <h3>Extra Images</h3>
                        <div class="addon-price">£25 <span>/image</span></div>
                        <p class="addon-description">Additional angles and compositions beyond package inclusions</p>
                    </div>
                    
                    <div class="addon-item">
                        <div class="addon-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M16 13H8" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M16 17H8" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 9H8" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <h3>Usage License</h3>
                        <div class="addon-price">£75 <span>/medium</span></div>
                        <p class="addon-description">Extended commercial usage rights per medium</p>
                    </div>
                    
                    <div class="addon-item">
                        <div class="addon-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2v11z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <h3>Professional Kit</h3>
                        <div class="addon-price">£100 <span>flat rate</span></div>
                        <p class="addon-description">Additional lighting equipment, camera lenses, and styling accessories</p>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'client-feedback.php'; ?>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3>How much does food photography cost in Surrey?</h3>
                        <p>Professional food photography in Surrey starts from £700 for our Quick Plate package. We offer comprehensive packages including Menu Refresh (£1,300) and Campaign Day (£1,500) to suit different needs and budgets.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Do you shoot on-site at restaurants?</h3>
                        <p>Yes, we specialize in on-site photography at your location. We bring all professional lighting and equipment to your restaurant, ensuring minimal disruption to your operations while capturing authentic dishes as your chefs prepare them.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>What's included in your photography packages?</h3>
                        <p>All packages include on-site photography, professional lighting, editing, and digital delivery. Our Menu Refresh package includes up to 8 dishes with full retouching, while Campaign Day adds social media formats and extended usage rights.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>How long does a typical food photography session take?</h3>
                        <p>Quick Plate sessions typically take 3-4 hours (half-day), while Menu Refresh and Campaign Day are full-day shoots lasting 6-8 hours. We work efficiently to minimize disruption to your business operations.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Do you provide edited images?</h3>
                        <p>Yes, all our packages include professional editing. Basic color grading is included with Quick Plate, while Menu Refresh and Campaign Day include full retouching. Images are delivered web-optimized and ready for immediate use.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Do you travel to London for food photography?</h3>
                        <p>Absolutely! We provide food photography services throughout Surrey and London with same-day availability. All equipment and lighting is brought to your location at no extra charge within our service area.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>What's the difference between standard and extended usage rights?</h3>
                        <p>Standard usage rights (included with Menu Refresh) cover restaurant menus, websites, and basic social media use. Extended usage rights (included with Campaign Day) add commercial advertising, print materials, and broader marketing campaigns. Additional usage licenses are available for £75 per medium for specific commercial needs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Call to Action -->
        <section class="portfolio-cta-section">
            <div class="container">
                <div class="portfolio-cta">
                    <h2>Bring Your Culinary Vision to Life</h2>
                    <p class="lead">From intimate restaurant moments to bold product campaigns, let's create stunning imagery that tells your story and captivates your audience.</p>
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
</body>
</html>
