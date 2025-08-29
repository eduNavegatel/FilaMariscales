# 📱 Guía Directa - Generar e Instalar APK

## 🚀 **Método Más Fácil: PWA Builder**

### **Paso 1: Preparar el Sitio**
1. **Asegúrate de que XAMPP esté corriendo** (Apache y MySQL)
2. **Tu sitio está disponible en**: `http://192.168.1.150/prueba-php`

### **Paso 2: Generar APK**
1. **Ve a**: https://www.pwabuilder.com
2. **Pega esta URL**: `http://192.168.1.150/prueba-php`
3. **Haz clic en "Build My PWA"**
4. **Selecciona "Android"**
5. **Configura**:
   - Package ID: `com.filamariscales.app`
   - Package Name: `Filá Mariscales`
   - Version: `1.0.0`
6. **Haz clic en "Generate Package"**
7. **Descarga el APK**

### **Paso 3: Instalar en tu Móvil**

#### **En Android:**
1. **Transfiere el APK** a tu móvil (USB, email, WhatsApp, etc.)
2. **Ve a Configuración → Seguridad**
3. **Activa "Fuentes desconocidas"** o "Instalar apps desconocidas"
4. **Abre el APK** y sigue las instrucciones
5. **¡Listo!** La app aparecerá en tu pantalla de inicio

#### **En iPhone:**
- **Nota**: Los APK son solo para Android. Para iPhone necesitas usar la PWA directamente.

## 🔧 **Método Alternativo: Bubblewrap**

### **Si tienes Node.js instalado:**

```bash
# Instalar Bubblewrap
npm install -g @bubblewrap/cli

# Inicializar proyecto
bubblewrap init --manifest http://192.168.1.150/prueba-php/manifest.json

# Generar APK
bubblewrap build
```

### **El APK estará en:**
`android/app/build/outputs/apk/debug/app-debug.apk`

## 📱 **Transferir APK al Móvil**

### **Opción 1: USB**
1. Conecta tu móvil por USB
2. Copia el APK a la carpeta Downloads
3. Instala desde el móvil

### **Opción 2: Email**
1. Envía el APK por email a ti mismo
2. Descárgalo en tu móvil
3. Instala

### **Opción 3: WhatsApp/Telegram**
1. Envía el APK por WhatsApp o Telegram
2. Descárgalo en tu móvil
3. Instala

### **Opción 4: Google Drive/Dropbox**
1. Sube el APK a Google Drive o Dropbox
2. Compártelo contigo mismo
3. Descárgalo en tu móvil
4. Instala

## 🎯 **Verificar que Funciona**

### **Una vez instalada:**
- ✅ **Icono en pantalla de inicio**
- ✅ **Se abre como app nativa** (sin barra del navegador)
- ✅ **Flip Book funciona perfectamente**
- ✅ **Todas las páginas cargan correctamente**
- ✅ **Funciona offline** (contenido cacheado)

## 🔧 **Solución de Problemas**

### **Si PWA Builder no funciona:**
1. **Verifica que XAMPP esté corriendo**
2. **Prueba acceder desde tu PC**: `http://192.168.1.150/prueba-php`
3. **Si no funciona, usa localhost**: `http://localhost/prueba-php`

### **Si el APK no se instala:**
1. **Verifica "Fuentes desconocidas"** está activado
2. **Reintenta la instalación**
3. **Limpia cache del instalador de apps**

### **Si la app no funciona:**
1. **Desinstala y vuelve a instalar**
2. **Verifica que tienes conexión a internet** (para cargar contenido)
3. **Prueba el modo offline** después de visitar todas las páginas

## 📋 **URLs Importantes**

- **Sitio Web**: http://192.168.1.150/prueba-php
- **Manifest**: http://192.168.1.150/prueba-php/manifest.json
- **PWA Builder**: https://www.pwabuilder.com
- **Bubblewrap**: https://github.com/GoogleChromeLabs/bubblewrap

## 🎉 **¡Resultado Final!**

Una vez que tengas el APK instalado:
- **Acceso directo** desde tu pantalla de inicio
- **Experiencia nativa** como una app real
- **Flip Book espectacular** con animaciones 3D
- **Funcionamiento offline** para contenido cacheado
- **Notificaciones push** para eventos importantes

---

**¡La Filá Mariscales ahora es una app móvil nativa! 📱🛡️**

