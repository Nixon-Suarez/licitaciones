# 📋 Sistema de Gestión de Licitaciones

Un aplicativo web desarrollado en PHP para gestionar ofertas y licitaciones de manera eficiente, con soporte para documentos adjuntos, búsqueda avanzada y control de usuarios.

---

## 🎯 Características

✅ **Gestión de Ofertas**

- Crear, editar y visualizar ofertas
- Presupuesto, moneda, período de ejecución
- Estado de ofertas (activo, creación, etc.)
- Consecutivo automático por año

✅ **Documentos Adjuntos**

- Subir archivos PDF y ZIP
- Máximo 10MB por archivo
- Eliminación de documentos
- Validación de tipos MIME

✅ **Actividades**

- Catálogo de actividades
- Filtrado por segmento y producto
- Relación con ofertas

✅ **Búsqueda y Filtros**

- Búsqueda por descripción y consecutivo en ofertas
- Filtrado por segmento y producto en actividades
- Paginación configurable

✅ **Autenticación**

- Sistema de login/logout
- Protección de sesiones
- Control de acceso por vista

✅ **Interfaz**

- Bootstrap 5
- Responsive design
- SweetAlert2 para notificaciones
- Iconos Bootstrap Icons

---

## 🏗️ Estructura del Proyecto

```
licitaciones/
├── app/
│   ├── ajax/
│   │   └── FunctionAjax.php          # Procesamiento de peticiones AJAX
│   ├── controllers/
│   │   ├── actividadesController.php # Gestión de actividades
│   │   ├── loginController.php       # Autenticación
│   │   ├── ofertaController.php      # Gestión de ofertas
│   │   ├── ofertaDocumentController.php # Gestión de documentos
│   │   ├── searchController.php      # Búsqueda y filtros
│   │   ├── userController.php        # Gestión de usuarios
│   │   └── viewsController.php       # Enrutamiento de vistas
│   ├── models/
│   │   ├── mainModel.php             # Modelo base
│   │   ├── viewsModel.php            # Modelo de vistas
│   │   └── eloquent/
│   │       ├── Actividad.php
│   │       ├── CategoriaGasto.php
│   │       ├── CategoriaIngreso.php
│   │       ├── Oferta.php
│   │       ├── OfertaDocumento.php
│   │       └── Usuario.php
│   └── views/
│       ├── content/                  # Vistas principales
│       ├── css/                      # Estilos
│       ├── docs/uploads/ofertas/     # Almacenamiento de archivos
│       ├── img/                      # Imágenes
│       ├── inc/                      # Vistas incluidas
│       └── js/                       # Scripts
├── config/
│   ├── app.php                       # Configuración de app
│   ├── database.php                  # Configuración de BD
│   └── server.php
├── BD/
│   └── script.sql                    # Script de base de datos
├── vendor/                           # Dependencias Composer
├── autoload.php                      # Cargador automático
├── index.php                         # Punto de entrada
└── README.md                         # Este archivo
```

---

## ⚙️ Requisitos

- **PHP** 7.4 o superior
- **MySQL/MariaDB** 5.7 o superior
- **Composer** para gestionar dependencias
- **Apache** con mod_rewrite habilitado
- **XAMPP** (recomendado para desarrollo local)

---

## 📦 Dependencias

El proyecto utiliza Composer con:

- **Illuminate/Database** - ORM Eloquent
- **Illuminate/Support** - Utilidades de Laravel
- **Nesbot/Carbon** - Manipulación de fechas
- **PSR-4 Autoloading** - Cargador automático de clases

Ver `composer.json` para la lista completa.

---

## �️ Herramientas y Tecnologías Utilizadas

### Backend

- **PHP 7.4+** - Lenguaje de programación servidor
- **Laravel Eloquent** - ORM para interacción con BD
- **Illuminate/Support** - Utilidades y helpers
- **Composer** - Gestor de dependencias PHP

### Frontend

- **Bootstrap 5** - Framework CSS responsive
- **Bootstrap Icons** - Iconografía
- **Axios** - Cliente HTTP para peticiones AJAX
- **JavaScript Vanilla** - Scripts interactivos
- **SweetAlert2** - Notificaciones elegantes
- **jQuery** (opcional) - Manipulación del DOM

### Base de Datos

- **MySQL/MariaDB** - Sistema de gestión de BD relacional
- **Eloquent ORM** - Mapeo objeto-relacional

### Herramientas de Desarrollo

- **XAMPP** - Stack local (Apache, MySQL, PHP)
- **Composer** - Gestor de paquetes PHP
- **Git** - Control de versiones
- **VS Code** - Editor de código recomendado

### Dependencias Principales

````json
{
  "illuminate/database": "^9.0",
  "illuminate/support": "^9.0",
  "nesbot/carbon": "^2.0",
  "brick/math": "^0.9",
  "doctrine/inflector": "^2.0",
  "symfony/translation": "^6.0"
}
---

## �🚀 Instalación

### 1. Clonar o descargar el proyecto

```bash
cd C:\xampp\htdocs\PHP\index.php
git clone <repositorio> licitaciones
# o descargar el ZIP
````

### 2. Instalar dependencias

```bash
cd licitaciones
composer install
```

### 3. Configurar la base de datos

**En `config/database.php`:**

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'licitaciones_db',
    'username' => 'root',
    'password' => '', // Sin contraseña para XAMPP
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
],
```

### 4. Crear base de datos

```bash
# Ejecutar script SQL
mysql -u root < BD/script.sql
```

O manualmente:

```sql
CREATE DATABASE licitaciones_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Configurar `.htaccess`

Crear `.htaccess` en la raíz:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.php [L]
</IfModule>
```

### 6. Iniciar el servidor

```bash
# Con XAMPP
# 1. Abrir XAMPP Control Panel
# 2. Iniciar Apache y MySQL
# 3. Acceder a http://localhost/php/index.php/licitaciones/

# O con PHP built-in
php -S localhost:8000
```

---

## 🔐 Configuración de Seguridad

### Permisos de carpetas

```bash
# Dar permisos de escritura a carpeta de uploads
chmod -R 777 app/views/docs/uploads/

# O en Windows (XAMPP)
# Clic derecho > Propiedades > Seguridad > Editar permisos
```

### Variables de entorno

Crear archivo `config/app.php` en la raíz:

```
APP_URL=http://localhost/php/index.php/licitaciones/
DB_HOST=localhost
DB_DATABASE=licitaciones_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📖 Uso

### Login

1. Acceder a `http://localhost/php/index.php/licitaciones/`
2. Ingresar credenciales de usuario
3. Se requiere registro previo

### Gestionar Ofertas

1. **Crear**: Ir a "Ofertas" → "Nueva oferta"
2. **Editar**: Seleccionar oferta → "Editar"
3. **Ver detalles**: Hacer clic en la oferta
4. **Adjuntar documentos**: En pestaña "Documentos"

### Gestionar Actividades

1. Ir a "Actividades"
2. Buscar por segmento o producto
3. Ver lista paginada

### Búsqueda

- **Ofertas**: Por descripción o consecutivo
- **Actividades**: Por segmento o producto

---

## 🗂️ Modelos (Eloquent)

### Oferta

```php
$oferta = Oferta::find($id);
$oferta->presupuesto;
$oferta->actividad; // Relación
$oferta->documentos; // Relación
```

### OfertaDocumento

```php
$doc = OfertaDocumento::where('licitacion_id', $oferta_id)->get();
$doc->archivo; // Nombre del archivo
$doc->ruta_archivo; // Ruta completa
```

### Actividad

```php
$actividad = Actividad::find($id);
$actividad->producto;
$actividad->segmento;
```

---

## 🔄 Flujo de Peticiones AJAX

1. **Formulario**: Clase `FormularioAjax`
2. **AJAX**: `app/js/ajax.js` → `app/ajax/FunctionAjax.php`
3. **Controller**: Procesa la lógica
4. **Respuesta**: JSON con estructura:

```json
{
  "tipo": "simple|redireccionar|recargar",
  "titulo": "Título del mensaje",
  "texto": "Texto del mensaje",
  "icono": "success|error|warning|info",
  "url": "URL destino (si aplica)"
}
```

---

## 🎨 Personalización

### Cambiar APP_URL

En `config/app.php`:

```php
define("APP_URL", "http://localhost/php/index.php/licitaciones/");
```

### Cambiar logo/marca

- Logo: `app/views/inc/head.php`
- Nombre empresa: `autoload.php` → `COMPANY`

### Agregar nuevas vistas

1. Crear `app/views/content/nueva_view.php`
2. Registrar en `viewsController.php`
3. Crear controlador correspondiente

---

## 🐛 Solución de problemas

### Error: "Class not found"

- Verificar que los nombres de clase usen mayúscula inicial (PSR-4)
- Ejecutar `composer dumpautoload`

### Error: "Database connection failed"

- Verificar credenciales en `config/database.php`
- Asegurar que MySQL esté corriendo
- Verificar que la BD existe

### Error al subir archivos

- Verificar permisos: `chmod 777 app/views/docs/uploads/`
- Verificar tamaño máximo: máximo 10MB
- Tipos permitidos: `.pdf`, `.zip`

### Sessión expirada

- Limpiar cookies del navegador
- Verificar `session_start.php`
- Revisar timeout en `php.ini`

---

## 📝 Notas de Desarrollo

- **Validación**: Se realiza tanto en cliente (HTML5) como en servidor (regex)
- **Sanitización**: Usar `limpiarCadena()` de `mainModel`
- **Paginación**: Configurable en cada controlador
- **Timestamps**: `creado_en` y `actualizado_en` automáticos
- **Consecutivo**: Formato `PO-NNNN-YY` para ofertas

---

## 📄 Licencia

Proyecto privado. Todos los derechos reservados.

---

**Última actualización**: Enero 2026  
**Versión**: 1.0.0
