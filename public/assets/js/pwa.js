/**
 * 📱 PWA Manager para Filá Mariscales
 * Maneja instalación, actualizaciones y funcionalidades PWA
 */

class PWAManager {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.isOnline = navigator.onLine;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.checkInstallation();
        this.setupNetworkDetection();
        this.setupServiceWorker();
    }
    
    setupEventListeners() {
        // Evento para capturar la instalación
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('📱 Evento de instalación capturado');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallPrompt();
        });
        
        // Evento cuando la app se instala
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA instalada correctamente');
            this.isInstalled = true;
            this.hideInstallPrompt();
            this.showInstallationSuccess();
        });
        
        // Detectar si ya está instalada
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('📱 PWA ejecutándose en modo standalone');
            this.isInstalled = true;
        }
    }
    
    setupNetworkDetection() {
        window.addEventListener('online', () => {
            console.log('🌐 Conexión restaurada');
            this.isOnline = true;
            this.showOnlineNotification();
        });
        
        window.addEventListener('offline', () => {
            console.log('📡 Conexión perdida');
            this.isOnline = false;
            this.showOfflineNotification();
        });
    }
    
    async setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('🛡️ Service Worker registrado:', registration);
                
                // Manejar actualizaciones del Service Worker
                registration.addEventListener('updatefound', () => {
                    console.log('🔄 Nueva versión del Service Worker disponible');
                    this.showUpdateNotification();
                });
                
                // Escuchar mensajes del Service Worker
                navigator.serviceWorker.addEventListener('message', (event) => {
                    console.log('💬 Mensaje del Service Worker:', event.data);
                    this.handleServiceWorkerMessage(event.data);
                });
                
            } catch (error) {
                console.error('❌ Error registrando Service Worker:', error);
            }
        }
    }
    
    checkInstallation() {
        // Verificar si la app ya está instalada
        if (window.matchMedia('(display-mode: standalone)').matches) {
            this.isInstalled = true;
        }
    }
    
    showInstallPrompt() {
        if (this.isInstalled) return;
        
        const installBanner = document.createElement('div');
        installBanner.id = 'pwa-install-banner';
        installBanner.innerHTML = `
            <div class="pwa-install-content">
                <div class="pwa-install-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="pwa-install-text">
                    <h4>Instalar Filá Mariscales</h4>
                    <p>Acceso rápido desde tu pantalla de inicio</p>
                </div>
                <div class="pwa-install-actions">
                    <button class="pwa-install-btn" onclick="pwaManager.installApp()">
                        <i class="fas fa-plus"></i> Instalar
                    </button>
                    <button class="pwa-install-close" onclick="pwaManager.hideInstallPrompt()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(installBanner);
        
        // Auto-ocultar después de 10 segundos
        setTimeout(() => {
            this.hideInstallPrompt();
        }, 10000);
    }
    
    hideInstallPrompt() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.remove();
        }
    }
    
    async installApp() {
        if (!this.deferredPrompt) {
            console.log('❌ No hay prompt de instalación disponible');
            return;
        }
        
        try {
            console.log('📱 Iniciando instalación...');
            this.deferredPrompt.prompt();
            
            const { outcome } = await this.deferredPrompt.userChoice;
            console.log('📱 Resultado de instalación:', outcome);
            
            this.deferredPrompt = null;
            
            if (outcome === 'accepted') {
                this.showInstallationSuccess();
            }
            
        } catch (error) {
            console.error('❌ Error durante la instalación:', error);
        }
    }
    
    showInstallationSuccess() {
        const notification = this.createNotification(
            '✅ Filá Mariscales instalada',
            'La app se ha instalado correctamente en tu dispositivo',
            'success'
        );
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    showOnlineNotification() {
        const notification = this.createNotification(
            '🌐 Conexión restaurada',
            'Ya puedes acceder a todo el contenido',
            'success'
        );
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    showOfflineNotification() {
        const notification = this.createNotification(
            '📡 Modo offline',
            'Algunas funciones pueden no estar disponibles',
            'warning'
        );
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    showUpdateNotification() {
        const notification = this.createNotification(
            '🔄 Nueva versión disponible',
            'Recarga la página para actualizar',
            'info',
            () => {
                window.location.reload();
            }
        );
        
        document.body.appendChild(notification);
    }
    
    createNotification(title, message, type = 'info', action = null) {
        const notification = document.createElement('div');
        notification.className = `pwa-notification pwa-notification-${type}`;
        notification.innerHTML = `
            <div class="pwa-notification-content">
                <div class="pwa-notification-icon">
                    <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                </div>
                <div class="pwa-notification-text">
                    <h5>${title}</h5>
                    <p>${message}</p>
                </div>
                ${action ? '<button class="pwa-notification-action">Actualizar</button>' : ''}
                <button class="pwa-notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        if (action) {
            const actionBtn = notification.querySelector('.pwa-notification-action');
            actionBtn.addEventListener('click', action);
        }
        
        return notification;
    }
    
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            warning: 'exclamation-triangle',
            error: 'times-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    
    handleServiceWorkerMessage(data) {
        switch (data.type) {
            case 'CACHE_UPDATED':
                console.log('📦 Cache actualizado');
                break;
            case 'NEW_CONTENT_AVAILABLE':
                console.log('🆕 Nuevo contenido disponible');
                this.showUpdateNotification();
                break;
            default:
                console.log('💬 Mensaje no reconocido:', data);
        }
    }
    
    // Métodos públicos para control externo
    getInstallationStatus() {
        return this.isInstalled;
    }
    
    getOnlineStatus() {
        return this.isOnline;
    }
    
    // Método para solicitar notificaciones push
    async requestNotificationPermission() {
        if (!('Notification' in window)) {
            console.log('❌ Notificaciones no soportadas');
            return false;
        }
        
        if (Notification.permission === 'granted') {
            console.log('✅ Permisos de notificación ya concedidos');
            return true;
        }
        
        if (Notification.permission === 'denied') {
            console.log('❌ Permisos de notificación denegados');
            return false;
        }
        
        try {
            const permission = await Notification.requestPermission();
            console.log('📱 Permiso de notificación:', permission);
            return permission === 'granted';
        } catch (error) {
            console.error('❌ Error solicitando permisos:', error);
            return false;
        }
    }
    
    // Método para enviar notificación local
    sendLocalNotification(title, options = {}) {
        if (Notification.permission === 'granted') {
            const notification = new Notification(title, {
                icon: '/assets/images/icons/icon-192x192.png',
                badge: '/assets/images/icons/icon-72x72.png',
                ...options
            });
            
            notification.addEventListener('click', () => {
                window.focus();
                notification.close();
            });
            
            return notification;
        }
    }
}

// ===== ESTILOS CSS PARA PWA =====
const pwaStyles = `
<style>
/* PWA Install Banner */
#pwa-install-banner {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: 20px;
    background: linear-gradient(135deg, var(--templar-red) 0%, var(--templar-red-dark) 100%);
    color: white;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    animation: slideInUp 0.5s ease-out;
}

.pwa-install-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pwa-install-icon {
    font-size: 2rem;
    color: white;
}

.pwa-install-text {
    flex: 1;
}

.pwa-install-text h4 {
    margin: 0;
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
}

.pwa-install-text p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.pwa-install-actions {
    display: flex;
    gap: 0.5rem;
}

.pwa-install-btn {
    background: white;
    color: var(--templar-red);
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-family: 'Cinzel', serif;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pwa-install-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.pwa-install-close {
    background: transparent;
    color: white;
    border: 1px solid white;
    padding: 8px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pwa-install-close:hover {
    background: white;
    color: var(--templar-red);
}

/* PWA Notifications */
.pwa-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    max-width: 350px;
    animation: slideInRight 0.5s ease-out;
}

.pwa-notification-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pwa-notification-icon {
    font-size: 1.5rem;
}

.pwa-notification-success .pwa-notification-icon {
    color: #28a745;
}

.pwa-notification-warning .pwa-notification-icon {
    color: #ffc107;
}

.pwa-notification-error .pwa-notification-icon {
    color: #dc3545;
}

.pwa-notification-info .pwa-notification-icon {
    color: #17a2b8;
}

.pwa-notification-text {
    flex: 1;
}

.pwa-notification-text h5 {
    margin: 0;
    font-family: 'Cinzel', serif;
    font-size: 1rem;
}

.pwa-notification-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

.pwa-notification-action {
    background: var(--templar-red);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pwa-notification-action:hover {
    background: var(--templar-red-dark);
}

.pwa-notification-close {
    background: transparent;
    color: #666;
    border: none;
    padding: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pwa-notification-close:hover {
    color: #333;
}

/* Animations */
@keyframes slideInUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 600px) {
    #pwa-install-banner {
        left: 10px;
        right: 10px;
        bottom: 10px;
    }
    
    .pwa-install-content {
        flex-direction: column;
        text-align: center;
    }
    
    .pwa-install-actions {
        justify-content: center;
    }
    
    .pwa-notification {
        right: 10px;
        left: 10px;
        max-width: none;
    }
}
</style>
`;

// Agregar estilos al documento
document.head.insertAdjacentHTML('beforeend', pwaStyles);

// ===== INICIALIZACIÓN =====
let pwaManager;

document.addEventListener('DOMContentLoaded', () => {
    pwaManager = new PWAManager();
    console.log('📱 PWA Manager inicializado');
});

// Exportar para uso global
window.PWAManager = PWAManager;
window.pwaManager = pwaManager;



