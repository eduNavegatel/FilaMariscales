# ⚔️ Cursor Personalizado de Espada - Filá Mariscales

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

### Versión Detallada (Alternativa)
También se incluye una versión más detallada del cursor con SVG personalizado:

```css
.cursor-espada-detallado * {
    cursor: url('data:image/svg+xml;utf8,<svg...>') 16 16, auto;
}
```

## 🧪 Pruebas

### Archivo de Prueba
Se ha creado un archivo de prueba completo: `test-cursor-espada.html`

### Características del Archivo de Prueba:
- ✅ Prueba de cursor normal
- ✅ Prueba de cursor en enlaces y botones
- ✅ Prueba de cursor de texto
- ✅ Prueba de cursor de espera
- ✅ Selector para cambiar entre tipos de cursor
- ✅ Visualización de colores del tema templario

### Cómo Usar el Archivo de Prueba:
1. Abrir `test-cursor-espada.html` en el navegador
2. Probar los diferentes tipos de cursor
3. Usar los botones para cambiar entre emoji, detallado y normal
4. Verificar que los colores coincidan con el tema templario

## 🎨 Colores del Tema

Los colores utilizados en el cursor coinciden con la paleta templaria:

- **Rojo Templario:** #DC143C
- **Rojo Oscuro:** #8B0000
- **Dorado:** #FFD700
- **Plateado:** #C0C0C0
- **Blanco:** #FFFFFF

## 🔄 Compatibilidad

### Navegadores Soportados:
- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ⚠️ Internet Explorer (limitado)

### Fallbacks:
- Si el cursor personalizado no se carga, se usa el cursor estándar del sistema
- Los campos de texto siempre usan el cursor de texto estándar
- Los elementos deshabilitados usan el cursor de espera estándar

## 🚀 Instalación

El cursor personalizado ya está incluido en el archivo CSS principal:
```
public/assets/css/style.css
```

No se requiere configuración adicional. El cursor se aplica automáticamente a toda la aplicación.

## 🎯 Personalización

### Cambiar Colores:
Para cambiar los colores del cursor, modificar los valores hexadecimales en el CSS:

```css
/* Cursor normal - cambiar #DC143C por el color deseado */
fill="%23DC143C"

/* Cursor en enlaces - cambiar #FFD700 por el color deseado */
fill="%23FFD700"
```

### Cambiar Tamaño:
Para cambiar el tamaño del cursor, modificar los valores de `width`, `height` y las coordenadas:

```css
width="24" height="24"  /* Tamaño del SVG */
12 12                   /* Punto de anclaje */
```

## 📝 Notas Técnicas

### Codificación de Colores:
Los colores en SVG deben estar codificados:
- `#` se convierte en `%23`
- Ejemplo: `#DC143C` → `%23DC143C`

### Punto de Anclaje:
El punto de anclaje (12 12) determina dónde se posiciona el cursor respecto al puntero del mouse.

### Rendimiento:
- El cursor emoji es más ligero y compatible
- El cursor detallado es más visual pero puede ser más pesado
- Se recomienda usar el cursor emoji por defecto

## 🎉 Resultado Final

El cursor personalizado de espada añade un toque temático único a la aplicación web de la Filá Mariscales, manteniendo la funcionalidad y usabilidad estándar mientras refuerza la identidad visual templaria.

---

**Desarrollado para la Filá Mariscales** ⚔️
