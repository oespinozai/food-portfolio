<?php
/**
 * Image Optimization Script
 * Generates WebP and AVIF versions of images with multiple sizes for responsive design
 */

set_time_limit(300); // 5 minutes for processing

// Configuration
$sourceDir = __DIR__ . '/assets/hero-images/';
$outputDir = __DIR__ . '/assets/optimized/';
$sizes = [400, 800, 1200, 1600]; // Different sizes for responsive design
$quality = 85; // Quality for WebP/AVIF

// Ensure output directory exists
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all image files
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$files = scandir($sourceDir);
$imageFiles = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($extension, $allowedExtensions)) {
        $imageFiles[] = $file;
    }
}

$results = ['processed' => 0, 'errors' => [], 'created' => []];

foreach ($imageFiles as $file) {
    $sourcePath = $sourceDir . $file;
    $filename = pathinfo($file, PATHINFO_FILENAME);
    
    // Skip if source doesn't exist
    if (!file_exists($sourcePath)) {
        $results['errors'][] = "Source file not found: $file";
        continue;
    }
    
    // Load original image
    $originalImage = createImageFromFile($sourcePath);
    if (!$originalImage) {
        $results['errors'][] = "Could not load image: $file";
        continue;
    }
    
    $originalWidth = imagesx($originalImage);
    $originalHeight = imagesy($originalImage);
    
    foreach ($sizes as $targetWidth) {
        // Skip if target width is larger than original
        if ($targetWidth > $originalWidth) continue;
        
        // Calculate proportional height
        $targetHeight = intval(($originalHeight * $targetWidth) / $originalWidth);
        
        // Create resized image
        $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Preserve transparency for PNG
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        
        // Resize image
        imagecopyresampled(
            $resizedImage, $originalImage,
            0, 0, 0, 0,
            $targetWidth, $targetHeight, $originalWidth, $originalHeight
        );
        
        // Generate WebP
        $webpPath = $outputDir . $filename . "-{$targetWidth}w.webp";
        if (function_exists('imagewebp')) {
            if (imagewebp($resizedImage, $webpPath, $quality)) {
                $results['created'][] = basename($webpPath);
            }
        }
        
        // Generate AVIF (if supported)
        $avifPath = $outputDir . $filename . "-{$targetWidth}w.avif";
        if (function_exists('imageavif')) {
            if (imageavif($resizedImage, $avifPath, $quality)) {
                $results['created'][] = basename($avifPath);
            }
        }
        
        // Also create optimized JPEG as fallback
        $jpegPath = $outputDir . $filename . "-{$targetWidth}w.jpg";
        if (imagejpeg($resizedImage, $jpegPath, $quality)) {
            $results['created'][] = basename($jpegPath);
        }
        
        imagedestroy($resizedImage);
    }
    
    imagedestroy($originalImage);
    $results['processed']++;
}

// Create metadata file with optimized image information
$metadata = [];
foreach ($imageFiles as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
    
    $metadata[$file] = [
        'original' => 'assets/hero-images/' . $file,
        'optimized' => [
            'avif' => [],
            'webp' => [],
            'jpeg' => []
        ]
    ];
    
    foreach ($sizes as $size) {
        $metadata[$file]['optimized']['avif'][] = "assets/optimized/{$filename}-{$size}w.avif";
        $metadata[$file]['optimized']['webp'][] = "assets/optimized/{$filename}-{$size}w.webp";
        $metadata[$file]['optimized']['jpeg'][] = "assets/optimized/{$filename}-{$size}w.jpg";
    }
}

file_put_contents($outputDir . 'optimization-metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));

// Output results
header('Content-Type: application/json');
echo json_encode($results);

/**
 * Create image resource from file
 */
function createImageFromFile($filepath) {
    $imageType = exif_imagetype($filepath);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            return imagecreatefromjpeg($filepath);
        case IMAGETYPE_PNG:
            return imagecreatefrompng($filepath);
        case IMAGETYPE_GIF:
            return imagecreatefromgif($filepath);
        default:
            return false;
    }
}
?>