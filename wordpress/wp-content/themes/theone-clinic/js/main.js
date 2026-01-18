// TheOne Clinic - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // Mobile Menu Toggle
    // ==========================================
    const hamburger = document.getElementById('hamburger');
    const mainNav = document.getElementById('mainNav');

    if (hamburger && mainNav) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNav.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (mainNav.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !mainNav.contains(e.target)) {
                hamburger.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close menu when clicking on a link (except dropdowns)
        const navLinks = mainNav.querySelectorAll('.nav-link:not(.dropdown-toggle)');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    hamburger.classList.remove('active');
                    mainNav.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    }

    // ==========================================
    // Mobile Dropdown Toggle
    // ==========================================
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                const dropdown = this.closest('.dropdown');
                
                // Close other dropdowns
                document.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('active');
                    }
                });
                
                dropdown.classList.toggle('active');
            } else {
                // On desktop, prevent link navigation
                e.preventDefault();
            }
        });
    });

    // ==========================================
    // Smooth Scroll for Navigation
    // ==========================================
    const smoothScroll = (target) => {
        const element = document.querySelector(target);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    };

    // ==========================================
    // Header Scroll Effect
    // ==========================================
    const header = document.querySelector('.main-header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // ==========================================
    // Menu Toggle
    // ==========================================
    const toggleMenuBtn = document.getElementById('toggleMenu');
    const servicesMenu = document.getElementById('servicesMenu');

    if (toggleMenuBtn && servicesMenu) {
        toggleMenuBtn.addEventListener('click', function() {
            const isActive = servicesMenu.classList.contains('active');
            
            if (isActive) {
                servicesMenu.classList.remove('active');
                setTimeout(() => {
                    servicesMenu.style.display = 'none';
                }, 300);
                toggleMenuBtn.innerHTML = '<i class="fas fa-bars"></i> Menu - Nasze Zabiegi';
            } else {
                servicesMenu.style.display = 'block';
                setTimeout(() => {
                    servicesMenu.classList.add('active');
                }, 10);
                toggleMenuBtn.innerHTML = '<i class="fas fa-times"></i> Zamknij Menu';
                
                // Smooth scroll to menu
                setTimeout(() => {
                    servicesMenu.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });
    }

    // ==========================================
    // Submenu Toggle for Laseroterapia
    // ==========================================
    const laseroterapiaToggle = document.getElementById('laseroterapiaToggle');
    const laseroterapiaSubmenu = document.getElementById('laseroterapiaSubmenu');

    if (laseroterapiaToggle && laseroterapiaSubmenu) {
        laseroterapiaToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isActive = laseroterapiaSubmenu.classList.contains('active');
            const icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');
            
            if (isActive) {
                laseroterapiaSubmenu.classList.remove('active');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            } else {
                laseroterapiaSubmenu.classList.add('active');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        });
    }

    // ==========================================
    // Submenu Toggle for Zabiegi Pielęgnacyjne
    // ==========================================
    const zabiegiToggle = document.getElementById('zabiegiToggle');
    const zabiegiSubmenu = document.getElementById('zabiegiSubmenu');

    if (zabiegiToggle && zabiegiSubmenu) {
        zabiegiToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isActive = zabiegiSubmenu.classList.contains('active');
            const icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');
            
            if (isActive) {
                zabiegiSubmenu.classList.remove('active');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            } else {
                zabiegiSubmenu.classList.add('active');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        });
    }

    // ==========================================
    // Animate on Scroll
    // ==========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.service-content, .pricing-table');
    animatedElements.forEach(el => {
        observer.observe(el);
    });

    // ==========================================
    // Phone Number Click to Call
    // ==========================================
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Analytics tracking can be added here
            console.log('Phone number clicked');
        });
    });

    // ==========================================
    // Booksy Button Tracking
    // ==========================================
    const booksyButtons = document.querySelectorAll('a[href*="booksy"]');
    booksyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Analytics tracking can be added here
            console.log('Booksy button clicked');
        });
    });

    // ==========================================
    // Back Button Smooth Scroll
    // ==========================================
    const backButtons = document.querySelectorAll('.back-btn');
    backButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Optional: Add smooth transition before navigation
            console.log('Back button clicked');
        });
    });

    // ==========================================
    // Gallery Filters (Efekty przed i po)
    // ==========================================
    document.querySelectorAll('.gallery-filters').forEach(filtersContainer => {
        const filterBtns = filtersContainer.querySelectorAll('.filter-btn');
        if (filterBtns.length === 0) {
            return;
        }

        // Scope items to the closest page/content container (WP may wrap blocks in extra divs).
        const scopeRoot =
            filtersContainer.closest('.service-content') ||
            filtersContainer.closest('.service-page') ||
            document;
        const galleryItems = scopeRoot.querySelectorAll('.before-after-gallery .gallery-item, .gallery-item');

        filterBtns.forEach(btn => {
            // Prevent accidental form submit if gallery is inside a form.
            btn.setAttribute('type', 'button');

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter') || 'all';

                galleryItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    const shouldShow = filterValue === 'all' || category === filterValue;

                    item.style.display = shouldShow ? 'block' : 'none';
                    if (shouldShow) {
                        item.classList.add('animate__animated', 'animate__fadeIn');
                    }
                });
            });
        });
    });

    // ==========================================
    // FAQ Categories + Accordion
    // ==========================================
    document.querySelectorAll('.faq-categories').forEach(categoriesContainer => {
        const categoryBtns = categoriesContainer.querySelectorAll('.faq-category-btn');
        if (categoryBtns.length === 0) {
            return;
        }

        const scopeRoot =
            categoriesContainer.closest('.service-content') ||
            categoriesContainer.closest('.service-page') ||
            document;
        const faqSections = scopeRoot.querySelectorAll('.faq-section');

        categoryBtns.forEach(btn => {
            btn.setAttribute('type', 'button');

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                categoryBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.getAttribute('data-category');
                if (!category) {
                    return;
                }

                faqSections.forEach(section => {
                    const sectionCategory = section.getAttribute('data-category');
                    const shouldShow = sectionCategory === category;
                    section.style.display = shouldShow ? 'block' : 'none';
                    if (shouldShow) {
                        section.classList.add('animate__animated', 'animate__fadeIn');
                    }
                });
            });
        });

        // Accordion behavior scoped to the same content.
        const faqQuestions = scopeRoot.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                if (!faqItem) {
                    return;
                }

                const isActive = faqItem.classList.contains('active');
                scopeRoot.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    });

    // ==========================================
    // Lazy Loading for Images
    // ==========================================
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }

    // ==========================================
    // Pricing Table Hover Effects
    // ==========================================
    const pricingRows = document.querySelectorAll('.pricing-row');
    pricingRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // ==========================================
    // Mobile Menu Adjustments
    // ==========================================
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        // Adjust animations for mobile
        const animatedElements = document.querySelectorAll('.animate__animated');
        animatedElements.forEach(el => {
            el.classList.add('animate__faster');
        });
    }

    // ==========================================
    // Prevent Double Click on Buttons
    // ==========================================
    const buttons = document.querySelectorAll('.btn-primary, .btn-outline');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('disabled')) {
                this.classList.add('disabled');
                setTimeout(() => {
                    this.classList.remove('disabled');
                }, 1000);
            }
        });
    });

    // ==========================================
    // Console Welcome Message
    // ==========================================
    console.log('%c👋 Witaj w TheOne Clinic!', 'color: #D4AF37; font-size: 20px; font-weight: bold;');
    console.log('%cStrona stworzona z wykorzystaniem Bootstrap, Animate.css i czystego JavaScript', 'color: #a0a0a0; font-size: 12px;');

    // ==========================================
    // Promotions Modal
    // ==========================================
    const promotionsModal = document.getElementById('promotionsModal');
    const openPromotionsBtn = document.getElementById('openPromotionsBtn');
    const closeModalBtn = document.querySelector('.close-modal');

    if (openPromotionsBtn && promotionsModal) {
        openPromotionsBtn.addEventListener('click', function() {
            promotionsModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                promotionsModal.classList.add('active');
            }, 10);
        });
    }

    if (closeModalBtn && promotionsModal) {
        closeModalBtn.addEventListener('click', function() {
            promotionsModal.classList.remove('active');
            setTimeout(() => {
                promotionsModal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        });
    }

    // Close modal when clicking outside
    if (promotionsModal) {
        window.addEventListener('click', function(e) {
            if (e.target === promotionsModal) {
                promotionsModal.classList.remove('active');
                setTimeout(() => {
                    promotionsModal.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }
        });
    }

});

// ==========================================
// Window Resize Handler
// ==========================================
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        // Adjust layout on resize if needed
        console.log('Window resized');
    }, 250);
});

// ==========================================
// Page Load Complete
// ==========================================
window.addEventListener('load', () => {
    console.log('Page fully loaded');
    
    // Remove any loading screens if present
    const loader = document.querySelector('.loader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 300);
    }
});
