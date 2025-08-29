/**
 * 🛡️ Service Worker para Filá Mariscales PWA
 * Maneja cache, funcionalidad offline y notificaciones push
 */

const CACHE_NAME = 'fila-mariscales-v1.0.0';
const STATIC_CACHE = 'fila-mariscales-static-v1.0.0';
const DYNAMIC_CACHE = 'fila-mariscales-dynamic-v1.0.0';

// Archivos críticos para cachear inmediatamente
const STATIC_FILES = [
    '/',
    '/index.php',
    '/libro',
    '/galeria',
    '/eventos',
    '/socios',
    '/assets/css/style.css',
    '/assets/css/responsive-modern.css',
    '/assets/css/components-modern.css',
    '/assets/css/flipbook.css',
    '/assets/js/main.js',
    '/assets/js/responsive-modern.js',
    '/assets/js/flipbook.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
    'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&display=swap',
    'https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap'
];

// Archivos para cache dinámico
const DYNAMIC_FILES = [
    '/assets/images/',
    '/uploads/',
    'https://images.unsplash.com/',
    'https://picsum.photos/'
];

// ===== INSTALACIÓN =====
self.addEventListener('install', (event) => {
    console.log('🛡️ Service Worker instalando...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('📦 Cacheando archivos estáticos...');
                return cache.addAll(STATIC_FILES);
            })
            .then(() => {
                console.log('✅ Service Worker instalado correctamente');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('❌ Error instalando Service Worker:', error);
            })
    );
});

// ===== ACTIVACIÓN =====
self.addEventListener('activate', (event) => {
    console.log('🚀 Service Worker activando...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('🗑️ Eliminando cache antiguo:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('✅ Service Worker activado correctamente');
                return self.clients.claim();
            })
    );
});

// ===== INTERCEPTAR PETICIONES =====
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Estrategia: Cache First para archivos estáticos
    if (isStaticFile(request)) {
        event.respondWith(cacheFirst(request));
    }
    // Estrategia: Network First para contenido dinámico
    else if (isDynamicFile(request)) {
        event.respondWith(networkFirst(request));
    }
    // Estrategia: Network First para API y contenido fresco
    else {
        event.respondWith(networkFirst(request));
    }
});

// ===== ESTRATEGIAS DE CACHE =====

// Cache First: Para archivos estáticos
async function cacheFirst(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('❌ Error en cacheFirst:', error);
        return new Response('Error de conexión', { status: 503 });
    }
}

// Network First: Para contenido dinámico
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.log('📡 Sin conexión, usando cache...');
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Página offline personalizada
        if (request.destination === 'document') {
            return caches.match('/offline.html');
        }
        
        return new Response('Contenido no disponible offline', { status: 503 });
    }
}

// ===== FUNCIONES AUXILIARES =====

function isStaticFile(request) {
    const staticExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.woff', '.woff2', '.ttf'];
    const url = new URL(request.url);
    
    return staticExtensions.some(ext => url.pathname.endsWith(ext)) ||
           STATIC_FILES.includes(url.pathname) ||
           url.hostname === 'fonts.googleapis.com' ||
           url.hostname === 'fonts.gstatic.com' ||
           url.hostname === 'cdn.jsdelivr.net' ||
           url.hostname === 'cdnjs.cloudflare.com';
}

function isDynamicFile(request) {
    const url = new URL(request.url);
    
    return DYNAMIC_FILES.some(pattern => url.href.includes(pattern)) ||
           url.pathname.startsWith('/uploads/') ||
           url.pathname.startsWith('/assets/images/');
}

// ===== NOTIFICACIONES PUSH =====
self.addEventListener('push', (event) => {
    console.log('📱 Notificación push recibida');
    
    const options = {
        body: event.data ? event.data.text() : 'Nuevo evento de la Filá Mariscales',
        icon: '/assets/images/icons/icon-192x192.png',
        badge: '/assets/images/icons/icon-72x72.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Ver evento',
                icon: '/assets/images/icons/explore-icon.png'
            },
            {
                action: 'close',
                title: 'Cerrar',
                icon: '/assets/images/icons/close-icon.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('Filá Mariscales', options)
    );
});

// ===== CLICK EN NOTIFICACIONES =====
self.addEventListener('notificationclick', (event) => {
    console.log('👆 Notificación clickeada');
    
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/eventos')
        );
    } else if (event.action === 'close') {
        // Solo cerrar la notificación
    } else {
        // Click en el cuerpo de la notificación
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// ===== SINCRONIZACIÓN EN BACKGROUND =====
self.addEventListener('sync', (event) => {
    console.log('🔄 Sincronización en background:', event.tag);
    
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    try {
        // Sincronizar datos cuando hay conexión
        console.log('🔄 Sincronizando datos...');
        
        // Aquí puedes agregar lógica para sincronizar datos
        // Por ejemplo, enviar formularios pendientes, actualizar contenido, etc.
        
    } catch (error) {
        console.error('❌ Error en sincronización:', error);
    }
}

// ===== MENSAJES =====
self.addEventListener('message', (event) => {
    console.log('💬 Mensaje recibido:', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});

// ===== ERROR HANDLING =====
self.addEventListener('error', (event) => {
    console.error('❌ Error en Service Worker:', event.error);
});

self.addEventListener('unhandledrejection', (event) => {
    console.error('❌ Promesa rechazada no manejada:', event.reason);
});

console.log('🛡️ Service Worker cargado correctamente');


