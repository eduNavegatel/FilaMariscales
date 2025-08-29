/**
 * 🚀 JavaScript Moderno para Responsive Design Espectacular
 */

// ===== UTILIDADES MODERNAS =====
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);

// ===== NAVEGACIÓN RESPONSIVA =====
class ResponsiveNavbar {
    constructor() {
        this.navbar = $('.navbar');
        this.navbarToggler = $('.navbar-toggler');
        this.navbarNav = $('.navbar-nav');
        this.isMenuOpen = false;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.handleScroll();
    }
    
    setupEventListeners() {
        // Toggle mobile menu
        if (this.navbarToggler) {
            this.navbarToggler.addEventListener('click', () => this.toggleMenu());
        }
        
        // Close menu when clicking close button
        const closeButton = $('.close-menu');
        if (closeButton) {
            closeButton.addEventListener('click', () => this.closeMenu());
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.navbar.contains(e.target) && this.isMenuOpen) {
                this.closeMenu();
            }
        });
        
        // Close menu when clicking on nav links (mobile)
        $$('.navbar-nav .nav-link').forEach(link => {
            if (!link.classList.contains('close-menu')) {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 575) {
                        this.closeMenu();
                    }
                });
            }
        });
        
        // Handle scroll
        window.addEventListener('scroll', () => this.handleScroll());
        
        // Handle resize
        window.addEventListener('resize', () => this.handleResize());
        
        // Smooth scroll for navigation links
        $$('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => this.smoothScroll(e));
        });
    }
    
    toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
        
        if (this.isMenuOpen) {
            this.openMenu();
        } else {
            this.closeMenu();
        }
    }
    
    openMenu() {
        this.navbarNav.classList.add('show');
        this.navbarToggler.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Animate menu items with delay
        $$('.nav-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    closeMenu() {
        this.navbarNav.classList.remove('show');
        this.navbarToggler.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset menu items
        $$('.nav-item').forEach(item => {
            item.style.opacity = '';
            item.style.transform = '';
        });
    }
    
    handleScroll() {
        if (window.scrollY > 50) {
            this.navbar.classList.add('scrolled');
        } else {
            this.navbar.classList.remove('scrolled');
        }
    }
    
    handleResize() {
        if (window.innerWidth > 768 && this.isMenuOpen) {
            this.closeMenu();
        }
    }
    
    smoothScroll(e) {
        e.preventDefault();
        const targetId = e.currentTarget.getAttribute('href');
        const targetElement = $(targetId);
        
        if (targetElement) {
            const offsetTop = targetElement.offsetTop - 80; // Account for fixed navbar
            
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
            
            // Close mobile menu if open
            if (this.isMenuOpen) {
                this.closeMenu();
            }
        }
    }
}

// ===== ANIMACIONES AL SCROLL =====
class ScrollAnimations {
    constructor() {
        this.animatedElements = $$('[data-animate]');
        this.init();
    }
    
    init() {
        this.setupIntersectionObserver();
        this.handleScrollAnimations();
    }
    
    setupIntersectionObserver() {
        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, options);
        
        this.animatedElements.forEach(el => observer.observe(el));
    }
    
    handleScrollAnimations() {
        // Parallax effect for hero section
        const hero = $('.hero');
        if (hero) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                hero.style.transform = `translateY(${rate}px)`;
            });
        }
        
        // Counter animations
        this.animateCounters();
    }
    
    animateCounters() {
        const counters = $$('[data-counter]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-counter'));
            const duration = 2000; // 2 seconds
            const step = target / (duration / 16); // 60fps
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            // Start animation when element is visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });
    }
}

// ===== LIGHTBOX PARA IMÁGENES =====
class Lightbox {
    constructor() {
        this.lightbox = null;
        this.currentIndex = 0;
        this.images = [];
        this.init();
    }
    
    init() {
        this.createLightbox();
        this.setupEventListeners();
    }
    
    createLightbox() {
        this.lightbox = document.createElement('div');
        this.lightbox.className = 'lightbox';
        this.lightbox.innerHTML = `
            <div class="lightbox-content">
                <button class="lightbox-close">&times;</button>
                <button class="lightbox-prev">&lt;</button>
                <button class="lightbox-next">&gt;</button>
                <img class="lightbox-image" src="" alt="">
                <div class="lightbox-caption"></div>
            </div>
        `;
        
        document.body.appendChild(this.lightbox);
    }
    
    setupEventListeners() {
        // Open lightbox on image click
        $$('img[data-lightbox]').forEach((img, index) => {
            img.addEventListener('click', () => this.open(index));
        });
        
        // Close lightbox
        this.lightbox.addEventListener('click', (e) => {
            if (e.target.classList.contains('lightbox') || 
                e.target.classList.contains('lightbox-close')) {
                this.close();
            }
        });
        
        // Navigation
        $('.lightbox-prev').addEventListener('click', () => this.prev());
        $('.lightbox-next').addEventListener('click', () => this.next());
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.lightbox.classList.contains('active')) return;
            
            switch (e.key) {
                case 'Escape':
                    this.close();
                    break;
                case 'ArrowLeft':
                    this.prev();
                    break;
                case 'ArrowRight':
                    this.next();
                    break;
            }
        });
    }
    
    open(index) {
        this.currentIndex = index;
        this.images = Array.from($$('img[data-lightbox]'));
        this.updateImage();
        this.lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    close() {
        this.lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.updateImage();
    }
    
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.updateImage();
    }
    
    updateImage() {
        const img = this.images[this.currentIndex];
        const lightboxImg = $('.lightbox-image');
        const caption = $('.lightbox-caption');
        
        lightboxImg.src = img.src;
        lightboxImg.alt = img.alt;
        caption.textContent = img.alt || '';
    }
}

// ===== FORMULARIOS MODERNOS =====
class ModernForms {
    constructor() {
        this.forms = $$('form');
        this.init();
    }
    
    init() {
        this.setupFormValidation();
        this.setupFloatingLabels();
        this.setupAutoResize();
    }
    
    setupFormValidation() {
        this.forms.forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e));
            
            // Real-time validation
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => this.validateField(field));
                field.addEventListener('input', () => this.clearFieldError(field));
            });
        });
    }
    
    validateForm(e) {
        const form = e.target;
        const fields = form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            this.showFormError(form, 'Por favor, completa todos los campos requeridos.');
        }
    }
    
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let errorMessage = '';
        
        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Este campo es requerido.';
        }
        
        // Email validation
        if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Por favor, ingresa un email válido.';
        }
        
        // Phone validation
        if (type === 'tel' && value && !this.isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Por favor, ingresa un teléfono válido.';
        }
        
        if (isValid) {
            this.clearFieldError(field);
        } else {
            this.showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    isValidPhone(phone) {
        return /^[\+]?[1-9][\d]{0,15}$/.test(phone.replace(/\s/g, ''));
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    clearFieldError(field) {
        field.classList.remove('error');
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    showFormError(form, message) {
        let errorDiv = form.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            form.insertBefore(errorDiv, form.firstChild);
        }
        errorDiv.textContent = message;
    }
    
    setupFloatingLabels() {
        $$('.floating-label').forEach(container => {
            const input = container.querySelector('input, textarea');
            const label = container.querySelector('label');
            
            if (input && label) {
                // Check if input has value on load
                if (input.value) {
                    container.classList.add('has-value');
                }
                
                input.addEventListener('focus', () => {
                    container.classList.add('focused');
                });
                
                input.addEventListener('blur', () => {
                    container.classList.remove('focused');
                    if (input.value) {
                        container.classList.add('has-value');
                    } else {
                        container.classList.remove('has-value');
                    }
                });
                
                input.addEventListener('input', () => {
                    if (input.value) {
                        container.classList.add('has-value');
                    } else {
                        container.classList.remove('has-value');
                    }
                });
            }
        });
    }
    
    setupAutoResize() {
        $$('textarea[data-autoresize]').forEach(textarea => {
            textarea.addEventListener('input', () => {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            });
        });
    }
}

// ===== LAZY LOADING =====
class LazyLoading {
    constructor() {
        this.images = $$('img[data-src]');
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.setupIntersectionObserver();
        } else {
            this.loadAllImages();
        }
    }
    
    setupIntersectionObserver() {
        const options = {
            rootMargin: '50px 0px',
            threshold: 0.01
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, options);
        
        this.images.forEach(img => observer.observe(img));
    }
    
    loadImage(img) {
        const src = img.getAttribute('data-src');
        if (src) {
            img.src = src;
            img.removeAttribute('data-src');
            img.classList.add('loaded');
        }
    }
    
    loadAllImages() {
        this.images.forEach(img => this.loadImage(img));
    }
}

// ===== SCROLL TO TOP =====
class ScrollToTop {
    constructor() {
        this.button = $('.scroll-to-top');
        this.init();
    }
    
    init() {
        if (!this.button) return;
        
        this.setupEventListeners();
        this.checkScrollPosition();
    }
    
    setupEventListeners() {
        window.addEventListener('scroll', () => this.checkScrollPosition());
        
        this.button.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    checkScrollPosition() {
        if (window.pageYOffset > 300) {
            this.button.classList.add('show');
        } else {
            this.button.classList.remove('show');
        }
    }
}

// ===== PERFORMANCE OPTIMIZATIONS =====
class PerformanceOptimizer {
    constructor() {
        this.init();
    }
    
    init() {
        this.debounceScroll();
        this.preloadCriticalResources();
    }
    
    debounceScroll() {
        let ticking = false;
        
        const updateScroll = () => {
            // Update scroll-based animations
            ticking = false;
        };
        
        const requestTick = () => {
            if (!ticking) {
                requestAnimationFrame(updateScroll);
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', requestTick);
    }
    
    preloadCriticalResources() {
        // Preload critical CSS and fonts
        const criticalResources = [
            'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&display=swap',
            'https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap'
        ];
        
        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource;
            link.as = 'style';
            document.head.appendChild(link);
        });
    }
}

// ===== INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all components
    new ResponsiveNavbar();
    new ScrollAnimations();
    new Lightbox();
    new ModernForms();
    new LazyLoading();
    new ScrollToTop();
    new PerformanceOptimizer();
    
    // Add loading animation
    document.body.classList.add('loaded');
    
    console.log('🚀 Responsive Design Moderno inicializado correctamente!');
});

// ===== SERVICE WORKER PARA PWA =====
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registrado: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registro falló: ', registrationError);
            });
    });
}
