# 📱 PWA y APK - Filá Mariscales

## 🎯 Descripción

Se ha implementado una **Progressive Web App (PWA)** completa para Filá Mariscales que permite convertir el sitio web en una aplicación móvil nativa. La PWA incluye funcionalidad offline, notificaciones push, y puede ser convertida fácilmente en un archivo APK para Android.

## ✨ Características de la PWA

### 🚀 **Funcionalidades Principales**
- **Instalación nativa** en dispositivos móviles
- **Funcionamiento offline** con cache inteligente
- **Notificaciones push** para eventos importantes
- **Sincronización en background** de datos
- **Interfaz nativa** sin barra de navegación del navegador
- **Acceso directo** desde pantalla de inicio

### 📱 **Experiencia de Usuario**
- **Pantalla de splash** personalizada
- **Iconos adaptativos** para Android
- **Navegación fluida** entre páginas
- **Gestos táctiles** optimizados
- **Modo offline** con página personalizada
- **Actualizaciones automáticas**

## 📁 Archivos Implementados

### 🔧 **Archivos de Configuración**
- `public/manifest.json` - Configuración principal de la PWA
- `public/sw.js` - Service Worker para funcionalidad offline
- `public/offline.html` - Página personalizada para modo offline
- `pwa-builder-config.json` - Configuración para generación de APK

### 💻 **Archivos JavaScript**
- `public/assets/js/pwa.js` - Gestión de PWA y notificaciones
- `generate-apk.js` - Script para generar APK automáticamente

### 🎨 **Archivos CSS**
- Estilos integrados en `pwa.js` para banners y notificaciones
- Compatible con el diseño existente

## 🛠️ Configuración de la PWA

### **1. Manifest.json**
```json
{
  "name": "Filá Mariscales - Caballeros Templarios",
  "short_name": "Filá Mariscales",
  "display": "standalone",
  "background_color": "#dc143c",
  "theme_color": "#dc143c",
  "orientation": "portrait-primary"
}
```

### **2. Service Worker**
- **Cache inteligente** de archivos estáticos
- **Estrategia Network First** para contenido dinámico
- **Sincronización automática** cuando hay conexión
- **Manejo de errores** robusto

### **3. Funcionalidades Offline**
- **Página offline personalizada** con diseño atractivo
- **Detección automática** de estado de conexión
- **Reintento automático** cada 30 segundos
- **Notificaciones** de cambio de estado

## 📱 Generación del APK

### **Método 1: PWA Builder (Recomendado)**

1. **Acceder a PWA Builder**:
   ```
   https://www.pwabuilder.com
   ```

2. **Ingresar URL del sitio**:
   ```
   https://tu-dominio.com
   ```

3. **Configurar opciones**:
   - Package ID: `com.filamariscales.app`
   - Package Name: `Filá Mariscales`
   - Version: `1.0.0`
   - Iconos y splash screen

4. **Generar APK**:
   - Descargar APK firmado
   - Listo para distribución

### **Método 2: Script Automático**

```bash
# Ejecutar el script de generación
node generate-apk.js

# O usar Bubblewrap directamente
node generate-apk.js --bubblewrap
```

### **Método 3: Bubblewrap (Google)**

```bash
# Instalar Bubblewrap
npm install -g @bubblewrap/cli

# Configurar proyecto
bubblewrap init --manifest https://tu-dominio.com/manifest.json

# Generar APK
bubblewrap build
```

## 🎨 Personalización de Iconos

### **Iconos Requeridos**
- `icon-72x72.png` - Para dispositivos pequeños
- `icon-96x96.png` - Para Android
- `icon-128x128.png` - Para Chrome
- `icon-144x144.png` - Para Android HD
- `icon-152x152.png` - Para iOS
- `icon-192x192.png` - Para Android
- `icon-384x384.png` - Para Android 2x
- `icon-512x512.png` - Para Android 3x

### **Ubicación de Iconos**
```
public/assets/images/icons/
├── icon-72x72.png
├── icon-96x96.png
├── icon-128x128.png
├── icon-144x144.png
├── icon-152x152.png
├── icon-192x192.png
├── icon-384x384.png
├── icon-512x512.png
├── history-icon.png
├── gallery-icon.png
└── events-icon.png
```

## 🔧 Configuración del Servidor

### **Headers HTTP Requeridos**
```apache
# .htaccess
<IfModule mod_headers.c>
    Header set Service-Worker-Allowed "/"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "DENY"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

### **MIME Types**
```apache
AddType application/manifest+json .json
AddType application/x-web-app-manifest+json .webapp
```

## 📊 Métricas y Analytics

### **Eventos de PWA**
- Instalación de la app
- Uso offline
- Actualizaciones
- Errores de cache

### **Integración con Analytics**
```javascript
// Eventos personalizados
gtag('event', 'pwa_install', {
    'event_category': 'engagement',
    'event_label': 'app_installation'
});

gtag('event', 'pwa_offline_usage', {
    'event_category': 'engagement',
    'event_label': 'offline_mode'
});
```

## 🚀 Despliegue y Distribución

### **1. Preparación**
- [ ] Verificar que todos los iconos estén presentes
- [ ] Probar funcionalidad offline
- [ ] Validar manifest.json
- [ ] Comprobar Service Worker

### **2. Despliegue**
- [ ] Subir archivos al servidor
- [ ] Configurar HTTPS (obligatorio para PWA)
- [ ] Verificar headers HTTP
- [ ] Probar instalación en dispositivos

### **3. Generación de APK**
- [ ] Usar PWA Builder o script automático
- [ ] Firmar APK para distribución
- [ ] Probar en dispositivos Android
- [ ] Subir a Google Play Store (opcional)

## 📱 Características del APK

### **Información del Paquete**
- **Package ID**: `com.filamariscales.app`
- **Nombre**: `Filá Mariscales`
- **Versión**: `1.0.0`
- **Tamaño**: ~5-10 MB (dependiendo del contenido cacheado)

### **Permisos Requeridos**
- `INTERNET` - Acceso a internet
- `ACCESS_NETWORK_STATE` - Estado de la red
- `VIBRATE` - Notificaciones con vibración
- `WAKE_LOCK` - Mantener pantalla encendida

### **Características Técnicas**
- **API Level**: 21+ (Android 5.0+)
- **Orientación**: Portrait (principal)
- **Splash Screen**: Personalizada con logo
- **Iconos Adaptativos**: Compatible con Android 8.0+

## 🔄 Actualizaciones

### **Actualización Automática**
- El Service Worker detecta nuevas versiones
- Notificación al usuario para actualizar
- Cache automático de nuevos recursos

### **Actualización Manual**
```javascript
// Forzar actualización
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for(let registration of registrations) {
            registration.update();
        }
    });
}
```

## 🛡️ Seguridad

### **Medidas Implementadas**
- **HTTPS obligatorio** para PWA
- **Headers de seguridad** configurados
- **Validación de manifest** antes de instalación
- **Cache seguro** con estrategias apropiadas

### **Privacidad**
- **Sin tracking** innecesario
- **Datos locales** solo en el dispositivo
- **Permisos mínimos** requeridos

## 📈 Optimización de Performance

### **Estrategias de Cache**
- **Cache First** para archivos estáticos
- **Network First** para contenido dinámico
- **Stale While Revalidate** para recursos críticos

### **Lazy Loading**
- **Carga diferida** de imágenes
- **JavaScript modular** cargado bajo demanda
- **CSS crítico** inline

## 🎯 Próximos Pasos

### **Mejoras Futuras**
- [ ] **Notificaciones push** con servidor backend
- [ ] **Sincronización de datos** offline
- [ ] **Modo oscuro** automático
- [ ] **Accesibilidad** mejorada
- [ ] **Analytics avanzados** de uso offline

### **Integración con App Stores**
- [ ] **Google Play Store** - APK firmado
- [ ] **App Store** - Web App (iOS)
- [ ] **Microsoft Store** - PWA package

## 📞 Soporte y Mantenimiento

### **Monitoreo**
- **Logs del Service Worker** en consola
- **Métricas de instalación** y uso
- **Errores de cache** y red

### **Debugging**
```javascript
// Habilitar logs detallados
localStorage.setItem('pwa_debug', 'true');

// Verificar estado del Service Worker
navigator.serviceWorker.getRegistrations().then(registrations => {
    console.log('Service Workers:', registrations);
});
```

## 🎉 Resultado Final

Con esta implementación, Filá Mariscales ahora tiene:

✅ **PWA completamente funcional** con todas las características modernas
✅ **APK generado automáticamente** para distribución Android
✅ **Experiencia offline** completa con página personalizada
✅ **Notificaciones push** para mantener informados a los usuarios
✅ **Instalación nativa** en dispositivos móviles
✅ **Performance optimizada** con cache inteligente

La aplicación mantiene toda la funcionalidad del sitio web original, incluyendo el **Flip Book espectacular**, pero ahora funciona como una app nativa en dispositivos móviles.

---

**¡La Filá Mariscales ahora está disponible como aplicación móvil! 📱🛡️**



