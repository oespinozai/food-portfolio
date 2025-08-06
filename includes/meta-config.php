<?php
// Dynamic Meta Configuration
// This file contains all meta tag data that can be dynamically updated

// Business Information (update once, affects all pages)
$business = [
    'name' => 'Oscar Espinoza',
    'business_name' => 'Oscar Espinoza Photography',
    'tagline' => 'Professional Food Photographer',
    'location' => 'Surrey & London',
    'phone' => '+44 7XXX XXXXXX', // Update with real number
    'email' => 'hello@oscarespinoza.co.uk',
    'website' => 'https://food.oscarespinoza.co.uk',
    'service_area' => ['Surrey', 'London', 'Home Counties'],
    'specialties' => ['Restaurant Photography', 'Product Photography', 'Editorial Photography', 'Menu Photography'],
    'min_price' => 700,
    'main_keywords' => 'food photographer, restaurant photography, product photography, commercial food photography'
];

// Service Packages (update these and meta descriptions auto-update)
$service_packages = [
    'quick_plate' => [
        'name' => 'Quick Plate',
        'price' => 700,
        'type' => 'Half-day shoot',
        'description' => 'Perfect for menu updates and social content'
    ],
    'menu_refresh' => [
        'name' => 'Menu Refresh', 
        'price' => 1300,
        'type' => 'Full-day shoot',
        'description' => 'Comprehensive menu photography'
    ],
    'campaign_day' => [
        'name' => 'Campaign Day',
        'price' => 1500, 
        'type' => 'Full-day shoot',
        'description' => 'Menu photography plus additional content'
    ]
];

// Dynamic Meta Data Generator
function generatePageMeta($page, $business, $service_packages) {
    $meta = [];
    
    switch($page) {
        case 'home':
            $meta['title'] = "Professional Food Photographer {$business['location']} | {$business['name']} | Restaurant & Product Photography";
            $meta['description'] = "Award-winning food photographer in {$business['location']}. Specializing in restaurant menus, product photography, and editorial shoots. From £{$business['min_price']}. Book consultation today.";
            $meta['keywords'] = "{$business['main_keywords']}, {$business['location']}, menu photography, commercial photography";
            break;
            
        case 'services':
            $packages = array_values($service_packages);
            $min_price = min(array_column($packages, 'price'));
            $meta['title'] = "Food Photography Services {$business['location']} | Packages from £{$min_price} | {$business['name']}";
            $meta['description'] = "Professional food photography services in {$business['location']}. {$packages[0]['name']} from £{$packages[0]['price']}, {$packages[1]['name']} from £{$packages[1]['price']}, {$packages[2]['name']} from £{$packages[2]['price']}. Same-day availability.";
            $meta['keywords'] = "food photography services, {$business['location']}, restaurant photography, pricing, packages";
            break;
            
        case 'portfolio':
            $meta['title'] = "Food Photography Portfolio | {$business['location']} | {$business['name']} Commercial Work";
            $meta['description'] = "Browse {$business['name']}'s professional food photography portfolio. Restaurant, product, and editorial photography serving {$business['location']} clients. Award-winning commercial work.";
            $meta['keywords'] = "food photography portfolio, {$business['location']}, restaurant photos, product photography examples";
            break;
            
        case 'about':
            $meta['title'] = "About {$business['name']} - Commercial Food Photographer | {$business['location']}";
            $meta['description'] = "Meet {$business['name']}, award-winning commercial food photographer based in {$business['location']}. 8+ years experience specializing in restaurant, product, and editorial food photography.";
            $meta['keywords'] = "about {$business['name']}, food photographer biography, {$business['location']}, commercial photography experience";
            break;
            
        case 'contact':
            $meta['title'] = "Contact {$business['name']} - Food Photography {$business['location']} | Quick Quote";
            $meta['description'] = "Contact {$business['name']} for professional food photography in {$business['location']}. Quick response, transparent pricing from £{$business['min_price']}, same-day availability. Get your quote today.";
            $meta['keywords'] = "contact food photographer, {$business['location']}, photography quote, hire photographer";
            break;
            
        case 'privacy-policy':
            $meta['title'] = "Privacy Policy - {$business['business_name']} | Data Protection & Photography Services";
            $meta['description'] = "Privacy policy for {$business['business_name']} food photography services. How we collect, use, and protect your personal information when using our website and services.";
            $meta['keywords'] = "privacy policy, data protection, {$business['business_name']}, photography services";
            break;
    }
    
    // Add common Open Graph and Twitter data
    $meta['og_title'] = $meta['title'];
    $meta['og_description'] = $meta['description'];
    $meta['og_image'] = $business['website'] . '/assets/social/og-' . $page . '.jpg';
    $meta['og_url'] = $business['website'] . '/' . ($page === 'home' ? '' : $page . '.php');
    
    return $meta;
}

// Local Business Schema Generator
function generateLocalBusinessSchema($business, $service_packages) {
    $services = [];
    foreach($service_packages as $package) {
        $services[] = [
            "@type" => "Service",
            "name" => $package['name'],
            "description" => $package['description'],
            "offers" => [
                "@type" => "Offer",
                "price" => $package['price'],
                "priceCurrency" => "GBP"
            ]
        ];
    }
    
    return [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $business['business_name'],
        "description" => "Professional commercial food photographer specializing in restaurant, product, and editorial photography",
        "image" => $business['website'] . "/assets/oscar-espinoza-photographer.jpg",
        "telephone" => $business['phone'],
        "email" => $business['email'],
        "url" => $business['website'],
        "address" => [
            "@type" => "PostalAddress",
            "addressRegion" => "Surrey",
            "addressCountry" => "GB"
        ],
        "geo" => [
            "@type" => "GeoCoordinates",
            "latitude" => "51.2362",
            "longitude" => "-0.5704"
        ],
        "serviceArea" => [
            "@type" => "Place",
            "name" => implode(", ", $business['service_area'])
        ],
        "areaServed" => $business['service_area'],
        "priceRange" => "££",
        "openingHours" => "Mo-Su 09:00-18:00",
        "hasOfferCatalog" => [
            "@type" => "OfferCatalog",
            "name" => "Food Photography Services",
            "itemListElement" => $services
        ],
        "sameAs" => [
            "https://www.behance.net/oxei",
            "https://instagram.com/creativeclapham"
        ]
    ];
}
?>