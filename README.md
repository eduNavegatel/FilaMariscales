# 🏰 Filá Mariscales - Portal Web

Portal web oficial de la Filá Mariscales de Caballeros Templarios de Elche.

## 📁 Estructura del Proyecto

```
prueba-php/
├── 📁 public/                 # DocumentRoot (Archivos públicos)
│   ├── 📁 assets/            # Recursos estáticos (CSS, JS, imágenes)
│   ├── 📁 uploads/           # Archivos subidos
│   ├── index.php             # Punto de entrada principal
│   ├── manifest.json         # PWA Manifest
│   ├── sw.js                 # Service Worker
│   ├── offline.html          # Página offline
│   └── .htaccess             # Configuración Apache
├── 📁 src/                   # Código fuente PHP (MVC)
│   ├── 📁 config/            # Configuraciones
│   ├── 📁 controllers/       # Controladores
│   ├── 📁 models/            # Modelos
│   ├── 📁 views/             # Vistas
│   ├── 📁 core/              # Núcleo del sistema
│   ├── 📁 helpers/           # Funciones auxiliares
│   └── 📁 middleware/        # Middleware
├── 📁 database/              # Scripts de base de datos
├── 📁 routes/                # Definición de rutas
├── 📁 uploads/               # Archivos subidos (backup)
├── 📁 vendor/                # Dependencias Composer
├── composer.json             # Dependencias PHP
├── .env                      # Variables de entorno
├── .htaccess                 # Configuración Apache raíz
└── README.md                 # Documentación principal
```

## 🚀 Características Principales

### 📖 **Flip Book Interactivo**
- Libro digital de la historia de la Filá
- Efecto 3D de giro de páginas
- Navegación por capítulos
- Diseño medieval auténtico
- **URL**: `http://localhost/prueba-php/libro`

### 📱 **Progressive Web App (PWA)**
- Instalable en dispositivos móviles
- Funcionamiento offline
- Notificaciones push
- Generación de APK

### 🎨 **Diseño Responsivo**
- Adaptable a todos los dispositivos
- Interfaz moderna y elegante
- Temática medieval templaria

### 🔐 **Sistema de Usuarios**
- Registro e inicio de sesión
- Panel de administración
- Gestión de socios
- Sistema de contraseñas seguras

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.2, MySQL
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Framework**: MVC personalizado
- **PWA**: Service Workers, Manifest
- **APK**: PWA Builder, Bubblewrap

## 📋 Requisitos del Sistema

- **Servidor**: Apache 2.4+
- **PHP**: 8.0 o superior
- **MySQL**: 5.7 o superior
- **Navegador**: Chrome, Firefox, Safari, Edge

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd prueba-php
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar base de datos**
   - Crear base de datos MySQL
   - Importar `database/schema.sql`
   - Configurar `.env`

4. **Configurar servidor web**
   - Apuntar DocumentRoot a `public/`
   - Habilitar mod_rewrite

5. **Configurar permisos**
   ```bash
   chmod 755 uploads/
   chmod 644 .env
   ```

## 🎮 Uso del Flip Book

### Navegación
- **Clic en páginas**: Mitad izquierda = anterior, mitad derecha = siguiente
- **Teclado**: Flechas izquierda/derecha
- **Botones de capítulos**: Navegación directa

### Características
- 6 capítulos de la historia de la Filá
- Efecto 3D de giro de páginas
- Diseño medieval auténtico
- Texto completamente legible

## 📱 Generación de APK

### Método 1: PWA Builder
1. Acceder a https://www.pwabuilder.com
2. Introducir URL: `http://localhost/prueba-php`
3. Generar APK
4. Descargar e instalar

### Método 2: Bubblewrap
```bash
npm install -g @bubblewrap/cli
bubblewrap init --manifest https://localhost/prueba-php/manifest.json
bubblewrap build
```

## 🔧 Configuración

### Variables de Entorno (.env)
```env
DB_HOST=localhost
DB_NAME=filamariscales
DB_USER=usuario
DB_PASS=contraseña
APP_URL=http://localhost/prueba-php
```

### Base de Datos
- Importar `database/schema.sql`
- Configurar credenciales en `.env`
- Ejecutar `database/notifications.sql` para notificaciones

## 📚 Documentación Técnica

### 📖 **Flip Book**
- **Archivos**: `public/assets/css/flipbook.css`, `public/assets/js/flipbook.js`
- **Vista**: `src/views/pages/libro.php`
- **Características**: Efecto 3D, navegación por capítulos, diseño medieval

### 📱 **PWA**
- **Manifest**: `public/manifest.json`
- **Service Worker**: `public/sw.js`
- **Página offline**: `public/offline.html`
- **Características**: Instalable, offline, notificaciones

### 🎨 **Diseño Responsivo**
- **CSS**: `public/assets/css/responsive-modern.css`
- **JavaScript**: `public/assets/js/responsive-modern.js`
- **Características**: Mobile-first, breakpoints, flexbox

### 🔐 **Seguridad**
- **Middleware**: `src/middleware/SecurityMiddleware.php`
- **Helpers**: `src/helpers/SecurityHelper.php`
- **Configuración**: `src/config/security.php`

## 🗑️ Archivos Eliminados (Limpieza)

### ❌ **Archivos de Desarrollo Temporal**
- `generar-apk-simple.php`
- `abrir-pwa-builder.bat`
- `generar-apk-auto.bat`
- `pwa-builder-config-simple.json`
- `debug-routes.php`
- `email_log.txt`

### ❌ **Archivos de Prueba**
- `public/test-responsive.php`
- `public/socios-direct.php`
- `public/setup-notifications.php`
- `public/update-database.php`
- `public/serve-image.php`

### ❌ **Documentación Redundante**
- `FLIPBOOK-IMPLEMENTACION.md`
- `GUIA-APK-DIRECTA.md`
- `GUIA-PWA-MOVIL.md`
- `RESPONSIVE-DESIGN-MODERNO.md`
- `MEJORAS-IMPLEMENTADAS.md`
- `SOLUCION-CONTRASEÑAS-REALES.md`
- `INSTRUCCIONES-PRUEBA.md`
- `README-SOCIOS.md`

### ❌ **Scripts de Configuración Temporales**
- `create-icons.php`
- `setup-pwa.php`
- `setup-improvements.php`
- `setup-socios.php`
- `agregar-contraseña.php`
- `generate-apk.js`
- `pwa-builder-config.json`

## 🎯 Beneficios de la Limpieza

### ✅ **Estructura Optimizada**
- Solo archivos esenciales
- Documentación consolidada
- Código limpio y organizado

### ✅ **Mantenimiento Simplificado**
- Menos archivos que mantener
- Documentación centralizada
- Estructura clara

### ✅ **Rendimiento Mejorado**
- Menos archivos que cargar
- Código optimizado
- Estructura eficiente

## 🤝 Contribución

1. Fork el proyecto
2. Crear rama feature (`git checkout -b feature/AmazingFeature`)
3. Commit cambios (`git commit -m 'Add AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 👥 Autores

- **Desarrollador Principal**: [Tu Nombre]
- **Filá Mariscales**: Caballeros Templarios de Elche

## 🙏 Agradecimientos

- Filá Mariscales de Caballeros Templarios
- Comunidad de Elche
- Contribuidores del proyecto

---

**🏰 Honor, Valor y Tradición** - Filá Mariscales de Caballeros Templarios
