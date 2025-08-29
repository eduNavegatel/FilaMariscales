// Main JavaScript for Filá Mariscales website
document.addEventListener('DOMContentLoaded', function() {
    // Cargar JavaScript moderno
    loadModernJS();
    
    // Cargar JavaScript de PWA
    loadPWAJS();
    
    // Cargar JavaScript del flip book si estamos en la página del libro
    if (window.location.pathname.includes('/libro')) {
        loadFlipBookJS();
    }
    
    // Funciones existentes
    initBootstrapComponents();
    initSmoothScrolling();
    initFormValidation();
    initFlashMessages();
    initMobileMenu();
    setActiveNavItem();
    initNavbarScroll();
});

function loadModernJS() {
    // Cargar JavaScript moderno de forma asíncrona
    const script = document.createElement('script');
    script.src = '/assets/js/responsive-modern.js';
    script.async = true;
    script.onload = function() {
        console.log('🚀 JavaScript moderno cargado correctamente');
    };
    script.onerror = function() {
        console.warn('⚠️ No se pudo cargar el JavaScript moderno');
    };
    document.head.appendChild(script);
}

function loadFlipBookJS() {
    // Cargar JavaScript del flip book de forma asíncrona
    const script = document.createElement('script');
    script.src = '/assets/js/flipbook.js';
    script.async = true;
    script.onload = function() {
        console.log('📖 JavaScript del flip book cargado correctamente');
    };
    script.onerror = function() {
        console.warn('⚠️ No se pudo cargar el JavaScript del flip book');
    };
    document.head.appendChild(script);
}

function loadPWAJS() {
    // Cargar JavaScript de PWA de forma asíncrona
    const script = document.createElement('script');
    script.src = '/assets/js/pwa.js';
    script.async = true;
    script.onload = function() {
        console.log('📱 JavaScript de PWA cargado correctamente');
    };
    script.onerror = function() {
        console.warn('⚠️ No se pudo cargar el JavaScript de PWA');
    };
    document.head.appendChild(script);
}

function initBootstrapComponents() {
    // Tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(el => new bootstrap.Tooltip(el));
    
    // Popovers
    var popovers = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popovers.map(el => new bootstrap.Popover(el));
}

function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
}

function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}

function initFlashMessages() {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

function initMobileMenu() {
    const menu = document.getElementById('navbarNav');
    if (!menu) return;
    
    const bsCollapse = new bootstrap.Collapse(menu, { toggle: false });
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                bsCollapse.hide();
            }
        });
    });
}

function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/') {
            link.classList.add('active');
        }
    });
}

function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}
