# 🌐 Sistema de Autenticación Web - Guía de Usuario

## ✅ Cambios Implementados

### 1. **Sistema de Autenticación Completo**
Ya no usamos sesiones manuales. Ahora el sistema usa la autenticación nativa de Laravel.

### 2. **Nuevas Funcionalidades**

#### 🔐 Login y Registro
- **Ruta de Login:** `/login`
- **Ruta de Registro:** `/register`
- **Cierre de sesión:** `/logout`

#### 📋 Navbar Dinámico
El navbar ahora muestra diferentes opciones según el estado de autenticación:

**Usuario NO autenticado:**
- ✅ Botón "Iniciar Sesión"
- ✅ Botón "Registrarse"
- ❌ NO puede ver "Crear Post"
- ❌ NO puede acceder a su cuenta

**Usuario autenticado:**
- ✅ Botón "Crear Post"
- ✅ Dropdown con nombre del usuario
- ✅ Opciones: Mi Cuenta, Mis Posts, Cerrar Sesión

### 3. **Rutas Protegidas**

Ahora estas rutas **requieren autenticación**:
- `/crear-post` - Crear un nuevo post
- `/post/crear` - Guardar post
- `/post/delete/{id}` - Eliminar post (propio o admin)
- `/comments/{id}` - Ver comentarios (requiere login)
- `/comment/store/{id}` - Crear comentario
- `/comment/delete/{id}` - Eliminar comentario (propio o admin)
- `/mi-cuenta` - Ver perfil
- `/mis-posts` - Ver mis posts

**Rutas públicas** (no requieren login):
- `/` - Página de inicio
- `/post` - Ver lista de posts (API)
- `/post/{id}` - Ver post específico (vistas)
- `/login` - Página de login
- `/register` - Página de registro

### 4. **Redirección Automática**

Si intentas acceder a una ruta protegida sin estar autenticado:
- ✅ Serás redirigido automáticamente a `/login`
- ✅ Después de iniciar sesión, volverás a la página que intentabas visitar

### 5. **Permisos de Admin**

Los administradores pueden:
- ✅ Eliminar posts de cualquier usuario
- ✅ Eliminar comentarios de cualquier usuario
- ✅ Todas las funcionalidades de usuarios normales

Usuarios normales:
- ✅ Solo pueden eliminar sus propios posts
- ✅ Solo pueden eliminar sus propios comentarios

---

## 🚀 Cómo Usar el Sistema

### Paso 1: Registrarse

1. Ve a la página de inicio: `http://localhost:8000`
2. Haz clic en **"Registrarse"** en el navbar
3. Completa el formulario:
   - Nombre completo
   - Email (debe ser único)
   - Contraseña (mínimo 8 caracteres)
   - Confirmar contraseña
4. Haz clic en **"Crear Cuenta"**
5. Serás redirigido automáticamente al inicio, ya autenticado

### Paso 2: Iniciar Sesión

1. Ve a `http://localhost:8000/login`
2. Ingresa tu email y contraseña
3. Haz clic en **"Iniciar Sesión"**
4. Serás redirigido al inicio

**Usuarios de prueba:**
```
Admin:
Email: admin@example.com
Password: password

Usuario Regular:
Email: test@example.com
Password: password
```

### Paso 3: Crear un Post

1. Asegúrate de estar autenticado
2. Haz clic en **"Crear Post"** en el navbar
3. Completa el formulario
4. Haz clic en "Publicar"

**Si no estás autenticado:**
- El sistema te redirigirá automáticamente a `/login`
- Después de iniciar sesión, volverás a la página de crear post

### Paso 4: Ver y Comentar Posts

1. En la página de inicio, verás todos los posts
2. Haz clic en un post para ver los detalles
3. **Para comentar, debes estar autenticado**
4. Si no estás autenticado y intentas acceder a los comentarios, serás redirigido al login

### Paso 5: Eliminar Posts/Comentarios

**Como usuario normal:**
- Solo puedes eliminar tus propios posts/comentarios
- Si intentas eliminar el contenido de otro usuario, recibirás error 403

**Como administrador:**
- Puedes eliminar cualquier post o comentario
- Estos permisos se verifican automáticamente

### Paso 6: Cerrar Sesión

1. Haz clic en tu nombre en el navbar
2. Selecciona **"Cerrar Sesión"**
3. Serás redirigido a la página de inicio
4. El navbar mostrará las opciones de Login/Registro

---

## 🔍 Diferencias con el Sistema Anterior

| Característica | Sistema Anterior | Sistema Nuevo |
|----------------|------------------|---------------|
| Autenticación | Sesiones manuales (`session('user_id')`) | Laravel Auth nativo |
| Login | No había | ✅ Formulario completo |
| Registro | No había | ✅ Formulario completo |
| Navbar | Siempre mostraba usuario | ✅ Dinámico según auth |
| Crear Post | Siempre disponible | ⚠️ Requiere login |
| Comentarios | Siempre disponible | ⚠️ Requiere login |
| Eliminar | Solo verificaba user_id | ✅ Verifica roles también |
| Redirección | No había | ✅ Auto-redirige a login |

---

## ⚠️ Importante: Protección de Rutas

### Rutas que REQUIEREN autenticación:

```php
// Estas rutas están protegidas por middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/crear-post', ...);
    Route::post('/post/crear', ...);
    Route::post('/post/delete/{id}', ...);
    Route::post('/comment/store/{id}', ...);
    Route::post('/comment/delete/{id}', ...);
    Route::get('/mi-cuenta', ...);
    Route::get('/mis-posts', ...);
});
```

### Si NO estás autenticado:
- ❌ No puedes crear posts
- ❌ No puedes ver detalles de posts (con comentarios)
- ❌ No puedes crear comentarios
- ❌ No puedes acceder a tu cuenta
- ✅ Serás redirigido a `/login` automáticamente

---

## 🎨 Mejoras de UI

### Navbar
- **Sin autenticar:** Muestra botones de Login y Registro
- **Con autenticación:** Muestra nombre del usuario y dropdown con opciones

### Formularios de Login/Registro
- ✨ Diseño moderno con gradiente purple/blue
- ✨ Validación de errores en tiempo real
- ✨ Mensajes de éxito/error
- ✨ Animaciones suaves
- ✨ Responsive design

### Mensajes Flash
El sistema muestra mensajes de:
- ✅ Registro exitoso
- ✅ Login exitoso
- ✅ Logout exitoso
- ❌ Credenciales incorrectas
- ❌ Errores de validación

---

## 🛠️ Solución de Problemas

### Error: "The GET method is not supported for route api/login"
**Causa:** Intentaste acceder a `/api/login` desde el navegador
**Solución:** Usa `/login` (sin `/api`) para la interfaz web

### Error: "Unauthenticated"
**Causa:** Intentaste acceder a una ruta protegida sin estar autenticado
**Solución:** El sistema te redirigirá automáticamente a `/login`

### No puedo crear posts
**Causa:** No estás autenticado
**Solución:** 
1. Inicia sesión en `/login`
2. O regístrate en `/register`

### No veo el botón "Crear Post"
**Causa:** No estás autenticado
**Solución:** En su lugar verás botones de "Iniciar Sesión" y "Registrarse"

### El logout no funciona
**Causa:** Estás usando la ruta vieja con sesiones manuales
**Solución:** El nuevo sistema usa `/logout` que funciona correctamente

---

## 📝 Flujo de Usuario Típico

### Usuario Nuevo:
1. **Visita la página** → Ve posts pero no puede interactuar
2. **Hace clic en "Registrarse"** → Completa formulario
3. **Se registra exitosamente** → Redirigido al inicio (ya autenticado)
4. **Ahora puede:**
   - Crear posts
   - Comentar posts
   - Ver su perfil
   - Gestionar su contenido

### Usuario Existente:
1. **Visita la página** → Ve posts
2. **Hace clic en "Iniciar Sesión"** → Ingresa credenciales
3. **Login exitoso** → Redirigido al inicio
4. **Puede realizar todas las acciones**

### Visitante (sin cuenta):
1. **Visita la página** → Ve posts
2. **Intenta crear post** → Redirigido a login
3. **Intenta comentar** → Redirigido a login
4. **Solo puede ver contenido público**

---

## 🔐 Seguridad Implementada

✅ **Passwords hasheados** con bcrypt
✅ **CSRF Protection** en todos los formularios
✅ **Validación de inputs** en servidor
✅ **Protección de rutas** con middleware
✅ **Verificación de permisos** antes de eliminar
✅ **Sesiones seguras** de Laravel
✅ **Regeneración de tokens** después de login/logout

---

## 🎯 Próximos Pasos Recomendados

1. **Verificación de Email:** Implementar envío de email real para verificar cuentas
2. **Recuperación de Contraseña:** Agregar funcionalidad "Olvidé mi contraseña"
3. **OAuth:** Permitir login con Google, Facebook, etc.
4. **2FA:** Autenticación de dos factores para más seguridad
5. **Rate Limiting:** Limitar intentos de login para prevenir ataques

---

## ✨ Resumen de Cambios

### ✅ Agregado:
- Sistema completo de autenticación web
- Vistas de login y registro
- Web AuthController
- Protección de rutas con middleware
- Navbar dinámico según estado de autenticación
- Redirección automática a login
- Verificación de permisos de admin para eliminar

### 🔄 Modificado:
- Rutas web ahora usan grupos protegidos
- PostController usa auth() en lugar de sesiones
- CommentController usa auth() en lugar de sesiones
- Navbar muestra opciones según autenticación

### ❌ Eliminado:
- Sistema manual de sesiones con `session('user_id')`
- Usuarios por defecto cuando no hay autenticación

---

**¡El sistema está listo para usar!** 🎉

Accede a `http://localhost:8000` y comienza a explorar las nuevas funcionalidades.
