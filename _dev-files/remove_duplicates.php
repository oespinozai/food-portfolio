<?php
// Script to remove duplicate images based on SHA checksums
// Keeps the best quality version (prioritizes larger files, cleaner names)

$json_file = 'images.json';
$images_dir = 'images/food/';

// Read the current images.json
$images = json_decode(file_get_contents($json_file), true);

$file_checksums = [];
$duplicates = [];
$unique_images = [];

echo "Checking for duplicates...\n";

foreach ($images as $image) {
    $filepath = $images_dir . $image['filename'];
    
    if (!file_exists($filepath)) {
        echo "File not found: {$image['filename']}\n";
        continue;
    }
    
    $checksum = hash_file('sha256', $filepath);
    
    if (isset($file_checksums[$checksum])) {
        // This is a duplicate
        $duplicates[] = $image;
        echo "Duplicate found: {$image['filename']} (matches {$file_checksums[$checksum]})\n";
        
        // Decide which version to keep (prefer cleaner filenames)
        $existing = null;
        foreach ($unique_images as $key => $existing_image) {
            if ($existing_image['filename'] === $file_checksums[$checksum]) {
                $existing = $existing_image;
                $existing_key = $key;
                break;
            }
        }
        
        if ($existing) {
            $current_score = scoreFilename($image['filename']);
            $existing_score = scoreFilename($existing['filename']);
            
            if ($current_score > $existing_score) {
                // Replace existing with current
                $unique_images[$existing_key] = $image;
                $file_checksums[$checksum] = $image['filename'];
                echo "  -> Keeping {$image['filename']} over {$existing['filename']}\n";
            } else {
                echo "  -> Keeping {$existing['filename']} over {$image['filename']}\n";
            }
        }
    } else {
        // This is unique
        $file_checksums[$checksum] = $image['filename'];
        $unique_images[] = $image;
    }
}

// Function to score filenames (higher score = better to keep)
function scoreFilename($filename) {
    $score = 0;
    
    // Prefer files without special characters
    if (!strpos($filename, '?')) $score += 10;
    if (!strpos($filename, '=')) $score += 10;
    if (!strpos($filename, '~')) $score += 10;
    if (!preg_match('/\\.\\d+$/', $filename)) $score += 10;
    
    // Prefer shorter, cleaner names
    if (strlen($filename) < 50) $score += 5;
    
    // Prefer original extensions
    if (preg_match('/\\.(jpg|jpeg|png)$/i', $filename)) $score += 5;
    
    return $score;
}

echo "\nFound " . count($duplicates) . " duplicates out of " . count($images) . " total images.\n";
echo "Keeping " . count($unique_images) . " unique images.\n";

// Create backup
$backup_file = 'images_backup_' . date('Y-m-d_H-i-s') . '.json';
copy($json_file, $backup_file);
echo "Backup created: $backup_file\n";

// Write the cleaned file
file_put_contents($json_file, json_encode($unique_images, JSON_PRETTY_PRINT));
echo "Updated images.json with unique images only.\n";

// List files that can be deleted
echo "\nFiles that can be safely deleted (duplicates):\n";
foreach ($duplicates as $duplicate) {
    $should_delete = true;
    foreach ($unique_images as $unique) {
        if ($unique['filename'] === $duplicate['filename']) {
            $should_delete = false;
            break;
        }
    }
    if ($should_delete) {
        echo "  - {$duplicate['filename']}\n";
    }
}
?>
