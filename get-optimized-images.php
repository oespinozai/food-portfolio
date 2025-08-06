<?php
header('Content-Type: application/json');

/**
 * Enhanced image loading with optimization support
 * Returns optimized images with srcset for responsive design
 */

// Directories
$heroImagesDir = __DIR__ . '/assets/hero-images/';
$optimizedDir = __DIR__ . '/assets/optimized/';
$metadataFile = $optimizedDir . 'optimization-metadata.json';

// Load original metadata
$originalMetadataFile = $heroImagesDir . 'metadata.json';
$originalMetadata = [];
if (file_exists($originalMetadataFile)) {
    $originalMetadata = json_decode(file_get_contents($originalMetadataFile), true) ?: [];
}

// Load optimization metadata
$optimizationMetadata = [];
if (file_exists($metadataFile)) {
    $optimizationMetadata = json_decode(file_get_contents($metadataFile), true) ?: [];
}

// Get all image files from the hero-images directory
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
$imageFiles = [];

if (!is_dir($heroImagesDir)) {
    echo json_encode([]);
    exit;
}

$files = scandir($heroImagesDir);
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($extension, $allowedExtensions)) {
        $imageFiles[] = $file;
    }
}

sort($imageFiles);

// Create enhanced image objects with optimization support
$images = [];
foreach ($imageFiles as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
    
    // Get original metadata
    $imageData = $originalMetadata[$file] ?? [];
    
    // Generate alt text and other properties
    $altText = $imageData['alt'] ?? generateAltText($filename);
    $category = $imageData['category'] ?? detectCategory($filename);
    $focus = $imageData['focus'] ?? 'center 40%';
    
    // Build optimized image data
    $optimizedData = $optimizationMetadata[$file] ?? null;
    
    $imageObject = [
        'src' => 'assets/hero-images/' . $file, // Fallback original
        'alt' => $altText,
        'category' => $category,
        'focus' => $focus,
        'filename' => $file,
        'optimized' => false
    ];
    
    // If optimized versions exist, add them
    if ($optimizedData && file_exists($optimizedDir)) {
        $imageObject['optimized'] = true;
        $imageObject['sources'] = [];
        
        // AVIF sources (best compression)
        if (!empty($optimizedData['optimized']['avif'])) {
            $avifSrcset = [];
            foreach ($optimizedData['optimized']['avif'] as $avifPath) {
                if (file_exists(__DIR__ . '/' . $avifPath)) {
                    $width = extractWidthFromFilename($avifPath);
                    $avifSrcset[] = $avifPath . ' ' . $width . 'w';
                }
            }
            if (!empty($avifSrcset)) {
                $imageObject['sources'][] = [
                    'type' => 'image/avif',
                    'srcset' => implode(', ', $avifSrcset)
                ];
            }
        }
        
        // WebP sources (good compression, wide support)
        if (!empty($optimizedData['optimized']['webp'])) {
            $webpSrcset = [];
            foreach ($optimizedData['optimized']['webp'] as $webpPath) {
                if (file_exists(__DIR__ . '/' . $webpPath)) {
                    $width = extractWidthFromFilename($webpPath);
                    $webpSrcset[] = $webpPath . ' ' . $width . 'w';
                }
            }
            if (!empty($webpSrcset)) {
                $imageObject['sources'][] = [
                    'type' => 'image/webp',
                    'srcset' => implode(', ', $webpSrcset)
                ];
            }
        }
        
        // JPEG fallback sources
        if (!empty($optimizedData['optimized']['jpeg'])) {
            $jpegSrcset = [];
            foreach ($optimizedData['optimized']['jpeg'] as $jpegPath) {
                if (file_exists(__DIR__ . '/' . $jpegPath)) {
                    $width = extractWidthFromFilename($jpegPath);
                    $jpegSrcset[] = $jpegPath . ' ' . $width . 'w';
                }
            }
            if (!empty($jpegSrcset)) {
                $imageObject['srcset'] = implode(', ', $jpegSrcset);
                $imageObject['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px';
            }
        }
    }
    
    $images[] = $imageObject;
}

echo json_encode($images);

/**
 * Extract width from optimized filename (e.g., "image-800w.webp" -> 800)
 */
function extractWidthFromFilename($path) {
    if (preg_match('/-(\d+)w\./', $path, $matches)) {
        return intval($matches[1]);
    }
    return 1200; // Default width
}

/**
 * Generate SEO-friendly alt text from filename
 */
function generateAltText($filename) {
    $cleaned = str_replace(['-', '_'], ' ', $filename);
    $cleaned = preg_replace('/\d+/', '', $cleaned);
    $cleaned = trim($cleaned);
    
    $baseAlt = "Professional food photography by Oscar Espinoza";
    
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

/**
 * Detect image category from filename
 */
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