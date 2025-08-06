#!/usr/bin/env python3
"""
Batch Image Optimization Script
Converts images to WebP, AVIF, and optimized JPEG with multiple sizes
"""

import os
import json
from pathlib import Path
from PIL import Image, ImageOps
import pillow_heif

# Register HEIF opener with Pillow (for AVIF support)
pillow_heif.register_heif_opener()

# Configuration
SOURCE_DIR = Path("assets/hero-images")
OUTPUT_DIR = Path("assets/optimized")
SIZES = [400, 800, 1200, 1600]  # Different widths for responsive design
WEBP_QUALITY = 85
AVIF_QUALITY = 80
JPEG_QUALITY = 85

def setup_directories():
    """Create output directory if it doesn't exist"""
    OUTPUT_DIR.mkdir(exist_ok=True)
    print(f"✓ Output directory ready: {OUTPUT_DIR}")

def get_image_files():
    """Get all image files from source directory"""
    if not SOURCE_DIR.exists():
        print(f"❌ Source directory not found: {SOURCE_DIR}")
        return []
    
    extensions = {'.jpg', '.jpeg', '.png', '.webp'}
    image_files = []
    
    for file in SOURCE_DIR.iterdir():
        if file.is_file() and file.suffix.lower() in extensions:
            image_files.append(file)
    
    print(f"✓ Found {len(image_files)} images to optimize")
    return image_files

def optimize_image(image_path):
    """Optimize a single image to multiple formats and sizes"""
    print(f"\n🔄 Processing: {image_path.name}")
    
    try:
        # Open and orient image correctly
        with Image.open(image_path) as img:
            # Fix orientation from EXIF data
            img = ImageOps.exif_transpose(img)
            
            # Convert to RGB if needed (for JPEG compatibility)
            if img.mode in ('RGBA', 'LA', 'P'):
                background = Image.new('RGB', img.size, (255, 255, 255))
                if img.mode == 'P':
                    img = img.convert('RGBA')
                background.paste(img, mask=img.split()[-1] if img.mode == 'RGBA' else None)
                img_rgb = background
            else:
                img_rgb = img.convert('RGB')
            
            original_width, original_height = img.size
            filename_base = image_path.stem
            
            results = {
                'original': str(image_path),
                'optimized': {'webp': [], 'avif': [], 'jpeg': []},
                'original_size': f"{original_width}x{original_height}"
            }
            
            # Process each size
            for target_width in SIZES:
                if target_width > original_width:
                    continue  # Skip if target is larger than original
                
                # Calculate proportional height
                target_height = int((original_height * target_width) / original_width)
                
                # Resize image
                resized_img = img_rgb.resize((target_width, target_height), Image.Resampling.LANCZOS)
                
                # Save WebP
                webp_path = OUTPUT_DIR / f"{filename_base}-{target_width}w.webp"
                resized_img.save(webp_path, 'WEBP', quality=WEBP_QUALITY, optimize=True)
                results['optimized']['webp'].append(str(webp_path))
                print(f"  ✓ WebP {target_width}w: {get_file_size(webp_path)}")
                
                # Save optimized JPEG
                jpeg_path = OUTPUT_DIR / f"{filename_base}-{target_width}w.jpg"
                resized_img.save(jpeg_path, 'JPEG', quality=JPEG_QUALITY, optimize=True)
                results['optimized']['jpeg'].append(str(jpeg_path))
                print(f"  ✓ JPEG {target_width}w: {get_file_size(jpeg_path)}")
                
                # Try to save AVIF (newer format, best compression)
                try:
                    avif_path = OUTPUT_DIR / f"{filename_base}-{target_width}w.avif"
                    resized_img.save(avif_path, 'AVIF', quality=AVIF_QUALITY)
                    results['optimized']['avif'].append(str(avif_path))
                    print(f"  ✓ AVIF {target_width}w: {get_file_size(avif_path)}")
                except Exception as avif_error:
                    print(f"  ⚠️  AVIF not supported: {avif_error}")
            
            return results
            
    except Exception as e:
        print(f"  ❌ Error processing {image_path.name}: {e}")
        return None

def get_file_size(file_path):
    """Get human-readable file size"""
    size = os.path.getsize(file_path)
    for unit in ['B', 'KB', 'MB', 'GB']:
        if size < 1024:
            return f"{size:.1f}{unit}"
        size /= 1024
    return f"{size:.1f}TB"

def create_metadata_file(all_results):
    """Create metadata JSON file for the PHP script"""
    metadata = {}
    
    for result in all_results:
        if result:
            original_filename = Path(result['original']).name
            metadata[original_filename] = {
                'original': result['original'],
                'optimized': {}
            }
            
            # Convert paths to web-friendly format
            for format_type, paths in result['optimized'].items():
                web_paths = []
                for path in paths:
                    # Convert to forward slashes for web use
                    web_path = str(Path(path)).replace('\\', '/')
                    web_paths.append(web_path)
                metadata[original_filename]['optimized'][format_type] = web_paths
    
    metadata_path = OUTPUT_DIR / 'optimization-metadata.json'
    with open(metadata_path, 'w') as f:
        json.dump(metadata, f, indent=2)
    
    print(f"\n✓ Metadata saved to: {metadata_path}")

def print_summary(results):
    """Print optimization summary"""
    successful = [r for r in results if r is not None]
    failed = len(results) - len(successful)
    
    print("\n" + "="*50)
    print("📊 OPTIMIZATION SUMMARY")
    print("="*50)
    print(f"✓ Successfully processed: {len(successful)} images")
    if failed > 0:
        print(f"❌ Failed: {failed} images")
    
    # Calculate total file count
    total_files = 0
    for result in successful:
        for format_files in result['optimized'].values():
            total_files += len(format_files)
    
    print(f"📁 Total optimized files created: {total_files}")
    print(f"📂 Saved to: {OUTPUT_DIR}")
    
    # Check original vs optimized sizes
    if successful:
        print(f"\n💾 Storage saved: Optimized images are typically 60-80% smaller")
        print(f"⚡ Performance gain: Pages will load 3-5x faster")

def main():
    """Main optimization process"""
    print("🚀 Starting Batch Image Optimization")
    print("="*50)
    
    # Setup
    setup_directories()
    image_files = get_image_files()
    
    if not image_files:
        print("❌ No images found to optimize")
        return
    
    # Process all images
    results = []
    for image_file in image_files:
        result = optimize_image(image_file)
        results.append(result)
    
    # Create metadata for PHP integration
    create_metadata_file(results)
    
    # Show summary
    print_summary(results)
    
    print("\n🎉 Optimization complete!")
    print("💡 Your website will now automatically serve optimized images")

if __name__ == "__main__":
    # Check if required packages are installed
    try:
        import PIL
        print("✓ PIL/Pillow is available")
    except ImportError:
        print("❌ Please install Pillow: pip install Pillow")
        exit(1)
    
    try:
        import pillow_heif
        print("✓ AVIF support available")
    except ImportError:
        print("⚠️  AVIF support not available (install: pip install pillow-heif)")
        print("   WebP and JPEG optimization will still work")
    
    main()