// Combined JavaScript files: contact.js, gallery.js, home.js, portfolio.js, and script.js
// Commercial Food Photography Website - Oscar Espinoza
// Combined and optimized for performance

// Global utility functions
function openLightbox(imageSrc) {
    const lightbox = document.getElementById('portfolio-lightbox') || document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image') || document.querySelector('.lightbox-image');
    
    if (lightbox && lightboxImage) {
        lightboxImage.src = imageSrc;
        lightbox.style.display = 'flex';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== IMAGE OPTIMIZATION HELPERS =====
    
    /**
     * Create optimized picture element with srcset and fallbacks
     */
    function createOptimizedPicture(imageData, className = '', onClick = '', loading = 'lazy') {
        if (!imageData.optimized || !imageData.sources) {
            // Fallback to regular img tag
            return `<img src="${imageData.src}" 
                         alt="${imageData.alt}"
                         class="${className}"
                         ${onClick ? `onclick="${onClick}"` : ''}
                         loading="${loading}"
                         style="object-position: ${imageData.focus || 'center'};">`;
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
                         alt="${imageData.alt}"
                         class="${className}"
                         ${imgSrcset}
                         ${imgSizes}
                         ${onClick ? `onclick="${onClick}"` : ''}
                         loading="${loading}"
                         style="object-position: ${imageData.focus || 'center'};">
                </picture>`;
    }

    // ===== HERO CAROUSEL ROTATION =====
    const carouselImage = document.getElementById('hero-carousel-image');
    if (carouselImage) {
        // Function to load optimized images from assets/hero-images folder
        async function loadHeroImages() {
            try {
                const response = await fetch('get-optimized-images.php');
                const images = await response.json();
                return images;
            } catch (error) {
                console.log('Fallback to original images');
                // Fallback to original image system
                try {
                    const fallbackResponse = await fetch('get-hero-images.php');
                    return await fallbackResponse.json();
                } catch (fallbackError) {
                    console.log('Using hardcoded fallback images');
                    return [
                        {
                            src: 'images/food/strand-uber-137.jpg',
                            focus: 'center 40%'
                        },
                    {
                        src: 'images/food/strand-uber-140.jpg',
                        focus: 'center 35%'
                    },
                    {
                        src: 'images/food/strand-uber-143.jpg',
                        focus: 'center 45%'
                    },
                    {
                        src: 'images/food/strand-uber-146.jpg',
                        focus: 'center 40%'
                    }
                ];
            }
        }

        let bestImages = [];
        let currentIndex = 0;
        
        // Initialize carousel
        async function initCarousel() {
            bestImages = await loadHeroImages();
            
            // Set first image
            if (bestImages.length > 0) {
                carouselImage.src = bestImages[0].src;
                carouselImage.alt = bestImages[0].alt;
                carouselImage.style.objectPosition = bestImages[0].focus;
                updateImageStructuredData(bestImages[0]);
            }
            
            // Preload all carousel images
            bestImages.forEach(imageObj => {
                const img = new Image();
                img.src = imageObj.src;
            });
            
            // Start rotation if we have images
            if (bestImages.length > 1) {
                setInterval(rotateCarouselImage, 4000);
            }
        }
        
        function rotateCarouselImage() {
            if (bestImages.length === 0) return;
            
            currentIndex = (currentIndex + 1) % bestImages.length;
            const currentImage = bestImages[currentIndex];
            
            carouselImage.style.opacity = '0';
            
            setTimeout(() => {
                carouselImage.src = currentImage.src;
                carouselImage.alt = currentImage.alt;
                carouselImage.style.objectPosition = currentImage.focus;
                carouselImage.style.opacity = '1';
                
                // Add structured data for SEO
                updateImageStructuredData(currentImage);
            }, 250);
        }
        
        // Add structured data for SEO
        function updateImageStructuredData(imageData) {
            // Remove existing structured data
            const existingScript = document.getElementById('hero-image-structured-data');
            if (existingScript) {
                existingScript.remove();
            }
            
            // Create new structured data
            const structuredData = {
                "@context": "https://schema.org",
                "@type": "ImageObject",
                "url": window.location.origin + '/' + imageData.src,
                "name": imageData.alt,
                "description": imageData.alt,
                "creator": {
                    "@type": "Person",
                    "name": "Oscar Espinoza",
                    "description": "Professional food photographer",
                    "address": {
                        "@type": "PostalAddress",
                        "addressRegion": "Surrey",
                        "addressCountry": "UK"
                    }
                },
                "category": imageData.category,
                "keywords": "food photography, " + imageData.category.toLowerCase() + ", Surrey, London, professional photographer"
            };
            
            const script = document.createElement('script');
            script.type = 'application/ld+json';
            script.id = 'hero-image-structured-data';
            script.textContent = JSON.stringify(structuredData);
            document.head.appendChild(script);
        }
        
        // Start the carousel
        initCarousel();
    }
    
    // ===== CONTACT FORM FUNCTIONALITY =====
    const contactForm = document.querySelector('form[name="contact-form"]');
    if (contactForm) {
        // Enhanced contact form with project type and budget handling
        const projectTypeSelect = contactForm.querySelector('select[name="project_type"]');
        const budgetSelect = contactForm.querySelector('select[name="budget"]');
        
        // Dynamic budget options based on project type
        if (projectTypeSelect && budgetSelect) {
            const budgetOptions = {
                'restaurant': [
                    'Under £500',
                    '£500 - £1,000',
                    '£1,000 - £2,500',
                    '£2,500+'
                ],
                'product': [
                    'Under £100',
                    '£100 - £300',
                    '£300 - £750',
                    '£750+'
                ],
                'editorial': [
                    'Under £750',
                    '£750 - £1,500',
                    '£1,500 - £3,000',
                    '£3,000+'
                ]
            };
            
            projectTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                const options = budgetOptions[selectedType] || budgetOptions['restaurant'];
                
                budgetSelect.innerHTML = '<option value="">Select budget range</option>';
                options.forEach(option => {
                    budgetSelect.innerHTML += `<option value="${option}">${option}</option>`;
                });
            });
        }
        
        // Form submission handling
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            const errorSpans = contactForm.querySelectorAll('.error-text');
            errorSpans.forEach(span => span.textContent = '');

            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData.entries());

            fetch('contact-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Message sent successfully! I\'ll get back to you within 24 hours.');
                    contactForm.reset();
                } else {
                    // Display error messages
                    for (const key in result.errors) {
                        const errorSpan = contactForm.querySelector(`#${key}-error`);
                        if (errorSpan) {
                            errorSpan.textContent = result.errors[key];
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }

    // ===== PORTFOLIO FUNCTIONALITY ===== 
    // DISABLED - Portfolio functionality moved to dedicated portfolio.js
    const portfolioGrid = document.getElementById('portfolio-grid');
    if (false && portfolioGrid) {
        // Function to fetch and display portfolio images
        function fetchAndDisplayPortfolioImages(category = 'all') {
            const loader = document.getElementById('loader');
            
            if (loader) loader.style.display = 'block';
            portfolioGrid.innerHTML = '';

            fetch(`list-food-images.php?category=${category}`)
                .then(response => response.json())
                .then(images => {
                    if (loader) loader.style.display = 'none';
                    
                    if (images.length === 0) {
                        portfolioGrid.innerHTML = '<p>No images found in this category.</p>';
                        return;
                    }

                    // Shuffle images for variety
                    const shuffledImages = [...images].sort(() => Math.random() - 0.5);

                    shuffledImages.forEach(image => {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'portfolio-item';
                        
                        const img = document.createElement('img');
                        img.src = image.src;
                        img.alt = image.alt; // Use proper alt text from JSON
                        img.loading = 'lazy';
                        
                        img.addEventListener('click', () => {
                            openLightbox(image.src);
                        });
                        
                        imgContainer.appendChild(img);
                        portfolioGrid.appendChild(imgContainer);
                    });
                })
                .catch(error => {
                    console.error('Error fetching portfolio images:', error);
                    if (loader) loader.style.display = 'none';
                    portfolioGrid.innerHTML = '<p>Failed to load images. Please try again.</p>';
                });
        }

        // Portfolio filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.filter;
                fetchAndDisplayPortfolioImages(category);
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Initial portfolio load
        fetchAndDisplayPortfolioImages();
    }

    // ===== HOME PAGE GALLERY =====
    const homeGallery = document.getElementById('maxFoodGallery');
    if (homeGallery) {
        fetch('list-food-images.php')
            .then(response => response.json())
            .then(images => {
                // Shuffle images for homepage variety
                const shuffledImages = [...images].sort(() => Math.random() - 0.5);
                
                // Take only first 12 images for homepage
                const displayImages = shuffledImages.slice(0, 12);
                
                homeGallery.innerHTML = '';
                displayImages.forEach(image => {
                    homeGallery.innerHTML += `
                        <div class="gallery-item">
                            ${createOptimizedPicture(image, '', `openLightbox('${image.src}')`, 'lazy')}
                        </div>
                    `;
                });
            })
            .catch(error => console.error('Home gallery error:', error));
    }

    // ===== GENERAL GALLERY FUNCTIONALITY =====
    const galleryGrid = document.querySelector('.gallery-grid');
    if (galleryGrid && !portfolioGrid) { // Only if not on portfolio page
        const fetchAndDisplayImages = (category = 'all') => {
            fetch(`list-food-images.php?category=${category}`)
                .then(response => response.json())
                .then(images => {
                    galleryGrid.innerHTML = '';
                    images.forEach(image => {
                        const img = document.createElement('img');
                        img.src = image.src;
                        img.alt = image.alt;
                        img.dataset.category = image.category;
                        img.loading = 'lazy';
                        img.addEventListener('click', () => {
                            openLightbox(image.src);
                        });
                        galleryGrid.appendChild(img);
                    });
                })
                .catch(error => console.error('Error fetching images:', error));
        };

        // Initial load
        fetchAndDisplayImages();

        // Filter functionality for general galleries
        const filterButtons = document.querySelectorAll('.filter-btn');
        if (filterButtons.length > 0) {
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.dataset.filter;
                    fetchAndDisplayImages(category);
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                });
            });
        }
    }

    // ===== LIGHTBOX FUNCTIONALITY =====
    const lightbox = document.getElementById('portfolio-lightbox') || document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image') || document.querySelector('.lightbox-image');
    const closeBtn = document.querySelector('.lightbox-close-btn') || document.querySelector('.close-btn');

    if (lightbox && closeBtn) {
        closeBtn.addEventListener('click', () => {
            lightbox.style.display = 'none';
        });

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.style.display = 'none';
            }
        });

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.style.display === 'flex') {
                lightbox.style.display = 'none';
            }
        });
    }

    // ===== MOBILE NAVIGATION =====
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const mobileNav = document.querySelector('.mobile-nav');
    const mainNavDrawer = document.querySelector('.main-nav');

    // Helper: lock/unlock body scroll when nav open
    function setBodyScrollLocked(locked) {
        if (locked) {
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
        } else {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        }
    }

    // Helper: update ARIA expanded state
    function updateAriaExpanded(isExpanded) {
        if (hamburgerMenu) {
            hamburgerMenu.setAttribute('aria-expanded', String(isExpanded));
        }
    }

    // Open/Close handlers
    function openMobileNav() {
        document.body.classList.add('nav-active');          // slides in .mobile-nav via CSS
        if (mainNavDrawer) mainNavDrawer.classList.add('active'); // open drawer on small screens
        if (hamburgerMenu) hamburgerMenu.classList.add('active');
        if (hamburgerIcon) hamburgerIcon.classList.add('active');
        updateAriaExpanded(true);
        setBodyScrollLocked(true);
    }
    function closeMobileNav() {
        document.body.classList.remove('nav-active');
        if (mainNavDrawer) mainNavDrawer.classList.remove('active');
        if (hamburgerMenu) hamburgerMenu.classList.remove('active');
        if (hamburgerIcon) hamburgerIcon.classList.remove('active');
        updateAriaExpanded(false);
        setBodyScrollLocked(false);
    }
    function toggleMobileNav() {
        const isOpen = document.body.classList.contains('nav-active') || (mainNavDrawer && mainNavDrawer.classList.contains('active'));
        if (isOpen) closeMobileNav(); else openMobileNav();
    }

    // Wire up hamburger interactions
    if (hamburgerMenu) {
        hamburgerMenu.addEventListener('click', toggleMobileNav);
        hamburgerMenu.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleMobileNav();
            }
        });
    }

    // Close mobile nav when clicking a link inside it
    if (mobileNav) {
        const links = mobileNav.querySelectorAll('a');
        links.forEach(link => link.addEventListener('click', closeMobileNav));
    }

    // Close on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMobileNav();
    });

    // Close when clicking outside drawer on small screens
    document.addEventListener('click', (e) => {
        const target = e.target;
        const clickedInsideDrawer = mainNavDrawer && mainNavDrawer.contains(target);
        const clickedHamburger = hamburgerMenu && hamburgerMenu.contains(target);
        const clickedMobileNav = mobileNav && mobileNav.contains(target);
        const isOpen = document.body.classList.contains('nav-active') || (mainNavDrawer && mainNavDrawer.classList.contains('active'));
        if (isOpen && !clickedInsideDrawer && !clickedHamburger && !clickedMobileNav) {
            closeMobileNav();
        }
    });

    // ===== SMOOTH SCROLLING =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetElement = document.querySelector(this.getAttribute('href'));
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ===== PERFORMANCE OPTIMIZATIONS =====
    
    // Lazy loading for images (if not natively supported)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        // Observe images with data-src attribute
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // ===== FORM ENHANCEMENTS =====
    
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds (fallback)
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });

    console.log('Commercial Food Photography Website initialized successfully');
});

// ===== ERROR HANDLING =====
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
});

// ===== SERVICE WORKER REGISTRATION (Optional) =====
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        // Uncomment if you add a service worker
        // navigator.serviceWorker.register('/sw.js');
    });
}
