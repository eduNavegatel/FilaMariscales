# 🔑 Instrucciones para Activar Contraseñas Temporales

## 📋 Pasos a Seguir

### 1. **Actualizar la Base de Datos**
Ejecuta el siguiente archivo en tu navegador para agregar los campos necesarios:
```
http://localhost/prueba-php/public/update-database.php
```

### 2. **Verificar que Funciona**
1. Ve al panel de administración: `http://localhost/prueba-php/public/admin/usuarios`
2. Haz clic en "Nueva Contraseña" junto a cualquier usuario
3. Confirma en el modal
4. Verás la nueva contraseña generada en pantalla
5. La contraseña aparecerá en la columna "Contraseña Temporal" de la lista

## ✨ **Nuevas Funcionalidades**

### **Botón "Nueva Contraseña"**
- Genera automáticamente una contraseña segura de 12 caracteres
- La muestra al admin para compartir con el usuario
- Se guarda en la base de datos para referencia

### **Columna "Contraseña Temporal"**
- Muestra la contraseña temporal actual de cada usuario
- Incluye la fecha y hora de creación
- Solo aparece si hay una contraseña temporal activa

### **Botón "Limpiar"**
- Elimina la contraseña temporal cuando ya no se necesite
- Solo aparece si hay una contraseña temporal activa

## 🔒 **Características de Seguridad**

- **Contraseñas seguras**: 6 caracteres con letras, números y símbolos
- **Sin caracteres confusos**: No usa 0, O, l, I, 1
- **Almacenamiento seguro**: Las contraseñas se hashean en la base de datos
- **Temporales**: Se pueden limpiar cuando ya no se necesiten

## 🎯 **Cómo Usar**

1. **Usuario pide su contraseña** → Haz clic en "Nueva Contraseña"
2. **Se genera automáticamente** → Copia la contraseña que aparece
3. **Comparte con el usuario** → Dásela por teléfono, email, etc.
4. **Usuario cambia la contraseña** → En su perfil (/profile) o primer login
5. **Contraseña se vuelve real** → Al cambiar, se elimina automáticamente la temporal
6. **Limpia la temporal** → Haz clic en "Limpiar" cuando ya no la necesites

## ⚠️ **Importante**

- Las contraseñas temporales se muestran en texto plano solo al admin
- Se almacenan hasheadas en la base de datos
- Se pueden limpiar en cualquier momento
- Son solo para uso temporal

¡Listo! Ahora puedes generar y ver contraseñas temporales para todos los usuarios.
