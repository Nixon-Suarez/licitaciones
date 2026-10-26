# Tutorial: primer arranque local

Este tutorial te lleva de un directorio vacío de trabajo a una sesión iniciada en el navegador. Al final vas a tener la base importada, la API respondiendo y el frontend Vue corriendo.

Necesitás XAMPP (Apache + MySQL), Composer, Node.js `^20.19.0` o `>=22.12.0`, y el código del módulo en una ruta equivalente a:

`C:\xampp\htdocs\PHP\index.php\licitaciones`

Si tu ruta de Apache es distinta, al final vas a ajustar dos URLs. No hace falta saber Vue ni Laravel para completar esta lección.

Para instalarlo con **Laragon** (sin XAMPP), usá el how-to [Instalar en Laragon](../how-to/instalar-en-laragon.md).

## 1. Comprobar PHP y Apache

1. Abrí el panel de XAMPP y arrancá **Apache** y **MySQL**.
2. En una terminal:

```bash
php -v
php -m
```

Confirmá que aparecen las extensiones `pdo_mysql`, `mbstring`, `fileinfo`, `zip`, `gd` y `xml`. Si `zip` o `gd` no figuran, habilitá `extension=zip` y `extension=gd` en `php.ini` y reiniciá Apache. Sin ellas no vas a poder generar ni leer Excel.

## 2. Crear e importar la base

En phpMyAdmin o en el cliente MySQL:

```sql
CREATE DATABASE licitaciones CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Importá el archivo `BD/licitaciones.sql` sobre esa base. El script crea las tablas `usuario`, `api_tokens`, `actividades`, `ofertas` y `ofertas_documentos`.

## 3. Instalar la API

```bash
cd api_licitaciones
composer install
```

Abrí `api_licitaciones/config/database.php` y dejá host, base, usuario y clave alineados con tu MySQL. El valor por defecto es `localhost` / `licitaciones` / `root` / clave vacía.

Asegurate de que exista `api_licitaciones/public/documentos_ofertas/` y que Apache pueda escribir ahí.

En el navegador abrí:

`http://localhost/php/index.php/licitaciones/api_licitaciones/`

Deberías ver un JSON con el mensaje `API Licitaciones`. Si ves 404, Apache no está aplicando el `.htaccess` (`mod_rewrite`) o la ruta base del proyecto no coincide con la del enrutador.

## 4. Instalar y levantar el frontend

```bash
cd app_licitaciones_vue
npm install
npm run dev
```

Vite suele servir en `http://localhost:5173`.

La API que consume el front está en `app_licitaciones_vue/src/stores/authStore.js`:

```js
baseUrl: 'http://localhost/php/index.php/licitaciones/api_licitaciones/api/'
```

Si tu carpeta no es `/php/index.php/licitaciones/`, cambiá también `$basePath` en `api_licitaciones/index.php` para que coincida con `REQUEST_URI`.

## 5. Registrar e iniciar sesión

1. Abrí `http://localhost:5173`.
2. Pulsá **Registrarse**.
3. Completá usuario (4–20 caracteres alfanuméricos), nombre, apellido y clave (7–100 caracteres: letras, números, `$`, `@`, `.`, `-`).
4. Enviá el formulario. El sistema crea el usuario con **perfil 3**.
5. Volvé al login e ingresá con ese usuario y clave.

Si el login es correcto, el navegador guarda `userData` en `localStorage` y te lleva a `/inicio`. Ahí ves las tarjetas **LICITACIONES** y **ACTIVIDADES**.

## Qué lograste

Tenés un usuario nuevo, un token de 24 horas y el panel principal. Seguí con [Gestionar ofertas](../how-to/gestionar-ofertas.md) o [Gestionar actividades](../how-to/gestionar-actividades.md).

Si la API no responde o el login falla, revisá las URLs en [Arquitectura](../explanation/arquitectura.md).
