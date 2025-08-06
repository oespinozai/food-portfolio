<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

// Sanitize the gallery parameter to allow only safe characters
$gallery = $_GET['gallery'] ?? 'corporate';
$gallery = preg_replace('/[^a-zA-Z0-9_-]/', '', $gallery);

$directory = __DIR__ . "/images/{$gallery}/";

// Check if directory exists and is readable
if (!is_dir($directory)) {
    echo json_encode(['error' => 'Directory not found', 'path' => realpath($directory)]);
    exit;
}

if (!is_readable($directory)) {
    echo json_encode(['error' => 'Directory not readable']);
    exit;
}

$images = [];
$files = scandir($directory);

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
        continue;
    }
    
    // Skip files that contain "format=" or ".1" anywhere in the file name
    if (strpos($file, 'format=') !== false || strpos($file, '.1') !== false) {
        continue;
    }
    
    $images[] = $file;
}

// Randomize the order of the images
shuffle($images);

$response = [
    'images'     => $images,
    'directory'  => realpath($directory),
    'fileCount'  => count($files),
    'imageCount' => count($images)
];

echo json_encode($response);
