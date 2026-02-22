# 🔐 Sistema de Autenticación y Autorización con Laravel Sanctum

## 📋 Índice
1. [Descripción General](#descripción-general)
2. [Instalación y Configuración](#instalación-y-configuración)
3. [Autenticación de Usuarios](#autenticación-de-usuarios)
4. [Roles y Permisos](#roles-y-permisos)
5. [Endpoints de la API](#endpoints-de-la-api)
6. [Ejemplos de Uso](#ejemplos-de-uso)
7. [Usuarios de Prueba](#usuarios-de-prueba)

---

## 📖 Descripción General

Este sistema implementa autenticación completa con Laravel Sanctum y sistema de roles/permisos con Spatie Laravel Permission. 

### Características Principales:
- ✅ **Registro de usuarios** con verificación de email
- ✅ **Login/Logout** con tokens JWT
- ✅ **Sistema de roles** (Admin, User, Editor, Moderator)
- ✅ **Sistema de permisos** granular
- ✅ **Verificación de email** obligatoria
- ✅ **Registro de actividades** de usuarios
- ✅ **Protección de rutas** con middleware

---

## 🚀 Instalación y Configuración

### 1. Requisitos
- PHP 8.2+
- Laravel 12
- SQLite (por defecto) o MySQL

### 2. Instalar Dependencias
```bash
composer install
```

### 3. Configurar Base de Datos
El archivo `.env` ya está configurado con SQLite. Si deseas usar MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

### 4. Ejecutar Migraciones y Seeders
```bash
php artisan migrate:fresh --seed --force
```

Esto creará:
- Todas las tablas necesarias
- Roles: `admin`, `user`, `editor`, `moderator`
- Permisos predefinidos
- Usuarios de prueba

### 5. Configurar Email (Opcional)
Para envío real de emails, configurar en `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_password
MAIL_FROM_ADDRESS="noreply@tuapp.com"
```

Por defecto usa `log` (emails guardados en `storage/logs`).

---

## 🔑 Autenticación de Usuarios

### Registro
**Endpoint:** `POST /api/register`

**Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta:**
```json
{
  "message": "Usuario registrado exitosamente. Por favor, verifica tu email.",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com"
  },
  "token": "1|abcdef..."
}
```

**Nota:** Por defecto se asigna el rol `user`.

### Login
**Endpoint:** `POST /api/login`

**Body:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Respuesta:**
```json
{
  "message": "Inicio de sesión exitoso",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "roles": [{"name": "admin"}]
  },
  "token": "2|ghijkl..."
}
```

**⚠️ IMPORTANTE:** Guardar el `token` para usarlo en todas las peticiones protegidas.

### Logout
**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

### Verificación de Email
**Endpoint:** `GET /api/email/verify/{id}/{hash}`

Este endpoint es visitado automáticamente cuando el usuario hace clic en el enlace del email.

### Reenviar Email de Verificación
**Endpoint:** `POST /api/email/resend`

**Headers:**
```
Authorization: Bearer {token}
```

---

## 👥 Roles y Permisos

### Roles Disponibles

#### 1. **User** (Usuario Regular)
**Permisos:**
- ✅ Ver posts
- ✅ Ver comentarios
- ✅ Crear comentarios
- ✅ Ver categorías

**Restricciones:**
- ❌ NO puede crear posts
- ❌ NO puede eliminar posts de otros
- ❌ NO puede eliminar comentarios de otros
- ❌ NO puede ver perfiles de otros usuarios

#### 2. **Admin** (Administrador)
**Permisos:**
- ✅ **TODOS los permisos del usuario regular**
- ✅ Ver todos los perfiles de usuarios
- ✅ Eliminar posts de cualquier usuario
- ✅ Eliminar comentarios de cualquier usuario
- ✅ Gestionar roles y permisos
- ✅ Ver actividades de usuarios
- ✅ Ver estadísticas del sistema

#### 3. **Editor**
**Permisos:**
- ✅ Todos los permisos de User
- ✅ Crear posts
- ✅ Editar posts
- ✅ Publicar posts
- ✅ Editar comentarios

#### 4. **Moderator** (Moderador)
**Permisos:**
- ✅ Todos los permisos de Editor
- ✅ Eliminar posts
- ✅ Eliminar comentarios
- ✅ Ver usuarios
- ✅ Gestionar categorías

### Lista Completa de Permisos

```php
// Posts
'view posts'
'create posts'
'edit posts'
'delete posts'
'publish posts'

// Comentarios
'view comments'
'create comments'
'edit comments'
'delete comments'

// Usuarios
'view users'
'create users'
'edit users'
'delete users'

// Roles y Permisos
'manage roles'
'manage permissions'

// Categorías
'view categories'
'create categories'
'edit categories'
'delete categories'

// Admin
'access admin panel'
'view user activities'
'view statistics'
```

---

## 🌐 Endpoints de la API

### Rutas Públicas (Sin autenticación)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/register` | Registrar nuevo usuario |
| POST | `/api/login` | Iniciar sesión |
| GET | `/api/email/verify/{id}/{hash}` | Verificar email |
| GET | `/api/posts` | Ver todos los posts |
| GET | `/api/posts/{id}` | Ver post específico |
| GET | `/api/comments` | Ver todos los comentarios |
| GET | `/api/posts/{postId}/comments` | Ver comentarios de un post |

### Rutas Protegidas (Requieren autenticación)

#### Perfil de Usuario
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/me` | Obtener perfil del usuario autenticado |
| POST | `/api/logout` | Cerrar sesión |
| POST | `/api/email/resend` | Reenviar email de verificación |

#### Posts
| Método | Endpoint | Descripción | Permiso |
|--------|----------|-------------|---------|
| POST | `/api/posts` | Crear post | Usuario autenticado |
| PUT | `/api/posts/{id}` | Editar post propio | Propietario |
| DELETE | `/api/posts/{id}` | Eliminar post | Propietario o Admin |
| GET | `/api/my-posts` | Ver mis posts | Usuario autenticado |

#### Comentarios
| Método | Endpoint | Descripción | Permiso |
|--------|----------|-------------|---------|
| POST | `/api/comments` | Crear comentario | `create comments` |
| PUT | `/api/comments/{id}` | Editar comentario | Propietario |
| DELETE | `/api/comments/{id}` | Eliminar comentario | Propietario o Admin |
| GET | `/api/my-comments` | Ver mis comentarios | Usuario autenticado |

### Rutas de Administrador (Solo Admin)

**Prefijo:** `/api/admin`

#### Gestión de Usuarios
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/users` | Listar todos los usuarios |
| GET | `/admin/users/{id}` | Ver usuario específico |
| POST | `/admin/users/{id}/roles` | Asignar rol a usuario |
| DELETE | `/admin/users/{id}/roles` | Remover rol de usuario |
| POST | `/admin/users/{id}/permissions` | Dar permiso a usuario |
| DELETE | `/admin/users/{id}/permissions` | Revocar permiso de usuario |

#### Actividades y Estadísticas
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/activities` | Ver todas las actividades |
| GET | `/admin/users/{id}/activities` | Ver actividades de un usuario |
| GET | `/admin/statistics` | Ver estadísticas del sistema |

---

## 💡 Ejemplos de Uso

### 1. Registro y Login Completo

```bash
# 1. Registrar usuario
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "María García",
    "email": "maria@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# 2. Login (tomar el token de la respuesta)
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "maria@example.com",
    "password": "password123"
  }'

# Respuesta incluye token:
# "token": "3|abcdefghijklmnop..."
```

### 2. Usuario Regular - Ver y Comentar Posts

```bash
# Ver todos los posts (público)
curl http://localhost:8000/api/posts

# Crear comentario (requiere autenticación)
curl -X POST http://localhost:8000/api/comments \
  -H "Authorization: Bearer 3|abcdefghijklmnop..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mi Comentario",
    "content": "Excelente post!",
    "post_id": 1
  }'

# Ver mis comentarios
curl http://localhost:8000/api/my-comments \
  -H "Authorization: Bearer 3|abcdefghijklmnop..."
```

### 3. Admin - Gestión Completa

```bash
# Login como admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'

# Ver todos los usuarios
curl http://localhost:8000/api/admin/users \
  -H "Authorization: Bearer {admin_token}"

# Eliminar post de otro usuario
curl -X DELETE http://localhost:8000/api/posts/5 \
  -H "Authorization: Bearer {admin_token}"

# Eliminar comentario de otro usuario
curl -X DELETE http://localhost:8000/api/comments/10 \
  -H "Authorization: Bearer {admin_token}"

# Ver actividades de un usuario
curl http://localhost:8000/api/admin/users/2/activities \
  -H "Authorization: Bearer {admin_token}"

# Ver estadísticas
curl http://localhost:8000/api/admin/statistics \
  -H "Authorization: Bearer {admin_token}"

# Asignar rol admin a un usuario
curl -X POST http://localhost:8000/api/admin/users/3/roles \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{"role": "admin"}'
```

### 4. Crear Post (Requiere Permiso)

```bash
# Usuario normal NO puede crear posts (recibirá error 403)
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer {user_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mi Primer Post",
    "content": "Contenido del post..."
  }'

# Respuesta:
# {
#   "message": "No tienes permiso para crear posts"
# }

# Admin o Editor SÍ pueden crear posts
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Post del Admin",
    "content": "Contenido importante...",
    "categories": [1, 2]
  }'
```

---

## 👤 Usuarios de Prueba

### Admin
```
Email: admin@example.com
Password: password
Rol: admin
```

**Puede hacer:**
- ✅ Todo lo que puede hacer un usuario regular
- ✅ Ver todos los perfiles (GET /api/admin/users)
- ✅ Eliminar posts de cualquier usuario
- ✅ Eliminar comentarios de cualquier usuario
- ✅ Gestionar roles y permisos
- ✅ Ver actividades y estadísticas

### Usuario Regular
```
Email: test@example.com
Password: password
Rol: user
```

**Puede hacer:**
- ✅ Ver todos los posts
- ✅ Ver comentarios
- ✅ Crear comentarios en posts
- ✅ Editar sus propios comentarios
- ✅ Eliminar sus propios comentarios
- ✅ Ver su perfil

**NO puede hacer:**
- ❌ Crear posts
- ❌ Ver perfiles de otros usuarios
- ❌ Eliminar posts de otros
- ❌ Eliminar comentarios de otros
- ❌ Acceder a rutas de admin

---

## 🔒 Seguridad

### Headers de Autenticación
Todas las rutas protegidas requieren el header:
```
Authorization: Bearer {token}
```

### Validaciones Implementadas
- ✅ Email único
- ✅ Password mínimo 8 caracteres
- ✅ Confirmación de password en registro
- ✅ Verificación de email obligatoria
- ✅ Tokens únicos por sesión
- ✅ Hash bcrypt para passwords

### Middleware Aplicado
- `auth:sanctum` - Verifica token válido
- `log.activity` - Registra actividades del usuario
- `role:admin` - Verifica rol de administrador

---

## 📊 Registro de Actividades

El sistema registra automáticamente:
- Registro de usuarios
- Login/Logout
- Verificación de email
- Creación de posts
- Creación de comentarios
- Edición de posts/comentarios
- Eliminación de posts/comentarios

Ver actividades (solo admin):
```bash
curl http://localhost:8000/api/admin/activities \
  -H "Authorization: Bearer {admin_token}"
```

---

## 🐛 Solución de Problemas

### Error: "Unauthenticated"
- Verificar que el header `Authorization: Bearer {token}` esté presente
- Verificar que el token sea válido (no expirado)

### Error: "No tienes permiso..."
- Verificar que el usuario tenga el rol/permiso necesario
- Usar `GET /api/me` para ver roles y permisos del usuario

### Error: "Email ya está en uso"
- El email debe ser único
- Usar otro email o verificar usuarios existentes

### Error: "This action is unauthorized"
- Solo el propietario puede editar/eliminar su contenido
- Solo admin puede eliminar contenido de otros usuarios

---

## 📝 Notas Adicionales

1. **Tokens de API:** No expiran por defecto. Configurar expiración en `config/sanctum.php` si es necesario.

2. **Verificación de Email:** Por defecto usa mailer `log`. Cambiar a SMTP para producción.

3. **Permisos vs Roles:** 
   - Los **roles** agrupan permisos
   - Los **permisos** son acciones específicas
   - Se puede asignar permisos directamente a usuarios

4. **Base de Datos:** SQLite por defecto. Cambiar a MySQL/PostgreSQL para producción.

---

## 🎯 Resumen de Permisos por Rol

| Acción | User | Editor | Moderator | Admin |
|--------|------|--------|-----------|-------|
| Ver posts | ✅ | ✅ | ✅ | ✅ |
| Crear posts | ❌ | ✅ | ✅ | ✅ |
| Editar posts propios | ❌ | ✅ | ✅ | ✅ |
| Eliminar posts propios | ❌ | ❌ | ✅ | ✅ |
| Eliminar posts de otros | ❌ | ❌ | ❌ | ✅ |
| Ver comentarios | ✅ | ✅ | ✅ | ✅ |
| Crear comentarios | ✅ | ✅ | ✅ | ✅ |
| Editar comentarios propios | ✅ | ✅ | ✅ | ✅ |
| Eliminar comentarios propios | ✅ | ✅ | ✅ | ✅ |
| Eliminar comentarios de otros | ❌ | ❌ | ✅ | ✅ |
| Ver perfiles de usuarios | ❌ | ❌ | ✅ | ✅ |
| Gestionar roles | ❌ | ❌ | ❌ | ✅ |
| Ver actividades | ❌ | ❌ | ✅ | ✅ |
| Ver estadísticas | ❌ | ❌ | ❌ | ✅ |

---

## 🚀 Iniciar Servidor

```bash
cd /workspaces/Final-BackendProyect/final
php artisan serve
```

API disponible en: `http://localhost:8000/api`

---

**¡Sistema completamente configurado y listo para usar!** 🎉
