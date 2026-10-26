# How-to: instalar el módulo en Laragon

Usá esta guía cuando querés correr Licitaciones con **Laragon** (Apache + MySQL + PHP) en lugar de XAMPP. El código es el mismo; cambian la carpeta pública, el host (`.test`) y dos URLs hardcodeadas.

Si XAMPP sigue prendido, apagá Apache y MySQL ahí antes de arrancar Laragon: ambos pelean por los puertos **80** y **3306**.

## Qué necesitás

- Laragon (Full) con Apache y MySQL/MariaDB
- PHP **8.2 o superior** (el selector de Laragon: menú **PHP → version**)
- Extensiones: `pdo_mysql`, `fileinfo`, `zip`, `gd`, `mbstring`, `xml`
- Composer (viene con Laragon)
- Node.js `^20.19.0` o `>=22.12.0` (el de Laragon o el del sistema)

Esta receta asume la instalación típica en `C:\laragon` y el document root `C:\laragon\www`.

## 1. Ubicar el proyecto

Copiá o cloná el repositorio **dentro de `www`**, como carpeta propia (no hace falta el árbol `PHP\index.php\` de XAMPP):

```text
C:\laragon\www\licitaciones\
├── api_licitaciones\
├── app_licitaciones_vue\
└── BD\
```

Con **Auto Virtual Hosts** activo (menú **Apache → sites-enabled**, por defecto encendido), Laragon publica:

`http://licitaciones.test`

Si el nombre de la carpeta es otro, el host será `http://<nombre-carpeta>.test`.

Abrí **Laragon → Start All**.

## 2. PHP y extensiones

1. Menú **PHP → version** y elegí 8.2, 8.3 u 8.4.
2. Menú **PHP → php.ini** y descomentá (o agregá):

```ini
extension=zip
extension=gd
extension=pdo_mysql
extension=mbstring
extension=fileinfo
extension=xml
```

3. **Stop All** y **Start All** para recargar PHP.

En la terminal de Laragon (menú **Terminal**):

```bash
php -v
php -m
```

Confirmá que figuran `zip` y `gd`. Esa es la misma PHP que usa Apache en Laragon, no la de XAMPP.

## 3. Base de datos

Laragon suele dejar MySQL en `127.0.0.1:3306`, usuario `root`, **sin clave**.

1. Abrí **HeidiSQL** desde el menú de Laragon (o cualquier cliente).
2. Creá la base e importá el dump:

```sql
CREATE DATABASE licitaciones CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Importá `BD/licitaciones.sql` sobre esa base.

3. En `api_licitaciones/config/database.php` dejá:

```php
'host'      => 'localhost',
'database'  => 'licitaciones',
'username'  => 'root',
'password'  => '',
```

Si tu MySQL de Laragon tiene clave, ponela ahí.

## 4. Ajustar las URLs (obligatorio)

El enrutador recorta un prefijo fijo de `REQUEST_URI`. En XAMPP ese prefijo es `/php/index.php/licitaciones/api_licitaciones`. En Laragon, con el layout de arriba, **no**.

En `api_licitaciones/index.php` cambiá `$basePath` a:

```php
$basePath = '/api_licitaciones';
```

En `app_licitaciones_vue/src/stores/authStore.js`:

```js
baseUrl: 'http://licitaciones.test/api_licitaciones/api/'
```

Opcional, en `api_licitaciones/config/app.php`:

```php
const APP_URL = 'http://licitaciones.test/';
```

Si preferís no usar `.test` y entrar por localhost, el prefijo incluye el nombre de carpeta:

- `$basePath = '/licitaciones/api_licitaciones';`
- `baseUrl: 'http://localhost/licitaciones/api_licitaciones/api/'`

`mod_rewrite` ya viene activo en el Apache de Laragon; el `.htaccess` de `api_licitaciones` se aplica sin pasos extra.

## 5. Instalar dependencias y carpeta de uploads

Terminal de Laragon:

```bash
cd C:\laragon\www\licitaciones\api_licitaciones
composer install
```

Creá `api_licitaciones/public/documentos_ofertas` si no existe.

```bash
cd C:\laragon\www\licitaciones\app_licitaciones_vue
npm install
npm run dev
```

Vite sigue en `http://localhost:5173` (no usa el vhost `.test`). El navegador habla con la API en `licitaciones.test`.

## 6. Comprobar

1. `http://licitaciones.test/api_licitaciones/` debe devolver JSON `API Licitaciones`.
2. `http://localhost:5173` → registrate e iniciá sesión.

Si el JSON es 404 `Ruta no encontrada`, `$basePath` no coincide con la URI real. En el navegador, la barra después del host tiene que empezar exactamente por ese prefijo (`/api_licitaciones` o `/licitaciones/api_licitaciones`).

## Problemas frecuentes en Laragon

| Síntoma | Qué hacer |
| --- | --- |
| Puerto 80 ocupado | Cerrar XAMPP/IIS/Skype; o en Laragon cambiar el puerto de Apache |
| `.test` no resuelve | Auto Virtual Hosts + que el nombre de carpeta coincida; a veces hace falta **Reload** de Apache |
| Excel no genera/lee | `zip` y `gd` en el `php.ini` de **esa** versión de PHP de Laragon, no el de XAMPP |
| Composer no encontrado | Usá **Menu → Terminal** de Laragon (carga el PATH) |
| Login no pega a la API | `authStore.baseUrl` con el host `.test` o `localhost` que realmente sirve Apache |
| CORS en el navegador | La API ya envía `Access-Control-Allow-Origin: *`; si falla, es casi siempre URL mal armada |

El flujo de uso (ofertas, actividades, perfiles) es el mismo que en XAMPP. Seguí con [Gestionar ofertas](gestionar-ofertas.md) o el [tutorial de primer arranque](../tutorial/primer-arranque.md) a partir del registro, ignorando las rutas `C:\xampp\...`.
