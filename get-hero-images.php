<?php
header('Content-Type: application/json');

// Directory containing hero images
$heroImagesDir = __DIR__ . '/assets/hero-images/';

// Check if directory exists
if (!is_dir($heroImagesDir)) {
    // Return empty array if directory doesn't exist
    echo json_encode([]);
    exit;
}

// Load image metadata from JSON file if it exists
$metadataFile = $heroImagesDir . 'metadata.json';
$metadata = [];
if (file_exists($metadataFile)) {
    $metadataContent = file_get_contents($metadataFile);
    $metadata = json_decode($metadataContent, true) ?: [];
}

// Get all image files from the hero-images directory
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$imageFiles = [];

$files = scandir($heroImagesDir);
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($extension, $allowedExtensions)) {
        $imageFiles[] = $file;
    }
}

// Sort files alphabetically for consistent order
sort($imageFiles);

// Create image objects with src, focus points, alt text, and categories
$images = [];
foreach ($imageFiles as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
    
    // Get metadata for this image or use defaults
    $imageMetadata = $metadata[$file] ?? [];
    
    // Generate smart alt text based on filename and metadata
    $altText = $imageMetadata['alt'] ?? generateAltText($filename);
    $category = $imageMetadata['category'] ?? detectCategory($filename);
    $focus = $imageMetadata['focus'] ?? 'center 40%';
    
    $images[] = [
        'src' => 'assets/hero-images/' . $file,
        'alt' => $altText,
        'category' => $category,
        'focus' => $focus,
        'filename' => $file
    ];
}

// If no images found, return empty array
if (empty($images)) {
    echo json_encode([]);
    exit;
}

echo json_encode($images);

// Helper function to generate SEO-friendly alt text from filename
function generateAltText($filename) {
    // Clean up filename and create descriptive alt text
    $cleaned = str_replace(['-', '_'], ' ', $filename);
    $cleaned = preg_replace('/\d+/', '', $cleaned); // Remove numbers
    $cleaned = trim($cleaned);
    
    // Add Oscar Espinoza branding and food photography context
    $baseAlt = "Professional food photography by Oscar Espinoza";
    
    // Try to detect content type from filename
    if (stripos($cleaned, 'cocktail') !== false || stripos($cleaned, 'drink') !== false) {
        return "$baseAlt - Cocktail and beverage photography Surrey London";
    } elseif (stripos($cleaned, 'restaurant') !== false || stripos($cleaned, 'dining') !== false) {
        return "$baseAlt - Restaurant atmosphere photography Surrey London";
    } elseif (stripos($cleaned, 'food') !== false || stripos($cleaned, 'dish') !== false) {
        return "$baseAlt - Food dish photography Surrey London";
    } elseif (stripos($cleaned, 'canape') !== false) {
        return "$baseAlt - Canapé and appetizer photography Surrey London";
    } else {
        return "$baseAlt - Commercial food photography Surrey London";
    }
}

// Helper function to detect image category from filename
function detectCategory($filename) {
    $filename = strtolower($filename);
    
    if (stripos($filename, 'cocktail') !== false || stripos($filename, 'drink') !== false) {
        return 'Beverages';
    } elseif (stripos($filename, 'canape') !== false) {
        return 'Appetizers';
    } elseif (stripos($filename, 'restaurant') !== false || stripos($filename, 'room') !== false) {
        return 'Restaurant';
    } else {
        return 'Food';
    }
}
?>