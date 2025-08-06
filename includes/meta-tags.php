<?php
// Dynamic Meta Tags Include
// Include this file in the <head> section of each page

// Include the meta configuration
require_once __DIR__ . '/meta-config.php';

// Get current page name
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page === 'index') $current_page = 'home';

// Generate meta data for current page
$meta = generatePageMeta($current_page, $business, $service_packages);
$schema = generateLocalBusinessSchema($business, $service_packages);
?>

<!-- Dynamic Meta Tags -->
<title><?php echo htmlspecialchars($meta['title']); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($meta['description']); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($meta['keywords']); ?>">
<meta name="author" content="<?php echo htmlspecialchars($business['name']); ?>">
<meta name="robots" content="index, follow">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="<?php echo htmlspecialchars($meta['og_title']); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($meta['og_description']); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($meta['og_image']); ?>">
<meta property="og:url" content="<?php echo htmlspecialchars($meta['og_url']); ?>">
<meta property="og:type" content="business.business">
<meta property="og:site_name" content="<?php echo htmlspecialchars($business['business_name']); ?>">
<meta property="og:locale" content="en_GB">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo htmlspecialchars($meta['title']); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($meta['description']); ?>">
<meta name="twitter:image" content="<?php echo htmlspecialchars($meta['og_image']); ?>">
<meta name="twitter:site" content="@creativeclapham">

<!-- Business Contact Info -->
<meta name="telephone" content="<?php echo htmlspecialchars($business['phone']); ?>">
<meta name="email" content="<?php echo htmlspecialchars($business['email']); ?>">
<meta name="geo.region" content="GB-SRY">
<meta name="geo.placename" content="Surrey">
<meta name="geo.position" content="51.2362;-0.5704">
<meta name="ICBM" content="51.2362, -0.5704">

<!-- Analytics Tracking -->
<?php include __DIR__ . '/analytics.php'; ?>

<!-- Local Business Schema -->
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<!-- Breadcrumb Schema -->
<?php if ($current_page !== 'home'): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "<?php echo $business['website']; ?>"
    },
    {
      "@type": "ListItem", 
      "position": 2,
      "name": "<?php echo ucfirst(str_replace('-', ' ', $current_page)); ?>",
      "item": "<?php echo $business['website']; ?>/<?php echo $current_page; ?>.php"
    }
  ]
}
</script>
<?php endif; ?>

<!-- Page-Specific Schema -->
<?php if ($current_page === 'services'): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Food Photography Services",
  "provider": {
    "@type": "LocalBusiness",
    "name": "<?php echo $business['business_name']; ?>",
    "telephone": "<?php echo $business['phone']; ?>"
  },
  "serviceType": "Photography",
  "areaServed": <?php echo json_encode($business['service_area']); ?>,
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Food Photography Packages",
    "itemListElement": [
      <?php 
      $package_schemas = [];
      foreach($service_packages as $package) {
        $package_schemas[] = json_encode([
          "@type" => "Offer",
          "name" => $package['name'],
          "description" => $package['description'],
          "price" => $package['price'],
          "priceCurrency" => "GBP"
        ]);
      }
      echo implode(",\n      ", $package_schemas);
      ?>
    ]
  }
}
</script>
<?php endif; ?>

<?php if ($current_page === 'home'): ?>
<!-- Homepage FAQ Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How much does food photography cost in Surrey?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Professional food photography in Surrey starts from £<?php echo $business['min_price']; ?> for our Quick Plate package. We offer comprehensive packages including Menu Refresh (£<?php echo $service_packages['menu_refresh']['price']; ?>) and Campaign Day (£<?php echo $service_packages['campaign_day']['price']; ?>)."
      }
    },
    {
      "@type": "Question", 
      "name": "Do you travel to London for food photography?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we provide food photography services throughout Surrey and London with same-day availability. All equipment and lighting is brought to your location."
      }
    },
    {
      "@type": "Question",
      "name": "What's included in your food photography packages?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "All packages include on-site photography, professional lighting, editing, and digital delivery. Our Menu Refresh package includes up to 8 dishes with full retouching, while Campaign Day adds social media formats and extended usage rights."
      }
    }
  ]
}
</script>
<?php endif; ?>