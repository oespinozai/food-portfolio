<?php
// Simple Image Optimization Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Check if GD is available
if (!extension_loaded('gd')) {
    echo json_encode(['error' => 'GD extension is not available']);
    exit;
}

// Check functions
$functions = [
    'imagewebp' => function_exists('imagewebp'),
    'imageavif' => function_exists('imageavif'),
    'imagecreatefromjpeg' => function_exists('imagecreatefromjpeg'),
    'imagecreatefrompng' => function_exists('imagecreatefrompng')
];

$sourceDir = __DIR__ . '/assets/hero-images/';
$outputDir = __DIR__ . '/assets/optimized/';

// Check directories
if (!is_dir($sourceDir)) {
    echo json_encode(['error' => 'Source directory does not exist: ' . $sourceDir]);
    exit;
}

// Create output directory
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0755, true)) {
        echo json_encode(['error' => 'Cannot create output directory: ' . $outputDir]);
        exit;
    }
}

// Get image files
$imageFiles = [];
$allowedExtensions = ['jpg', 'jpeg', 'png'];

if ($handle = opendir($sourceDir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $allowedExtensions)) {
                $imageFiles[] = $file;
            }
        }
    }
    closedir($handle);
}

if (empty($imageFiles)) {
    echo json_encode(['error' => 'No image files found in ' . $sourceDir]);
    exit;
}

// Simple processing - just create WebP versions
$results = ['processed' => 0, 'created' => [], 'errors' => [], 'functions' => $functions];

foreach ($imageFiles as $file) {
    try {
        $sourcePath = $sourceDir . $file;
        $filename = pathinfo($file, PATHINFO_FILENAME);
        
        // Load image based on type
        $image = null;
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $image = imagecreatefrompng($sourcePath);
                break;
            default:
                $results['errors'][] = "Unsupported format: $file";
                continue 2;
        }
        
        if (!$image) {
            $results['errors'][] = "Could not load: $file";
            continue;
        }
        
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Create one WebP version (800px width max)
        $newWidth = min($width, 800);
        $newHeight = intval(($height * $newWidth) / $width);
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save WebP
        if ($functions['imagewebp']) {
            $webpPath = $outputDir . $filename . '.webp';
            if (imagewebp($resized, $webpPath, 80)) {
                $results['created'][] = basename($webpPath);
            }
        }
        
        // Save JPEG
        $jpegPath = $outputDir . $filename . '.jpg';
        if (imagejpeg($resized, $jpegPath, 80)) {
            $results['created'][] = basename($jpegPath);
        }
        
        imagedestroy($image);
        imagedestroy($resized);
        $results['processed']++;
        
    } catch (Exception $e) {
        $results['errors'][] = "Error processing $file: " . $e->getMessage();
    }
}

echo json_encode($results);
?>