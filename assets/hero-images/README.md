# Hero Images Folder

Drop your hero carousel images in this folder.

## Supported formats:
- JPG/JPEG
- PNG
- GIF
- WebP

## Guidelines:
- Use horizontal/landscape images only
- Recommended dimensions: 1200x600px or larger
- Keep file sizes optimized for web (under 500KB each)
- Images will automatically appear in the homepage carousel

## SEO Features:
✅ **Automatic Alt Text Generation** - Smart SEO descriptions based on filename
✅ **Category Detection** - Automatically categorizes as Food, Beverages, Restaurant, or Appetizers  
✅ **Structured Data** - JSON-LD markup for search engines
✅ **Custom Metadata** - Override defaults via `metadata.json`

## How it works:
1. **Drop images** in this folder
2. **Smart SEO** automatically generates alt text and categories
3. **Custom metadata** can be added in `metadata.json`
4. **Structured data** updates dynamically for each image

## SEO Naming Examples:
- `cocktail-martini-2024.jpg` → "Professional food photography by Oscar Espinoza - Cocktail and beverage photography Surrey London"
- `restaurant-dining-room.jpg` → "Professional food photography by Oscar Espinoza - Restaurant atmosphere photography Surrey London"
- `food-pasta-dish.jpg` → "Professional food photography by Oscar Espinoza - Food dish photography Surrey London"

## Custom Metadata:
Edit `metadata.json` to override defaults:
```json
{
  "your-image.jpg": {
    "alt": "Custom SEO description",
    "category": "Food",
    "focus": "center 30%"
  }
}
```