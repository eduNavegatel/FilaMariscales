/**
 * Flip Book - Estilo Libro Antiguo
 */

class FlipBook {
    constructor(container, pages) {
        this.container = container;
        this.pages = pages;
        this.currentPage = 0;
        this.isFlipping = false;
        
        this.init();
    }
    
    init() {
        this.createFlipBook();
        this.setupControls();
        this.showPage(0);
    }
    
    createFlipBook() {
        this.container.innerHTML = `
            <div class="flipbook">
                ${this.pages.map((page, index) => this.createPage(page, index)).join('')}
            </div>
        `;
        
        this.pageElements = this.container.querySelectorAll('.flipbook-page');
    }
    
    createPage(pageData, index) {
        const pageNumber = index + 1;
        
        return `
            <div class="flipbook-page" data-page="${pageNumber}">
                <div class="page-front">
                    <div class="page-header">
                        <div class="page-number">CAPÍTULO ${pageNumber}</div>
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
                                <img src="${pageData.imagen}" alt="${pageData.titulo}">
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
                        <div class="page-number">CAPÍTULO ${pageNumber + 1}</div>
                        <div class="page-date">${this.pages[index + 1]?.fecha || 'Continuará...'}</div>
                    </div>
                    
                    <div class="page-content">
                        ${this.pages[index + 1] ? `
                            <h2 class="page-title">${this.pages[index + 1].titulo}</h2>
                            <h3 class="page-subtitle">${this.pages[index + 1].subtitulo}</h3>
                            
                            <div class="page-text">
                                ${this.pages[index + 1].contenido.slice(0, 2).map(parrafo => `<p>${parrafo}</p>`).join('')}
                            </div>
                        ` : `
                            <div class="page-text">
                                <p>Este es el final del libro de la historia de la Filá Mariscales.</p>
                                <p>Que la tradición templaria perdure por siempre.</p>
                            </div>
                        `}
                    </div>
                    
                    <div class="page-footer">
                        <div class="page-quote">
                            "Honor, valor y tradición."
                            <br><small>- Caballeros Templarios</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    setupControls() {
        // Teclado
        document.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'ArrowLeft':
                    this.previousPage();
                    break;
                case 'ArrowRight':
                    this.nextPage();
                    break;
            }
        });
        
        // Clic en páginas
        this.pageElements.forEach((page, index) => {
            page.addEventListener('click', (e) => {
                const rect = page.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;
                
                if (x < width / 2) {
                    this.previousPage();
                } else {
                    this.nextPage();
                }
            });
        });
    }
    
    previousPage() {
        if (this.currentPage > 0 && !this.isFlipping) {
            this.flipPage(this.currentPage - 1, 'backward');
        }
    }
    
    nextPage() {
        if (this.currentPage < this.pages.length - 1 && !this.isFlipping) {
            this.flipPage(this.currentPage + 1, 'forward');
        }
    }
    
    flipPage(targetPage, direction) {
        if (this.isFlipping) return;
        
        this.isFlipping = true;
        const currentPageElement = this.pageElements[this.currentPage];
        
        // Agregar clase de giro
        currentPageElement.classList.add('flipping');
        
        // Efecto de giro
        if (direction === 'forward') {
            currentPageElement.style.transform = 'translate(-50%, -50%) rotateY(-180deg)';
        } else {
            currentPageElement.style.transform = 'translate(-50%, -50%) rotateY(180deg)';
        }
        
        // Actualizar página actual
        setTimeout(() => {
            this.currentPage = targetPage;
            this.isFlipping = false;
            
            // Actualizar estado de todas las páginas
            this.updatePageStates();
            
            // Actualizar botones de capítulos
            this.updateChapterButtons(this.currentPage);
        }, 1000);
    }
    
    updatePageStates() {
        this.pageElements.forEach((page, index) => {
            if (index < this.currentPage) {
                page.classList.add('flipped');
                page.classList.remove('flipping');
                page.style.transform = 'translate(-50%, -50%) rotateY(-180deg)';
            } else {
                page.classList.remove('flipped', 'flipping');
                page.style.transform = 'translate(-50%, -50%)';
            }
        });
    }
    
    goToPage(pageIndex) {
        if (pageIndex < 0 || pageIndex >= this.pages.length || this.isFlipping) return;
        
        this.currentPage = pageIndex;
        this.showPage(this.currentPage);
    }
    
    showPage(pageIndex) {
        this.currentPage = pageIndex;
        this.updatePageStates();
        this.updateChapterButtons(pageIndex);
    }
    
    updateChapterButtons(currentPage) {
        const chapterBtns = document.querySelectorAll('.chapter-btn');
        chapterBtns.forEach((btn, index) => {
            btn.classList.toggle('active', index === currentPage);
        });
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

