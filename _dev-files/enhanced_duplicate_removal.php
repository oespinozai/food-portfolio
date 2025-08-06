<?php
// Enhanced duplicate detection script
// Checks file content hashes and removes duplicates more thoroughly

$images_dir = 'images/food/';
$json_file = 'images.json';

// Get all actual files in the directory
$actual_files = array_diff(scandir($images_dir), array('.', '..'));

echo "Found " . count($actual_files) . " files in directory\n";

// Calculate SHA256 for all files
$file_hashes = [];
$hash_to_files = [];

foreach ($actual_files as $file) {
    if (is_file($images_dir . $file)) {
        $hash = hash_file('sha256', $images_dir . $file);
        $file_hashes[$file] = $hash;
        
        if (!isset($hash_to_files[$hash])) {
            $hash_to_files[$hash] = [];
        }
        $hash_to_files[$hash][] = $file;
    }
}

// Find duplicates
$duplicates = [];
$keep_files = [];

foreach ($hash_to_files as $hash => $files) {
    if (count($files) > 1) {
        echo "\nDuplicate group (hash: " . substr($hash, 0, 8) . "...):\n";
        
        // Score each file to determine which to keep
        $scored_files = [];
        foreach ($files as $file) {
            $score = scoreFilename($file);
            $scored_files[] = ['file' => $file, 'score' => $score];
            echo "  - $file (score: $score)\n";
        }
        
        // Sort by score descending
        usort($scored_files, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        // Keep the highest scored file
        $keep_file = $scored_files[0]['file'];
        $keep_files[] = $keep_file;
        echo "  -> KEEPING: $keep_file\n";
        
        // Mark others as duplicates
        for ($i = 1; $i < count($scored_files); $i++) {
            $duplicates[] = $scored_files[$i]['file'];
            echo "  -> DUPLICATE: {$scored_files[$i]['file']}\n";
        }
    } else {
        // Unique file
        $keep_files[] = $files[0];
    }
}

function scoreFilename($filename) {
    $score = 0;
    
    // Prefer files without .1, .2, etc. suffixes
    if (!preg_match('/\\.\\d+(\\.\\w+)?$/', $filename)) $score += 20;
    
    // Prefer files without special URL encoding characters
    if (!strpos($filename, '%')) $score += 15;
    if (!strpos($filename, '?')) $score += 15;
    if (!strpos($filename, '=')) $score += 15;
    
    // Prefer files without ~ backup indicators
    if (!strpos($filename, '~')) $score += 10;
    
    // Prefer standard extensions
    if (preg_match('/\\.(jpg|jpeg|png)$/i', $filename)) $score += 10;
    
    // Prefer shorter names (less likely to be processed versions)
    if (strlen($filename) < 50) $score += 5;
    
    // Prefer names without "Copy" 
    if (!stripos($filename, 'copy')) $score += 5;
    
    // Prefer names without "format" indicators
    if (!stripos($filename, 'format')) $score += 10;
    
    return $score;
}

// Read current images.json
$current_images = json_decode(file_get_contents($json_file), true);

// Filter to keep only the best versions
$filtered_images = [];
foreach ($current_images as $image) {
    if (in_array($image['filename'], $keep_files)) {
        $filtered_images[] = $image;
    }
}

// Add any files that exist but aren't in JSON
foreach ($keep_files as $file) {
    $found = false;
    foreach ($filtered_images as $image) {
        if ($image['filename'] === $file) {
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // Generate alt text for new files
        $alt_text = generateAltText($file);
        $filtered_images[] = [
            'filename' => $file,
            'alt' => $alt_text
        ];
        echo "Added new file: $file\n";
    }
}

function generateAltText($filename) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Basic categorization based on filename
    if (stripos($name, 'restaurant') !== false || stripos($name, 'dining') !== false) {
        return "Restaurant photography - professional culinary presentation with elegant plating";
    } elseif (stripos($name, 'product') !== false || stripos($name, 'package') !== false) {
        return "Product photography - professional food packaging with commercial styling";
    } elseif (stripos($name, 'cocktail') !== false || stripos($name, 'drink') !== false) {
        return "Beverage photography - expertly crafted cocktail with professional presentation";
    } elseif (stripos($name, 'canape') !== false) {
        return "Catering photography - elegant canapés with sophisticated presentation";
    } else {
        return "Professional food photography - expertly crafted culinary image with artistic presentation";
    }
}

echo "\n\nSUMMARY:\n";
echo "Original files: " . count($actual_files) . "\n";
echo "Unique files to keep: " . count($keep_files) . "\n";
echo "Duplicates to remove: " . count($duplicates) . "\n";
echo "Final images in JSON: " . count($filtered_images) . "\n";

// Create backup
$backup_file = 'images_backup_' . date('Y-m-d_H-i-s') . '.json';
copy($json_file, $backup_file);
echo "Backup created: $backup_file\n";

// Write cleaned JSON
file_put_contents($json_file, json_encode($filtered_images, JSON_PRETTY_PRINT));
echo "Updated images.json\n";

echo "\nFiles that can be deleted:\n";
foreach ($duplicates as $duplicate) {
    echo "  rm \"$images_dir$duplicate\"\n";
}
?>
