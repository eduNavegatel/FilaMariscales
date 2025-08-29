# 📱 Guía Rápida - Probar PWA en Móvil

## 🚀 **Cómo Acceder desde tu Móvil**

### **Opción 1: Acceso Directo (Más Fácil)**

1. **Asegúrate de que XAMPP esté corriendo** en tu PC
2. **Conecta tu móvil a la misma red WiFi** que tu PC
3. **Encuentra la IP de tu PC**:
   - Windows: Abre CMD y escribe `ipconfig`
   - Busca la línea "IPv4 Address" (ejemplo: 192.168.1.100)

4. **En tu móvil, abre el navegador** y ve a:
   ```
   http://TU-IP-DEL-PC/prueba-php
   ```
   Ejemplo: `http://192.168.1.100/prueba-php`

### **Opción 2: Usando localhost (Si estás cerca)**

Si tu móvil puede acceder a tu PC directamente:
```
http://localhost/prueba-php
```

## 📲 **Instalar como App**

Una vez que accedas desde tu móvil:

### **En Chrome (Android):**
1. **Abrir el sitio** en Chrome
2. **Aparecerá un banner** "Instalar Filá Mariscales"
3. **Tocar "Instalar"** o ir a Menú → "Instalar app"
4. **Confirmar instalación**
5. **¡Listo!** La app aparecerá en tu pantalla de inicio

### **En Safari (iPhone):**
1. **Abrir el sitio** en Safari
2. **Tocar el botón compartir** (cuadrado con flecha)
3. **Seleccionar "Agregar a pantalla de inicio"**
4. **Confirmar** y personalizar nombre
5. **¡Listo!** La app aparecerá en tu pantalla de inicio

## 🎯 **Funcionalidades que Puedes Probar**

### **✅ PWA Funcionalidades:**
- **Instalación nativa** - Se instala como app real
- **Modo offline** - Funciona sin internet
- **Notificaciones** - Recibe alertas de eventos
- **Navegación fluida** - Sin barra del navegador
- **Flip Book** - El libro interactivo funciona perfectamente

### **📱 Experiencia Móvil:**
- **Responsive design** - Se adapta a tu pantalla
- **Gestos táctiles** - Navegación con dedos
- **Performance optimizada** - Carga rápida
- **Cache inteligente** - Contenido guardado localmente

## 🔧 **Solución de Problemas**

### **Si no aparece el banner de instalación:**
1. **Verificar HTTPS** - La PWA necesita conexión segura
2. **Limpiar cache** del navegador
3. **Recargar la página** varias veces
4. **Verificar que el Service Worker esté activo**

### **Si no funciona offline:**
1. **Visitar todas las páginas** primero (para cachear)
2. **Esperar unos segundos** para que se descargue el contenido
3. **Desconectar WiFi** y probar

### **Si no carga desde móvil:**
1. **Verificar que XAMPP esté corriendo**
2. **Comprobar que estén en la misma red WiFi**
3. **Verificar la IP de tu PC**
4. **Probar con la IP en lugar de localhost**

## 📊 **Verificar que Funciona**

### **Indicadores de PWA Activa:**
- ✅ **Banner de instalación** aparece
- ✅ **Icono en pantalla de inicio** después de instalar
- ✅ **Funciona sin barra del navegador**
- ✅ **Modo offline disponible**
- ✅ **Notificaciones funcionan**

### **Comandos de Verificación:**
En la consola del navegador móvil:
```javascript
// Verificar Service Worker
navigator.serviceWorker.getRegistrations().then(registrations => {
    console.log('Service Workers:', registrations);
});

// Verificar PWA instalada
console.log('PWA Instalada:', window.matchMedia('(display-mode: standalone)').matches);
```

## 🎉 **¡Disfruta tu PWA!**

Una vez instalada, tendrás:
- **Acceso rápido** desde tu pantalla de inicio
- **Experiencia nativa** como una app real
- **Funcionamiento offline** para contenido cacheado
- **Notificaciones push** para eventos importantes
- **Flip Book espectacular** funcionando perfectamente

---

**¡La Filá Mariscales ahora está en tu móvil como una app nativa! 📱🛡️**

