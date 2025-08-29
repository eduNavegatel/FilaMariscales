# Filá Mariscales - Sistema de Gestión de Eventos

Sistema web optimizado para la gestión de eventos de la Filá Mariscales de Caballeros Templarios de Elche.

## 🎨 Características del Diseño

- **Tema Templario**: Colores basados en la bandera templaria (rojo y blanco)
- **Tipografía Medieval**: Fuentes Cinzel y Crimson Text para un aspecto histórico
- **Diseño Responsivo**: Adaptado a todos los dispositivos
- **Interfaz Moderna**: Bootstrap 5 con animaciones suaves
- **Navegación Intuitiva**: Menú completo con iconos descriptivos
- **Cursor Personalizado**: Cursor de espada templaria personalizado

## 🚀 Características del Sistema

- **Gestión de Usuarios**: Registro, autenticación y perfiles de usuarios
- **Panel de Administración**: Gestión completa de eventos, usuarios y contenido
- **Sistema de Eventos**: Creación, edición y gestión de eventos
- **Galería de Imágenes**: Subida y gestión de imágenes
- **Sistema de Rutas**: Router personalizado con soporte para middleware
- **Arquitectura MVC**: Código organizado y mantenible

## 🎯 Colores Templarios Implementados

### Paleta Principal
- **Rojo Templario**: `#DC143C` (Crimson)
- **Rojo Oscuro**: `#8B0000` (Dark Red)
- **Rojo Claro**: `#FF4444`
- **Blanco Puro**: `#FFFFFF`
- **Blanco Off**: `#F8F9FA`
- **Oro Templario**: `#FFD700` (accent)

### Uso en la Web
- **Fondo principal**: Blanco con patrones sutiles
- **Navbar**: Gradiente rojo templario
- **Botones**: Rojo templario con hover dorado
- **Texto**: Negro sobre fondo blanco para legibilidad
- **Acentos**: Dorado para elementos destacados

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache/Nginx con mod_rewrite habilitado
- Composer (opcional)

## 🛠️ Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd prueba-php
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL llamada `mariscales_db`
   - Importar el archivo `database/schema.sql`

3. **Configurar el servidor web**
   - Apuntar el DocumentRoot a la carpeta `public/`
   - Asegurar que mod_rewrite esté habilitado

4. **Verificar configuración**
   - Editar `src/config/config.php` si es necesario
   - Ajustar URL_ROOT según tu configuración

## 📁 Estructura del Proyecto

```
prueba-php/
├── public/                 # Punto de entrada público
│   ├── assets/            # CSS, JS e imágenes
│   │   ├── css/
│   │   │   ├── style.css  # Estilos principales templarios
│   │   │   └── admin.css  # Estilos del panel admin
│   │   └── js/
│   │       └── main.js    # JavaScript principal
│   ├── .htaccess          # Configuración Apache
│   └── index.php          # Front controller
├── src/                   # Código fuente
│   ├── config/           # Configuraciones
│   ├── controllers/      # Controladores
│   ├── models/          # Modelos
│   ├── views/           # Vistas
│   ├── helpers/         # Funciones auxiliares
│   └── core/            # Núcleo del sistema
├── routes/              # Definición de rutas
├── database/            # Esquemas de base de datos
└── vendor/              # Dependencias de Composer
```

---

# 🔐 PANEL DE ADMINISTRACIÓN

## 📍 Acceso al Panel de Administración

### **URL Principal:**
```
http://localhost/prueba-php/public/admin
```

### **URL de Login:**
```
http://localhost/prueba-php/public/admin/login
```

## 🔑 Credenciales de Administrador

### **Credenciales Principales:**
- **Usuario:** `admin`
- **Contraseña:** `admin123`

### **Credenciales Alternativas:**
- **Usuario:** `administrador`
- **Contraseña:** `admin`

## 🚀 Cómo Acceder

### **Método 1: Acceso Directo**
1. Abre tu navegador
2. Ve a: `http://localhost/prueba-php/public/admin`
3. Si no estás logueado, serás redirigido al login
4. Ingresa las credenciales
5. ¡Accede al panel de administración!

### **Método 2: Desde la Página Principal**
1. Ve a: `http://localhost/prueba-php/public/`
2. Añade `/admin` al final de la URL
3. Ingresa las credenciales
4. ¡Listo!

## 🛡️ Funcionalidades del Panel

### **Dashboard Principal**
- ✅ Vista general del sitio
- ✅ Estadísticas de usuarios
- ✅ Estadísticas de eventos
- ✅ Actividad reciente

### **Gestión de Usuarios**
- ✅ Ver todos los usuarios
- ✅ Crear nuevos usuarios
- ✅ Editar usuarios existentes
- ✅ Activar/desactivar usuarios

### **Gestión de Contenido**
- ✅ Editar páginas del sitio
- ✅ Gestionar noticias
- ✅ Administrar galería
- ✅ Configurar eventos

### **Configuración**
- ✅ Ajustes del sitio
- ✅ Configuración de seguridad
- ✅ Gestión de sesiones

## 🔒 Seguridad

### **Características de Seguridad:**
- ✅ **Sesión con timeout**: 1 hora de inactividad
- ✅ **Máximo intentos**: 5 intentos de login
- ✅ **Tiempo de bloqueo**: 15 minutos tras 5 intentos fallidos
- ✅ **Headers de seguridad**: Protección contra ataques
- ✅ **Validación de tokens**: CSRF protection

### **Recomendaciones:**
1. **Cambia las credenciales por defecto** en producción
2. **Usa contraseñas fuertes**
3. **Cierra sesión** cuando termines
4. **No compartas** las credenciales

---

# 🌐 URLs Disponibles

## 🏠 Menú Principal Reorganizado

### **Inicio**
- **URL:** `http://localhost/prueba-php/public/`
- **Descripción:** Página principal con información general

### **👥 Quienes Somos**
- **Historia:** `http://localhost/prueba-php/public/historia`
- **Directiva:** `http://localhost/prueba-php/public/directiva`
- **Blog:** `http://localhost/prueba-php/public/blog`
- **Libro:** `http://localhost/prueba-php/public/libro`
- **Noticias:** `http://localhost/prueba-php/public/noticias`

### **🔧 Utilidades**
- **Calendario:** `http://localhost/prueba-php/public/calendario`
- **Música:** `http://localhost/prueba-php/public/musica`
- **Descargas:** `http://localhost/prueba-php/public/descargas`
- **Galería:** `http://localhost/prueba-php/public/galeria`

### **📦 Recursos**
- **Tienda:** `http://localhost/prueba-php/public/tienda`
- **Patrocinadores:** `http://localhost/prueba-php/public/patrocinadores`
- **Hermanamientos:** `http://localhost/prueba-php/public/hermanamientos`

### **👤 Socios**
- **URL:** `http://localhost/prueba-php/public/socios`
- **Descripción:** Zona exclusiva para socios de la Filá Mariscales

## 🔧 Panel de Administración

### **Admin Principal**
- **Dashboard:** `http://localhost/prueba-php/public/admin`
- **Usuarios:** `http://localhost/prueba-php/public/admin/users`
- **Eventos:** `http://localhost/prueba-php/public/admin/events`
- **Galería:** `http://localhost/prueba-php/public/admin/gallery`
- **Configuración:** `http://localhost/prueba-php/public/admin/settings`

---

# ⚔️ Cursor Personalizado de Espada

## 🎯 Descripción

Se ha implementado un cursor personalizado con forma de espada para toda la aplicación web de la Filá Mariscales. El cursor cambia de color según el contexto de uso, manteniendo la temática templaria del sitio.

## 🎨 Tipos de Cursor

### 1. **Cursor Normal (Emoji)**
- **Apariencia:** Emoji de espada roja (⚔️)
- **Color:** Rojo templario (#DC143C)
- **Uso:** Áreas generales de la página

### 2. **Cursor en Enlaces y Botones**
- **Apariencia:** Emoji de espada dorada (⚔️)
- **Color:** Dorado (#FFD700)
- **Uso:** Enlaces, botones y elementos interactivos

### 3. **Cursor de Texto**
- **Apariencia:** Cursor de texto estándar
- **Uso:** Campos de entrada, áreas de texto editables

### 4. **Cursor de Espera**
- **Apariencia:** Cursor de espera estándar
- **Uso:** Elementos deshabilitados o en proceso de carga

## 🔧 Implementación Técnica

### Archivo CSS Principal
```css
/* Cursor personalizado de espada */
* {
    cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><text x="12" y="18" font-family="Arial, sans-serif" font-size="20" text-anchor="middle" fill="%23DC143C">⚔️</text></svg>') 12 12, auto;
}

/* Cursor de enlace para botones y enlaces */
a, button, .btn, [role="button"], [type="button"], [type="submit"] {
    cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><text x="12" y="18" font-family="Arial, sans-serif" font-size="20" text-anchor="middle" fill="%23FFD700">⚔️</text></svg>') 12 12, pointer;
}

/* Cursor de texto para inputs y áreas de texto */
input, textarea, select, [contenteditable="true"] {
    cursor: text;
}

/* Cursor de espera */
*:disabled, .loading {
    cursor: wait;
}
```

---

# 🔧 Solución para el Modal de Edición de Usuarios

## 📋 Problemas Identificados

### 1. **Inconsistencia en la Base de Datos**
- La tabla `users` no tenía el campo `activo` (TINYINT)
- El campo `rol` no incluía el valor 'socio' en el ENUM
- Los usuarios existentes no tenían el campo `activo` definido

### 2. **Problemas de Routing**
- La ruta `/admin/editarUsuario/{id}` no se manejaba correctamente en `public/index.php`
- El controlador no recibía el ID del usuario correctamente

### 3. **Problemas en el Modelo**
- El método `updateUser` no verificaba si el ID estaba presente
- No había manejo adecuado de errores en la base de datos

## 🛠️ Soluciones Aplicadas

### 1. **Actualización del Esquema de Base de Datos**
- ✅ Agregado campo `activo TINYINT(1) DEFAULT 1`
- ✅ Modificado ENUM del campo `rol` para incluir 'socio'
- ✅ Actualizados usuarios existentes con `activo = 1`

### 2. **Corrección del Routing**
- ✅ Actualizado `public/index.php` para manejar rutas dinámicas
- ✅ Mejorado el manejo de parámetros en el controlador

### 3. **Mejoras en el Controlador**
- ✅ Agregada validación mejorada de datos
- ✅ Mejorado el manejo de errores
- ✅ Agregados logs detallados para debugging

### 4. **Mejoras en el Modelo**
- ✅ Verificación de ID antes de actualizar
- ✅ Manejo de excepciones mejorado
- ✅ Verificación de filas afectadas

### 5. **Mejoras en el Frontend**
- ✅ Indicador de carga en el botón de envío
- ✅ Validación mejorada del formulario
- ✅ Logs detallados en la consola

## 🚀 Pasos para Aplicar la Solución

### Paso 1: Actualizar la Base de Datos
```bash
# Ejecutar el script de actualización
http://localhost/prueba-php/public/update-database.php
```

### Paso 2: Verificar la Estructura
El script mostrará:
- ✅ Columna 'activo' agregada
- ✅ Campo 'rol' actualizado
- ✅ Usuarios existentes actualizados
- 📋 Estructura final de la tabla

### Paso 3: Probar el Modal
1. Ir a: `http://localhost/prueba-php/public/admin/usuarios`
2. Hacer clic en "Editar" en cualquier usuario
3. Modificar algún campo
4. Hacer clic en "Guardar Cambios"
5. Verificar que los cambios se guarden

## 🔍 Verificación

### En la Consola del Navegador (F12)
Deberías ver logs como:
```
✅ Abriendo modal de edición para usuario: 1
✅ Form action set to: /prueba-php/public/admin/editarUsuario/1
✅ Formulario válido, enviando...
```

### En los Logs del Servidor
Deberías ver logs como:
```
editarUsuario called with ID: 1
User::updateUser called with data: Array
✅ Usuario actualizado exitosamente
```

---

# 🔧 Instrucciones para Debuggear

## 🎯 Problema
Cuando editas un usuario y cambias el rol de "Usuario" a "Socio", el cambio no se guarda correctamente.

## 🔍 Pasos para Debuggear

### 1. **Limpiar Cache del Navegador**
- Presiona `Ctrl + F5` (Windows) o `Cmd + Shift + R` (Mac) para forzar recarga
- O ve a `F12 → Network → Disable cache` y recarga la página

### 2. **Probar el Botón de Test JavaScript**
- En la página de "Gestión de Usuarios", haz clic en el botón **"Test JavaScript"** (amarillo)
- Debería aparecer un alert diciendo "Prueba de JavaScript completada"
- Abre la consola del navegador (`F12 → Console`) y verifica que aparezcan los mensajes de prueba

### 3. **Probar la Edición de Usuario**
1. Haz clic en **"EDITAR"** en cualquier usuario
2. Cambia el rol a **"Socio"**
3. Haz clic en **"Guardar Cambios"**
4. **IMPORTANTE**: Debería aparecer un confirm dialog preguntando "¿Estás seguro de que quieres guardar los cambios?"
5. Haz clic en **"Aceptar"**

### 4. **Verificar la Consola del Navegador**
Durante el proceso de edición, abre la consola (`F12 → Console`) y verifica que aparezcan estos mensajes:

```
=== ENVÍO DE FORMULARIO DE EDICIÓN ===
Form action: http://localhost/prueba-php/public/admin/editarUsuario/X
Usuario ID: X
=== DATOS DEL FORMULARIO ===
nombre: [nombre del usuario]
apellidos: [apellidos del usuario]
email: [email del usuario]
rol: socio
activo: 1
user_id: X
csrf_token: [token]
Rol seleccionado en el dropdown: socio
✅ Rol "socio" detectado correctamente
✅ Formulario válido, enviando...
```

### 5. **Si el Problema Persiste**

#### Opción A: Probar en Modo Incógnito
- Abre una ventana de incógnito/privada
- Ve a la página de gestión de usuarios
- Prueba editar un usuario

#### Opción B: Probar en Diferente Navegador
- Usa Chrome, Firefox, Edge, etc.
- Prueba editar un usuario

#### Opción C: Verificar Errores en la Consola
- Abre la consola (`F12 → Console`)
- Busca mensajes de error en rojo
- Si hay errores, anótalos

### 6. **Verificar la Base de Datos**
Si el problema persiste, verifica directamente en la base de datos:

1. Abre phpMyAdmin
2. Ve a la base de datos `mariscales_db`
3. Ve a la tabla `users`
4. Busca el usuario que intentaste editar
5. Verifica si el campo `rol` se actualizó

## 🚨 Posibles Causas

1. **Cache del Navegador**: El navegador está usando una versión antigua del JavaScript
2. **JavaScript Deshabilitado**: El JavaScript no se está ejecutando
3. **Errores de JavaScript**: Hay errores que impiden el funcionamiento
4. **Problema de Red**: La petición no llega al servidor
5. **Problema de Base de Datos**: La actualización no se guarda

## 📞 Información para Reportar

Si el problema persiste, proporciona:

1. **Navegador usado**: Chrome, Firefox, Edge, etc.
2. **Versión del navegador**
3. **Mensajes de la consola**: Copia todos los mensajes que aparecen en `F12 → Console`
4. **Pasos exactos**: Qué botones presionaste y en qué orden
5. **Screenshot**: Si es posible, una imagen del modal abierto

---

# 🖼️ Instrucciones para Colocar el Fondo de Pantalla

## 📁 Paso 1: Ubicación de la Imagen

### **Carpeta de Destino:**
```
C:\xampp\htdocs\prueba-php\public\assets\images\backgrounds\
```

### **Nombre del Archivo:**
```
knight-templar-background.jpg
```

## 📋 Paso 2: Acciones Requeridas

1. **Copia tu imagen** del caballero templario
2. **Pégala en la carpeta:** `public/assets/images/backgrounds/`
3. **Renómbrala como:** `knight-templar-background.jpg`

## 🎯 Paso 3: Verificación

Una vez colocada la imagen, el fondo se aplicará automáticamente a **todas las páginas** del sitio web.

### **Características del Fondo:**
- ✅ **Cubre toda la pantalla** (`background-size: cover`)
- ✅ **Centrado** (`background-position: center`)
- ✅ **Fijo al scroll** (`background-attachment: fixed`)
- ✅ **Sin repetición** (`background-repeat: no-repeat`)
- ✅ **Overlay semitransparente** para mejor legibilidad del texto

## 🎨 Efecto Visual

El fondo tendrá:
- **Imagen del caballero templario** como base
- **Gradiente blanco semitransparente** superpuesto
- **Mejor legibilidad** del contenido
- **Tema templario** perfectamente integrado

## 🚀 Resultado Final

- ✅ Fondo aplicado en todas las páginas
- ✅ Texto legible sobre la imagen
- ✅ Tema templario coherente
- ✅ Efecto visual impactante

## 📝 Nota Importante

Si la imagen no aparece, verifica que:
1. El nombre del archivo sea exactamente: `knight-templar-background.jpg`
2. Esté en la carpeta correcta: `public/assets/images/backgrounds/`
3. El formato sea JPG, PNG o WebP

---

# 🚀 Desarrollo

## 🎯 Rutas Principales

```php
// Páginas públicas
GET /                    -> Pages@index
GET /historia           -> Pages@historia
GET /libro              -> Pages@libro
GET /galeria            -> Pages@galeria

// Autenticación
GET /login              -> Pages@login
GET /registro           -> Pages@registro

// Admin
GET /admin              -> Admin\AdminController@dashboard
GET /admin/events       -> Admin\EventController@index
GET /admin/users        -> Admin\UserController@index
```

## 🔧 Optimizaciones Realizadas

### Archivos Eliminados
- ✅ Archivos de prueba temporales
- ✅ Archivos CSS duplicados (medieval.css, animations.css)
- ✅ Estilos inline redundantes
- ✅ Código JavaScript innecesario

### Archivos Optimizados
- ✅ `public/assets/css/style.css` - Colores templarios implementados
- ✅ `src/views/layouts/main.php` - Layout simplificado y limpio
- ✅ `routes/web.php` - Rutas corregidas y optimizadas
- ✅ `public/index.php` - Sistema de rutas implementado
- ✅ `src/config/config.php` - Configuración mejorada

### Mejoras de Rendimiento
- ✅ CSS optimizado con variables CSS
- ✅ JavaScript simplificado y eficiente
- ✅ Configuración de caché mejorada
- ✅ Compresión GZIP habilitada
- ✅ Rutas optimizadas

## 🎨 Diseño y UX

### Tipografía
- **Títulos**: Cinzel (elegante y medieval)
- **Texto**: Crimson Text (legible y clásica)
- **Iconos**: Bootstrap Icons (consistentes)

### Responsive Design
- **Desktop**: Navegación completa con todos los enlaces
- **Tablet**: Menú adaptado con iconos
- **Mobile**: Menú hamburguesa optimizado

### Animaciones
- **AOS Library**: Animaciones de entrada suaves
- **Hover Effects**: Transiciones elegantes
- **Scroll Effects**: Navbar con efecto de scroll

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+, MySQL
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Arquitectura**: MVC (Model-View-Controller)
- **Router**: Sistema de rutas personalizado
- **Autoloading**: PSR-4 con Composer

## 📝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🆘 Soporte

Para soporte técnico o consultas, contactar con el equipo de desarrollo de la Filá Mariscales.

---

**🎯 Sistema optimizado y listo para producción con tema templario completo!**
