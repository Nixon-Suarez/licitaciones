# Módulo de Licitaciones (Prueba FullStack PHP + Vue)

## Requisitos
- PHP 7.0+
- MySQL/MariaDB
- Node.js + npm
- Composer
- XAMPP/WAMP o servidor Apache con mod_rewrite

## Estructura
- `api_licitaciones/` - Backend PHP MVC + Eloquent (API REST)
- `app_licitaciones_vue/` - Frontend Vue 2.6 + Vite + Bootstrap 5
- `BD/licitaciones.sql` - Script de base de datos

## Instalación

### 1) Base de datos
1. Crear base de datos `licitaciones` en MySQL/MariaDB
2. Importar `BD/licitaciones.sql`

### 2) Backend (API)
```bash
cd api_licitaciones
composer install
```
Configurar conexión en `config/database.php` (host, database, username, password).

Asegurar que la carpeta `api_licitaciones/public/documentos_ofertas/` exista y tenga permisos de escritura.

### 3) Frontend (Vue)
```bash
cd app_licitaciones_vue
npm install
npm run dev
```

## URLs
- API: `http://localhost/php/index.php/licitaciones/api_licitaciones/`
- Frontend: según configuración de Vite (ej. `http://localhost:5173`)

## Funcionalidades
- Crear/Editar/Ver/Listar ofertas
- Filtros por consecutivo, objeto o descripción
- Paginación
- Carga de documentos (PDF/ZIP) en edición
- Validación de al menos 1 documento al editar
- Generación automática de consecutivo `PO-XXX-YY`
- Exportación a Excel desde listado
- Autenticación con token
