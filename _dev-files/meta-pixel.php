<?php
// Meta Pixel (Facebook/Instagram) Configuration
// Replace 'YOUR_PIXEL_ID' with your actual Meta Pixel ID

$META_PIXEL_ID = 'YOUR_PIXEL_ID'; // Replace with your actual Pixel ID

// Only load Meta Pixel in production (not on localhost)
$is_production = !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8000']);

if ($is_production && !empty($META_PIXEL_ID) && $META_PIXEL_ID !== 'YOUR_PIXEL_ID') {
    ?>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    
    fbq('init', '<?php echo $META_PIXEL_ID; ?>');
    fbq('track', 'PageView');

    // Enhanced photography business events
    window.trackMetaLeadGeneration = function(formType, value) {
        fbq('track', 'Lead', {
            content_name: formType,
            value: value || 1300,
            currency: 'GBP',
            content_category: 'Food Photography Services'
        });
    };

    window.trackMetaStripeClick = function(packageName, depositAmount) {
        fbq('track', 'InitiateCheckout', {
            content_name: packageName,
            value: depositAmount,
            currency: 'GBP',
            content_type: 'photography_package'
        });
    };

    window.trackMetaConsultation = function() {
        fbq('track', 'Schedule', {
            content_name: 'Photography Consultation',
            value: 1300,
            currency: 'GBP'
        });
    };

    window.trackMetaPortfolioView = function(category) {
        fbq('track', 'ViewContent', {
            content_name: 'Portfolio - ' + (category || 'Food Photography'),
            content_type: 'portfolio_image',
            content_category: 'Food Photography'
        });
    };

    window.trackMetaServiceView = function(serviceName) {
        fbq('track', 'ViewContent', {
            content_name: serviceName + ' Photography Package',
            content_type: 'service_page',
            content_category: 'Photography Services'
        });
    };

    // Track high-value visitors (spent time on services/portfolio)
    window.trackMetaHighIntent = function() {
        fbq('track', 'Search', {
            search_string: 'food photography services',
            content_category: 'High Intent Visitor'
        });
    };

    // Auto-track high intent after 30 seconds on services page
    if (window.location.pathname.includes('services')) {
        setTimeout(() => {
            trackMetaHighIntent();
        }, 30000);
    }
    </script>
    
    <!-- Meta Pixel Noscript -->
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id=<?php echo $META_PIXEL_ID; ?>&ev=PageView&noscript=1" />
    </noscript>
    <?php
} else {
    // Development mode - log Meta Pixel events to console
    ?>
    <script>
        console.log('📘 Meta Pixel Debug Mode (localhost)');
        
        // Mock Meta Pixel functions for development
        window.trackMetaLeadGeneration = function(formType, value) {
            console.log('📘 Meta Pixel Event: Lead Generation', {formType, value});
        };
        
        window.trackMetaStripeClick = function(packageName, depositAmount) {
            console.log('📘 Meta Pixel Event: Stripe Click', {packageName, depositAmount});
        };
        
        window.trackMetaConsultation = function() {
            console.log('📘 Meta Pixel Event: Consultation Booking');
        };
        
        window.trackMetaPortfolioView = function(category) {
            console.log('📘 Meta Pixel Event: Portfolio View', {category});
        };
        
        window.trackMetaServiceView = function(serviceName) {
            console.log('📘 Meta Pixel Event: Service View', {serviceName});
        };
        
        window.trackMetaHighIntent = function() {
            console.log('📘 Meta Pixel Event: High Intent Visitor');
        };
    </script>
    <?php
}
?>