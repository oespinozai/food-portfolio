<?php
header('Content-Type: application/json');

$imageDirectory = 'images/corporate/';
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$images = [];

// Check if the directory exists and is readable
if (is_dir($imageDirectory) && is_readable($imageDirectory)) {
    $files = scandir($imageDirectory);
    if ($files !== false) {
        foreach ($files as $file) {
            // Skip the current and parent directory entries
            if ($file === '.' || $file === '..') {
                continue;
            }
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $allowedExtensions, true)) {
                $images[] = $file;
            }
        }
    } else {
        echo json_encode(['error' => 'Failed to read directory']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Directory does not exist or is not readable']);
    exit;
}

echo json_encode($images);
