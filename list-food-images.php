<?php
header('Content-Type: application/json');

// Path to the JSON file
$json_file = 'images.json';

// Check if the file exists
if (!file_exists($json_file)) {
    http_response_code(404);
    echo json_encode(['error' => 'Image data not found.']);
    exit;
}

// Read the JSON file content
$json_content = file_get_contents($json_file);

// Decode the JSON into a PHP array
$images = json_decode($json_content, true);

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(['error' => 'Error decoding image data.']);
    exit;
}

// Add the full path to each image and URL encode the filename
$processed_images = array_map(function($image) {
    // URL encode the filename to handle special characters
    $encoded_filename = rawurlencode($image['filename']);
    $image['src'] = 'images/food/' . $encoded_filename;
    return $image;
}, $images);

// Output the processed data as JSON
echo json_encode($processed_images);
?>