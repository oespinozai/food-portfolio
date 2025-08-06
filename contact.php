<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php include 'includes/meta-tags.php'; ?>
    
    <!-- Courier Prime from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="canonical" href="https://food.oscarespinoza.co.uk/contact.php">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <main class="contact-page">
        <section class="hero-services">
            <div class="container">
                <div class="hero-badge">Get in Touch</div>
                <h1>Ready to Create Stunning Food Photography?</h1>
                <p class="hero-description">Let's discuss your project and bring your culinary vision to life.</p>
            </div>
        </section>

        <section class="contact-content">
            <div class="container">
                <div class="contact-grid">
                    <div class="contact-form-section">
                        <h2>Project Brief</h2>
                        <form id="contact-form" method="POST" action="contact-handler.php">
                            <!-- Honeypot for spam protection -->
                            <input type="text" name="website" style="display:none;">
                            
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="company">Company/Restaurant Name</label>
                                <input type="text" id="company" name="company">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="project-type">Project Type *</label>
                                <select id="project-type" name="project_type" required>
                                    <option value="">Select project type</option>
                                    <option value="restaurant">Restaurant Photography</option>
                                    <option value="product">Product Photography</option>
                                    <option value="editorial">Editorial Photography</option>
                                    <option value="events">Food Events</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="budget">Budget Range</label>
                                <select id="budget" name="budget">
                                    <option value="">Select budget range</option>
                                    <option value="under-500">Under £500</option>
                                    <option value="500-1000">£500 - £1,000</option>
                                    <option value="1000-2000">£1,000 - £2,000</option>
                                    <option value="2000-plus">£2,000+</option>
                                    <option value="discuss">Prefer to discuss</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="timeline">Timeline</label>
                                <select id="timeline" name="timeline">
                                    <option value="">Select timeline</option>
                                    <option value="urgent">This week (urgent)</option>
                                    <option value="soon">Within 2 weeks</option>
                                    <option value="month">Within a month</option>
                                    <option value="flexible">Flexible timing</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Shoot Location</label>
                                <input type="text" id="location" name="location" placeholder="e.g., Surrey, London, Your restaurant">
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Project Details *</label>
                                <textarea id="message" name="message" rows="6" required placeholder="Please describe your project, number of dishes/products, specific requirements, etc."></textarea>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="gdpr_consent" required>
                                    <span class="checkmark"></span>
                                    I consent to my data being processed as outlined in the <a href="privacy-policy.html" target="_blank">Privacy Policy</a> *
                                </label>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="marketing_consent">
                                    <span class="checkmark"></span>
                                    I'd like to receive updates about food photography tips and offers
                                </label>
                            </div>
                            
                            <button type="submit" class="btn-primary" onclick="trackFormSubmission('contact_form', 1300)">Send Project Brief</button>
                        </form>
                    </div>
                    
                    <div class="contact-info">
                        <h2>Contact Information</h2>
                        <div class="contact-details">
                            <div class="contact-item">
                                <h3>Response Time</h3>
                                <p>Within 24 hours (usually much faster)</p>
                            </div>
                            
                            <div class="contact-item">
                                <h3>Coverage Area</h3>
                                <p>Surrey & London coverage<br>
                                On-site shoots at your location<br>
                                Professional equipment included</p>
                            </div>
                            
                            <div class="contact-item">
                                <h3>Quick Quote</h3>
                                <p>Quick Plate from £700<br>
                                Menu Refresh from £1,300<br>
                                Campaign Day from £1,500</p>
                            </div>
                            
                            <div class="contact-item">
                                <h3>Business Hours</h3>
                                <p>Monday - Friday: 9:00 AM - 5:00 PM<br>
                                Weekend shoots available by arrangement</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'client-feedback.php'; ?>
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