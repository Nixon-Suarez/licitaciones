# Módulo de Licitaciones (FullStack PHP + Vue)

Aplicación para la gestión de licitaciones, ofertas, documentos adjuntos y actividades (catálogo UNSPSC), con autenticación por token, control de acceso por perfiles, filtros, paginación y exportación a Excel.

Documentación completa (Diátaxis: tutorial, how-to, referencia y explicación): **[docs/README.md](docs/README.md)**.

---

## Requisitos

- PHP **8.2+** con extensiones `pdo_mysql`, `fileinfo`, `zip`, `gd`, `mbstring` y `xml`
- MySQL/MariaDB
- Composer
- Node.js `^20.19.0` o `>=22.12.0` + npm
- Apache con `mod_rewrite` (XAMPP, WAMP o [Laragon](docs/how-to/instalar-en-laragon.md))

> Las extensiones `zip` y `gd` son necesarias para generar y leer archivos `.xlsx` con PhpSpreadsheet.

---

## Estructura

```text
licitaciones/
├── api_licitaciones/          # Backend PHP MVC + Eloquent (API REST)
│   ├── app/
│   │   ├── Http/Controllers/  # Actividades, Ofertas, Documentos, Usuarios, Login
│   │   ├── Middleware/        # Validación de token y perfiles
│   │   └── Models/            # Eloquent: Oferta, Actividad, Usuario, Token...
│   ├── config/                # app.php, database.php, server.php
│   ├── public/                # Archivos subidos (documentos_ofertas)
│   ├── index.php              # Enrutador de la API
│   └── composer.json
├── app_licitaciones_vue/      # Frontend Vue 3 + Vite + Pinia
│   └── src/
│       ├── views/             # Login, Inicio, Ofertas, Actividades, Usuario
│       ├── components/        # Tabs, modales, cargue masivo
│       ├── stores/            # authStore, alertStore
│       └── router/
├── BD/licitaciones.sql        # Script de base de datos y datos de ejemplo
└── README.md
```

---

## Instalación

### 1) Base de datos

1. Crear la base de datos `licitaciones` en MySQL/MariaDB.
2. Importar `BD/licitaciones.sql`.

```sql
CREATE DATABASE licitaciones CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

El script crea las tablas `usuario`, `api_tokens`, `actividades`, `ofertas` y `ofertas_documentos`, con sus llaves foráneas y datos de ejemplo.

### 2) Backend (API)

```bash
cd api_licitaciones
composer install
```

Configurar la conexión en `config/database.php` (host, database, username, password). Por defecto apunta a `localhost` / `licitaciones` / `root` sin clave.

Verificar que exista la carpeta `api_licitaciones/public/documentos_ofertas/` y que tenga permisos de escritura.

### 3) Frontend (Vue)

```bash
cd app_licitaciones_vue
npm install
npm run dev
```

La URL base de la API está centralizada en `src/stores/authStore.js`:

```js
baseUrl: 'http://localhost/php/index.php/licitaciones/api_licitaciones/api/'
```

Si el proyecto se monta en otra ruta o host, ajustar ese valor. En Laragon el host típico es `http://licitaciones.test/`; los pasos están en [docs/how-to/instalar-en-laragon.md](docs/how-to/instalar-en-laragon.md).

### 4) URLs de referencia

- API: `http://localhost/php/index.php/licitaciones/api_licitaciones/`
- Frontend: según Vite (por defecto `http://localhost:5173`)

---

## Novedades de esta versión

- **Actividades**: creación, edición, eliminación y listado con filtros y paginación.
- **Cargue masivo de actividades** desde Excel `.xlsx`, con descarga de plantilla, validación fila por fila y transacción todo-o-nada.
- **Control de acceso por perfiles** (1, 2 y 3) sobre ofertas, actividades, documentos y usuarios.
- **Edición de usuario**: cada usuario puede actualizar su propio perfil; el administrador puede editar a cualquiera. La clave es opcional al actualizar.
- **Eliminación de ofertas** vía API, borrando en cascada sus documentos y archivos físicos.
- **Eliminación de documentos** con validación de permisos y borrado del archivo del servidor.
- **Reporte Excel de ofertas** respetando los filtros activos.
- **Migración del frontend a Vue 3**, con Pinia, Vue Router, Bootstrap 5, Axios y SweetAlert2.

---

## Funcionalidades

### Autenticación y usuarios

- Login/logout con token Bearer almacenado en `localStorage`.
- Token con vigencia de **24 horas**, validado contra hash SHA-256 y estado `revoked`.
- Registro público de usuarios (se asigna **perfil 3**).
- Persistencia de sesión al recargar la aplicación.
- Vista **Configuración** para editar el perfil propio.
- El nombre de usuario actualizado se sincroniza en el store y en `localStorage`.
- Cambio de clave opcional: si los campos se dejan vacíos se conserva la clave actual.
- Solo el **perfil 1** puede eliminar usuarios vía API.

### Ofertas

- Crear, editar, ver y listar ofertas.
- Pestañas de **Información Básica**, **Cronograma** y **Documentos**.
- Filtros por consecutivo y por objeto/descripción.
- Paginación configurable (10 registros por página en la UI).
- Generación automática del consecutivo con formato `PO-XXX-YY`.
- Estados de oferta (por defecto `creacion`).
- Validación de moneda (`COP`, `USD`, `EUR`), presupuesto mayor a 0 con máximo 2 decimales y cronograma coherente.
- En edición se exige al menos **1 documento** cargado.
- Eliminación de ofertas vía API con borrado transaccional de sus documentos y archivos.

### Documentos

- Carga de archivos **PDF** y **ZIP** durante la edición de una oferta.
- Validación de tipo MIME real y tamaño máximo de **10 MB**.
- Renombrado seguro del archivo y almacenamiento en `public/documentos_ofertas/`.
- Descarga desde el detalle y desde la edición.
- Eliminación con validación de permisos y borrado físico del archivo.

### Actividades

- Crear, editar, ver y listar actividades.
- Campos de catálogo: código/nombre de segmento, familia, clase y producto.
- Filtros por segmento y producto.
- Paginación.
- Eliminación con validación: no se permite borrar una actividad que tenga ofertas asociadas (se informan los consecutivos).
- Cargue masivo desde Excel `.xlsx`:
  - Plantilla descargable con encabezados y fila de ejemplo.
  - Validación de campos obligatorios, formato numérico, longitud de textos, duplicados dentro del archivo y duplicados contra la base de datos.
  - Si una fila falla, **no se carga ninguna** y se devuelve el detalle de errores por fila.
  - Inserción por lotes dentro de una transacción.

### Reportes

- **Ofertas**: exportación a Excel (`ofertas.xlsx`) con los filtros aplicados.
- **Actividades**: descarga de plantilla Excel (`plantilla_actividades.xlsx`) para el cargue masivo.

### Interfaz

- Panel de inicio con accesos a Licitaciones y Actividades.
- Navbar con menús de Licitaciones, Actividades y Usuario.
- Diseño responsive con Bootstrap 5.
- Notificaciones con SweetAlert2.
- Acciones de edición/eliminación visibles según perfil y propietario del registro.

---

## Perfiles y permisos

El sistema maneja tres perfiles (`usuario.perfil`):

| Perfil | Visibilidad | Edición/eliminación |
| --- | --- | --- |
| **1** | Todos los registros | Puede gestionar cualquier oferta, actividad y documento. Único perfil que puede eliminar usuarios. |
| **2** | Todos los registros | Solo puede editar/eliminar sus propios registros. |
| **3** | Ofertas filtradas por propietario. Actividades: listado general. | Solo puede editar/eliminar sus propios registros. |

Reglas implementadas en el backend:

- `filtrarRegistrosVisibles()` limita las ofertas del perfil 3 a `usuario_id = usuario autenticado`.
- `puedeGestionarRegistro()` permite la gestión al perfil 1 o al propietario del registro.
- La actualización de usuario permite al perfil 1 editar a cualquiera y al resto editar solo su propio perfil.
- La eliminación de usuarios está restringida al perfil 1.

---

## Reglas de validación principales

- **Usuario**: 4–20 caracteres alfanuméricos; nombre y apellido de 3–40 letras; clave de 7–100 caracteres (`letras`, `números`, `$`, `@`, `.`, `-`).
- **Oferta**: objeto máximo 150 caracteres; descripción máximo 400; presupuesto numérico mayor a 0 con hasta 2 decimales; fecha/hora de cierre posterior a la de inicio.
- **Actividad**: códigos numéricos de hasta 10 dígitos; textos de hasta 150 caracteres en el formulario y hasta 200 en el cargue masivo.
- **Documentos**: solo PDF/ZIP, máximo 10 MB.
- **Cargue masivo**: solo `.xlsx`, máximo 10 MB, primera fila de encabezados.

---

## API REST

Prefijo base: `http://localhost/php/index.php/licitaciones/api_licitaciones`

Todas las rutas requieren `Authorization: Bearer <token>`, salvo las indicadas como públicas.

### Autenticación y usuarios

| Método | Ruta | Público | Descripción |
| --- | --- | --- | --- |
| GET | `/` | Sí | Health check de la API |
| POST | `/api/user/login` | Sí | Iniciar sesión y obtener token |
| POST | `/api/user/insert` | Sí | Registrar usuario (perfil 3) |
| GET | `/api/user/get` | No | Obtener el usuario autenticado |
| PUT | `/api/user/update` | No | Actualizar perfil propio; perfil 1 puede editar a cualquiera |
| DELETE | `/api/user/delete/{id}` | No | Eliminar usuario (solo perfil 1) |

### Ofertas

| Método | Ruta | Descripción |
| --- | --- | --- |
| POST | `/api/oferta/insert` | Crear oferta |
| GET | `/api/oferta/find/{id}` | Obtener oferta por ID |
| GET | `/api/oferta/list` | Listar con `pagina`, `registros`, `consecutivo`, `descripcion`, `objeto` |
| PUT | `/api/oferta/update` | Actualizar oferta |
| DELETE | `/api/oferta/delete/{id}` | Eliminar oferta, documentos y archivos |
| GET | `/api/oferta/excel` | Generar Excel con los filtros aplicados |

### Documentos de oferta

| Método | Ruta | Descripción |
| --- | --- | --- |
| POST | `/api/ofertaDocumento/insert` | Subir documento (multipart: `titulo`, `descripcion`, `licitacion_id`, `archivo_oferta`) |
| GET | `/api/ofertaDocumento/get?id={oferta_id}` | Listar documentos de una oferta |
| DELETE | `/api/ofertaDocumento/delete/{id}` | Eliminar documento y archivo físico |

### Actividades

| Método | Ruta | Descripción |
| --- | --- | --- |
| GET | `/api/actividad/get` | Listado para selector; con `id` devuelve el detalle |
| GET | `/api/actividad/list` | Listar con `pagina`, `registros`, `segmento`, `producto` |
| POST | `/api/actividad/insert` | Crear actividad |
| PUT | `/api/actividad/update` | Actualizar actividad |
| DELETE | `/api/actividad/delete/{id}` | Eliminar actividad (bloqueado si tiene ofertas) |
| POST | `/api/actividad/masivo` | Cargue masivo `.xlsx` (`archivo_actividades`) |
| GET | `/api/actividad/plantilla` | Descargar plantilla Excel |

---

## Base de datos

| Tabla | Descripción |
| --- | --- |
| `usuario` | Usuarios, clave con bcrypt y perfil (1, 2, 3) |
| `api_tokens` | Tokens de acceso, hash, expiración y revocación |
| `actividades` | Catálogo de segmento/familia/clase/producto y propietario |
| `ofertas` | Ofertas con consecutivo, presupuesto, cronograma, estado y propietario |
| `ofertas_documentos` | Metadatos y ruta física de los adjuntos de cada oferta |

Relaciones principales:

- Una **oferta** pertenece a una **actividad** (`ofertas.actividad_id`).
- Una **oferta** tiene muchos **documentos** (`ofertas_documentos.licitacion_id`), con borrado en cascada.
- Cada **oferta**, **actividad** y **usuario** registra su propietario mediante `usuario_id` / `id`.

---

## Notas de desarrollo

- El backend no usa un framework de rutas: el enrutamiento se resuelve en `api_licitaciones/index.php`.
- La sanitización de entradas se centraliza en `Controller::limpiarCadena()`.
- Las respuestas siguen el formato `{ "code": <status>, "data": <resultado> }`.
- La zona horaria del backend está configurada en `America/Bogota`.
- Los archivos subidos se guardan en `api_licitaciones/public/documentos_ofertas/`.
- El frontend obtiene la URL de la API desde `authStore.baseUrl`.

---

## Solución de problemas

- **401 / 403**: token ausente, vencido, revocado o perfil sin permisos. Volver a iniciar sesión.
- **Error al generar/leer Excel**: verificar que PHP tenga habilitadas las extensiones `zip` y `gd`.
- **Error al subir archivos**: verificar permisos de escritura en `public/documentos_ofertas/` y el límite de 10 MB.
- **No conecta con la API**: revisar `src/stores/authStore.js` (`baseUrl`) y `config/database.php`.
- **Error de CORS/Apache**: confirmar que `mod_rewrite` está activo y que el `.htaccess` de `api_licitaciones` se está aplicando.

---

**Última actualización**: Octubre 2026