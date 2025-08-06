<?php
// Script to identify likely duplicate images based on filename patterns
// Since SHA doesn't match, we'll use filename pattern matching

$images_dir = 'images/food/';
$json_file = 'images.json';

// Get all files
$all_files = array_diff(scandir($images_dir), array('.', '..'));

// Group files by base name
$file_groups = [];

foreach ($all_files as $file) {
    if (!is_file($images_dir . $file)) continue;
    
    // Extract base name (remove extensions, numbers, format indicators)
    $base_name = $file;
    
    // Remove format parameters
    $base_name = preg_replace('/\?format=\w+/', '', $base_name);
    
    // Remove .1, .2, etc. but keep the original extension
    $base_name = preg_replace('/\.(\d+)(\.[^.]+)?$/', '$2', $base_name);
    
    // Remove ~ backup indicators
    $base_name = str_replace('~', '', $base_name);
    
    if (!isset($file_groups[$base_name])) {
        $file_groups[$base_name] = [];
    }
    
    $file_groups[$base_name][] = $file;
}

// Find groups with multiple files (likely duplicates)
$likely_duplicates = [];
$files_to_keep = [];

foreach ($file_groups as $base_name => $files) {
    if (count($files) > 1) {
        echo "\nLikely duplicate group for: $base_name\n";
        
        // Score each file
        $scored_files = [];
        foreach ($files as $file) {
            $score = scoreFile($file, $images_dir);
            $scored_files[] = ['file' => $file, 'score' => $score];
            
            $size = filesize($images_dir . $file);
            echo "  - $file (score: $score, size: " . number_format($size) . " bytes)\n";
        }
        
        // Sort by score (highest first)
        usort($scored_files, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        // Keep the best one
        $keep_file = $scored_files[0]['file'];
        $files_to_keep[] = $keep_file;
        echo "  -> KEEPING: $keep_file\n";
        
        // Mark others as duplicates
        for ($i = 1; $i < count($scored_files); $i++) {
            $likely_duplicates[] = $scored_files[$i]['file'];
            echo "  -> DUPLICATE: {$scored_files[$i]['file']}\n";
        }
    } else {
        // Single file, keep it
        $files_to_keep[] = $files[0];
    }
}

function scoreFile($filename, $images_dir) {
    $score = 0;
    
    // File size (larger is often better quality)
    $size = filesize($images_dir . $filename);
    $score += min($size / 10000, 50); // Cap at 50 points for size
    
    // Prefer files without numeric suffixes (.1, .2, etc.)
    if (!preg_match('/\.(\d+)(\.[^.]+)?$/', $filename)) $score += 30;
    
    // Prefer files without URL parameters
    if (!strpos($filename, '?')) $score += 20;
    if (!strpos($filename, '=')) $score += 20;
    
    // Prefer files without backup indicators
    if (!strpos($filename, '~')) $score += 15;
    
    // Prefer standard extensions
    if (preg_match('/\.(jpg|jpeg|png)$/i', $filename)) $score += 10;
    
    // Penalize format-specific versions
    if (stripos($filename, 'format') !== false) $score -= 10;
    
    // Prefer cleaner names
    if (strlen($filename) < 60) $score += 5;
    
    return $score;
}

// Read current images.json
$current_images = json_decode(file_get_contents($json_file), true);

// Filter images.json to only include files we want to keep
$filtered_images = [];
foreach ($current_images as $image) {
    if (in_array($image['filename'], $files_to_keep)) {
        $filtered_images[] = $image;
    }
}

echo "\n\nSUMMARY:\n";
echo "Total files found: " . count($all_files) . "\n";
echo "Files to keep: " . count($files_to_keep) . "\n";
echo "Likely duplicates: " . count($likely_duplicates) . "\n";
echo "Images in final JSON: " . count($filtered_images) . "\n";

// Create backup
$backup_file = 'images_backup_pattern_' . date('Y-m-d_H-i-s') . '.json';
copy($json_file, $backup_file);
echo "Backup created: $backup_file\n";

// Update images.json
file_put_contents($json_file, json_encode($filtered_images, JSON_PRETTY_PRINT));
echo "Updated images.json\n";

if (!empty($likely_duplicates)) {
    echo "\nFiles that can likely be removed:\n";
    foreach ($likely_duplicates as $duplicate) {
        echo "rm \"$images_dir$duplicate\"\n";
    }
}
?>
