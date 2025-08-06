<?php
// Google Analytics 4 Configuration
// Replace 'G-XXXXXXXXXX' with your actual GA4 Measurement ID

$GA4_MEASUREMENT_ID = 'G-ZC9JZRNRPF'; // Oscar Espinoza Food Photography

// Only load analytics in production (not on localhost)
$is_production = !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8000']);

if ($is_production && !empty($GA4_MEASUREMENT_ID) && $GA4_MEASUREMENT_ID !== 'G-XXXXXXXXXX') {
    ?>
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $GA4_MEASUREMENT_ID; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $GA4_MEASUREMENT_ID; ?>', {
            // Enhanced ecommerce and conversion tracking
            page_title: document.title,
            page_location: window.location.href,
            custom_map: {
                'custom_parameter_1': 'business_type',
                'custom_parameter_2': 'service_interest'
            }
        });

        // Track page views with photography-specific context
        gtag('event', 'page_view', {
            page_title: document.title,
            page_location: window.location.href,
            content_group1: 'Food Photography',
            content_group2: getPageType()
        });

        // Helper function to determine page type
        function getPageType() {
            const path = window.location.pathname;
            if (path === '/' || path === '/index.php') return 'Homepage';
            if (path.includes('portfolio')) return 'Portfolio';
            if (path.includes('services')) return 'Services';
            if (path.includes('contact')) return 'Contact';
            if (path.includes('about')) return 'About';
            return 'Other';
        }

        // Enhanced conversion tracking functions
        window.trackFormSubmission = function(formType, value) {
            gtag('event', 'generate_lead', {
                currency: 'GBP',
                value: value || 0,
                form_type: formType,
                business_category: 'Food Photography'
            });
        };

        window.trackStripeClick = function(packageName, depositAmount) {
            gtag('event', 'begin_checkout', {
                currency: 'GBP',
                value: depositAmount,
                item_name: packageName,
                item_category: 'Photography Package',
                payment_type: 'Stripe Deposit'
            });
        };

        window.trackCalendlyClick = function() {
            gtag('event', 'schedule_consultation', {
                event_category: 'Engagement',
                event_label: 'Calendly Booking',
                value: 1300 // Average package value
            });
        };

        window.trackPortfolioView = function(imageCategory) {
            gtag('event', 'view_item', {
                item_category: 'Portfolio Image',
                content_type: imageCategory || 'Food Photography'
            });
        };

        window.trackPhoneClick = function() {
            gtag('event', 'contact_phone', {
                event_category: 'Contact',
                event_label: 'Phone Number Click'
            });
        };

        window.trackEmailClick = function() {
            gtag('event', 'contact_email', {
                event_category: 'Contact', 
                event_label: 'Email Click'
            });
        };
    </script>
    <?php
} else {
    // Development mode - log analytics events to console
    ?>
    <script>
        console.log('🔍 Analytics Debug Mode (localhost)');
        
        // Mock analytics functions for development
        window.trackFormSubmission = function(formType, value) {
            console.log('📊 GA4 Event: Form Submission', {formType, value});
        };
        
        window.trackStripeClick = function(packageName, depositAmount) {
            console.log('📊 GA4 Event: Stripe Click', {packageName, depositAmount});
        };
        
        window.trackCalendlyClick = function() {
            console.log('📊 GA4 Event: Calendly Click');
        };
        
        window.trackPortfolioView = function(imageCategory) {
            console.log('📊 GA4 Event: Portfolio View', {imageCategory});
        };
        
        window.trackPhoneClick = function() {
            console.log('📊 GA4 Event: Phone Click');
        };
        
        window.trackEmailClick = function() {
            console.log('📊 GA4 Event: Email Click');
        };
    </script>
    <?php
}
?>