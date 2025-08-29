/**
 * 📖 Flip Book Moderno y Espectacular
 * Funcionalidad completa para un flip book interactivo
 */

class FlipBook {
    constructor(container, pages) {
        this.container = container;
        this.pages = pages;
        this.currentPage = 0;
        this.isFlipping = false;
        this.flipDirection = 'forward';
        
        this.init();
    }
    
    init() {
        this.createFlipBook();
        this.setupControls();
        this.setupEventListeners();
        this.showPage(0);
        
        // Agregar efecto de entrada
        setTimeout(() => {
            this.container.classList.add('loaded');
        }, 500);
    }
    
    createFlipBook() {
        this.container.innerHTML = `
            <div class="flipbook">
                ${this.pages.map((page, index) => this.createPage(page, index)).join('')}
            </div>
            <div class="flipbook-controls">
                <button class="flipbook-btn" id="prevPage" disabled>
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <div class="page-indicator">
                    <span id="currentPageNum">1</span> / <span id="totalPages">${this.pages.length}</span>
                </div>
                <button class="flipbook-btn" id="nextPage">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        `;
        
        this.pageElements = this.container.querySelectorAll('.flipbook-page');
        this.prevBtn = this.container.querySelector('#prevPage');
        this.nextBtn = this.container.querySelector('#nextPage');
        this.currentPageNum = this.container.querySelector('#currentPageNum');
        this.totalPages = this.container.querySelector('#totalPages');
    }
    
    createPage(pageData, index) {
        const isEven = index % 2 === 0;
        const pageNumber = index + 1;
        
        return `
            <div class="flipbook-page" data-page="${pageNumber}">
                <div class="page-front">
                    <div class="page-header">
                        <div class="page-number">Capítulo ${pageNumber}</div>
                        <div class="page-date">${pageData.fecha}</div>
                    </div>
                    
                    <div class="page-content">
                        <h2 class="page-title">${pageData.titulo}</h2>
                        <h3 class="page-subtitle">${pageData.subtitulo}</h3>
                        
                        <div class="page-text">
                            ${pageData.contenido.map(parrafo => `<p>${parrafo}</p>`).join('')}
                        </div>
                        
                        ${pageData.imagen ? `
                            <div class="page-image">
                                <img src="${pageData.imagen}" alt="${pageData.titulo}" loading="lazy">
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="page-footer">
                        <div class="page-quote">
                            "La tradición no es adorar las cenizas, sino mantener viva la llama."
                            <br><small>- Filá Mariscales</small>
                        </div>
                    </div>
                </div>
                
                <div class="page-back">
                    <div class="page-header">
                        <div class="page-number">Capítulo ${pageNumber + 1}</div>
                        <div class="page-date">${this.pages[index + 1]?.fecha || 'Continuará...'}</div>
                    </div>
                    
                    <div class="page-content">
                        ${this.pages[index + 1] ? `
                            <h2 class="page-title">${this.pages[index + 1].titulo}</h2>
                            <h3 class="page-subtitle">${this.pages[index + 1].subtitulo}</h3>
                            
                            <div class="page-text">
                                ${this.pages[index + 1].contenido.slice(0, 2).map(parrafo => `<p>${parrafo}</p>`).join('')}
                                <p><em>Continúa en la siguiente página...</em></p>
                            </div>
                        ` : `
                            <div class="page-text">
                                <h2 class="page-title">Fin del Libro</h2>
                                <p>Has llegado al final de esta crónica histórica de la Filá Mariscales de Caballeros Templarios.</p>
                                <p>Que el legado de honor, tradición y excelencia perdure por siempre.</p>
                            </div>
                        `}
                    </div>
                    
                    <div class="page-footer">
                        <div class="page-quote">
                            "El honor de los templarios vive en cada uno de nosotros."
                            <br><small>- Filá Mariscales</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    setupControls() {
        this.prevBtn.addEventListener('click', () => this.previousPage());
        this.nextBtn.addEventListener('click', () => this.nextPage());
    }
    
    setupEventListeners() {
        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (this.isFlipping) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    this.previousPage();
                    break;
                case 'ArrowRight':
                    this.nextPage();
                    break;
                case 'Home':
                    this.goToPage(0);
                    break;
                case 'End':
                    this.goToPage(this.pages.length - 1);
                    break;
            }
        });
        
        // Navegación con clic en páginas
        this.pageElements.forEach((page, index) => {
            page.addEventListener('click', (e) => {
                if (this.isFlipping) return;
                
                const rect = page.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const pageWidth = rect.width;
                
                // Si se hace clic en la mitad derecha, siguiente página
                if (clickX > pageWidth / 2) {
                    this.nextPage();
                } else {
                    this.previousPage();
                }
            });
        });
        
        // Navegación con gestos táctiles
        let startX = 0;
        let startY = 0;
        
        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        this.container.addEventListener('touchend', (e) => {
            if (this.isFlipping) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const deltaX = endX - startX;
            const deltaY = endY - startY;
            
            // Solo procesar si el gesto es horizontal y suficientemente largo
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0) {
                    this.previousPage();
                } else {
                    this.nextPage();
                }
            }
        });
    }
    
    async flipPage(direction) {
        if (this.isFlipping) return;
        
        this.isFlipping = true;
        this.flipDirection = direction;
        
        const currentPageElement = this.pageElements[this.currentPage];
        
        if (direction === 'forward') {
            currentPageElement.classList.add('flipping-forward');
            
            // Esperar a que termine la animación
            await this.waitForAnimation(currentPageElement, 'flipping-forward');
            
            currentPageElement.classList.remove('flipping-forward');
            currentPageElement.classList.add('flipped');
            
        } else {
            currentPageElement.classList.remove('flipped');
            currentPageElement.classList.add('flipping-backward');
            
            // Esperar a que termine la animación
            await this.waitForAnimation(currentPageElement, 'flipping-backward');
            
            currentPageElement.classList.remove('flipping-backward');
        }
        
        this.isFlipping = false;
        this.updateControls();
    }
    
    waitForAnimation(element, className) {
        return new Promise((resolve) => {
            const duration = getComputedStyle(document.documentElement)
                .getPropertyValue('--flip-duration') || '1.2s';
            
            setTimeout(() => {
                resolve();
            }, parseFloat(duration) * 1000);
        });
    }
    
    async nextPage() {
        if (this.currentPage < this.pages.length - 1 && !this.isFlipping) {
            await this.flipPage('forward');
            this.currentPage++;
            this.showPage(this.currentPage);
        }
    }
    
    async previousPage() {
        if (this.currentPage > 0 && !this.isFlipping) {
            this.currentPage--;
            this.showPage(this.currentPage);
            await this.flipPage('backward');
        }
    }
    
    goToPage(pageIndex) {
        if (pageIndex < 0 || pageIndex >= this.pages.length || this.isFlipping) return;
        
        // Resetear todas las páginas
        this.pageElements.forEach((page, index) => {
            if (index < pageIndex) {
                page.classList.add('flipped');
            } else {
                page.classList.remove('flipped');
            }
        });
        
        this.currentPage = pageIndex;
        this.showPage(this.currentPage);
        this.updateControls();
    }
    
    showPage(pageIndex) {
        // Ocultar todas las páginas
        this.pageElements.forEach((page, index) => {
            if (index < pageIndex) {
                page.style.zIndex = index;
                page.style.transform = 'translate(-50%, -50%) rotateY(-180deg)';
            } else {
                page.style.zIndex = this.pages.length - index;
                page.style.transform = 'translate(-50%, -50%) rotateY(0deg)';
            }
        });
        
        this.updateControls();
    }
    
    updateControls() {
        this.currentPageNum.textContent = this.currentPage + 1;
        
        this.prevBtn.disabled = this.currentPage === 0;
        this.nextBtn.disabled = this.currentPage === this.pages.length - 1;
        
        // Agregar efectos visuales
        if (this.prevBtn.disabled) {
            this.prevBtn.style.opacity = '0.5';
        } else {
            this.prevBtn.style.opacity = '1';
        }
        
        if (this.nextBtn.disabled) {
            this.nextBtn.style.opacity = '0.5';
        } else {
            this.nextBtn.style.opacity = '1';
        }
    }
    
    // Métodos públicos para control externo
    getCurrentPage() {
        return this.currentPage;
    }
    
    getTotalPages() {
        return this.pages.length;
    }
    
    // Efectos especiales
    addPageEffect() {
        const currentPageElement = this.pageElements[this.currentPage];
        currentPageElement.style.transform += ' scale(1.02)';
        
        setTimeout(() => {
            currentPageElement.style.transform = currentPageElement.style.transform.replace(' scale(1.02)', '');
        }, 200);
    }
    
    // Método para recargar el flip book
    reload() {
        this.currentPage = 0;
        this.isFlipping = false;
        this.createFlipBook();
        this.setupControls();
        this.setupEventListeners();
        this.showPage(0);
    }
}

// Función de inicialización global
function initFlipBook(containerId, pagesData) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Contenedor ${containerId} no encontrado`);
        return null;
    }
    
    return new FlipBook(container, pagesData);
}

// Exportar para uso global
window.FlipBook = FlipBook;
window.initFlipBook = initFlipBook;

