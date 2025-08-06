document.addEventListener('DOMContentLoaded', function() {
    const portfolioGrid = document.getElementById('portfolio-grid');
    const filterButtons = document.querySelectorAll('.filter-btn');
    let allImages = [];

    // Create optimized picture element for portfolio
    function createOptimizedPortfolioPicture(imageData, index) {
        if (!imageData.optimized || !imageData.sources) {
            // Fallback to regular img tag
            return `<img src="${imageData.src}" 
                         alt="${imageData.title}"
                         loading="lazy"
                         onclick="openLightbox(${index})">`;
        }
        
        let sourcesHtml = '';
        imageData.sources.forEach(source => {
            sourcesHtml += `<source type="${source.type}" srcset="${source.srcset}"`;
            if (imageData.sizes) {
                sourcesHtml += ` sizes="${imageData.sizes}"`;
            }
            sourcesHtml += '>';
        });
        
        const imgSrcset = imageData.srcset ? `srcset="${imageData.srcset}"` : '';
        const imgSizes = imageData.sizes ? `sizes="${imageData.sizes}"` : '';
        
        return `<picture>
                    ${sourcesHtml}
                    <img src="${imageData.src}" 
                         alt="${imageData.title}"
                         ${imgSrcset}
                         ${imgSizes}
                         loading="lazy"
                         onclick="openLightbox(${index})">
                </picture>`;
    }

    // Load optimized images from API
    async function loadPortfolioImages() {
        try {
            showLoading();
            const response = await fetch('get-optimized-images.php');
            const images = await response.json();
            
            // Use optimized image data with fallbacks
            allImages = images.map(image => ({
                src: image.src,
                category: image.category || '',
                title: image.alt || formatImageTitle(image.filename || ''),
                optimized: image.optimized || false,
                sources: image.sources || [],
                srcset: image.srcset || '',
                sizes: image.sizes || '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 400px'
            }));
            
            displayImages(allImages);
            hideLoading();
        } catch (error) {
            console.error('Error loading portfolio images:', error);
            showErrorState();
        }
    }

    // Categorize images based on filename
    function categorizeImage(filename) {
        const name = filename.toLowerCase();
        if (name.includes('restaurant') || name.includes('dining') || name.includes('table')) {
            return 'restaurants';
        } else if (name.includes('product') || name.includes('package') || name.includes('bottle')) {
            return 'products';
        } else if (name.includes('editorial') || name.includes('magazine') || name.includes('styled')) {
            return 'editorial';
        }
        return 'restaurants'; // Default category
    }

    // Format image title from filename
    function formatImageTitle(filename) {
        return filename
            .replace(/\.[^/.]+$/, '') // Remove extension
            .replace(/[-_]/g, ' ')    // Replace hyphens and underscores with spaces
            .replace(/\b\w/g, l => l.toUpperCase()); // Capitalize first letters
    }

    // Display images in grid
    function displayImages(images) {
        portfolioGrid.innerHTML = '';
        
        images.forEach((image, index) => {
            const imageElement = document.createElement('div');
            imageElement.className = `gallery-item ${Array.isArray(image.category) ? image.category.join(' ') : image.category}`;
            imageElement.innerHTML = `
                ${createOptimizedPortfolioPicture(image, index)}
                <div class="image-overlay">
                </div>
            `;
            portfolioGrid.appendChild(imageElement);
        });
    }

    // Get category label
    function getCategoryLabel(category) {
        const labels = {
            'Food': 'Food',
            'Beverages': 'Beverages',
            'Products': 'Product',
            'restaurants': 'Restaurant',
            'products': 'Product',
            'editorial': 'Editorial'
        };
        
        // Handle arrays of categories
        if (Array.isArray(category)) {
            return category.map(cat => labels[cat] || cat).join(', ');
        }
        
        return labels[category] || 'Food Photography';
    }

    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            if (filter === 'all') {
                displayImages(allImages);
            } else {
                const filteredImages = allImages.filter(image => {
                    // Handle both single category strings and arrays of categories
                    if (Array.isArray(image.category)) {
                        return image.category.includes(filter);
                    } else {
                        return image.category === filter;
                    }
                });
                displayImages(filteredImages);
            }
        });
    });

    // Loading state
    function showLoading() {
        portfolioGrid.innerHTML = '<div class="loading">Loading portfolio...</div>';
    }

    function hideLoading() {
        const loading = portfolioGrid.querySelector('.loading');
        if (loading) loading.remove();
    }

    function showErrorState() {
        portfolioGrid.innerHTML = '<div class="error">Error loading portfolio. Please try again later.</div>';
    }

    // Lightbox functionality (reuse existing gallery.js lightbox)
    window.openLightbox = function(index) {
        // Implementation can use existing lightbox from gallery.js
        if (typeof openGalleryLightbox === 'function') {
            openGalleryLightbox(allImages[index].src);
        }
    };

    // Initialize
    loadPortfolioImages();
});
