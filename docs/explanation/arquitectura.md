# Explicación: arquitectura del módulo

El módulo de Licitaciones es una aplicación de dos procesos: una **API PHP** servida por Apache y un **SPA Vue 3** servido por Vite en desarrollo. No hay un framework de rutas tipo Laravel completo: Eloquent (Illuminate Database) se usa como capa de persistencia, y el despacho HTTP es un `if` sobre método + URI en `index.php`.

## Por qué está partido así

La UI necesita un front moderno (componentes, Pinia, Bootstrap 5). La persistencia y las reglas de negocio (consecutivo, permisos, Excel, archivos) viven en PHP porque el entorno de destino es XAMPP y MySQL. El contrato entre ambos es JSON (y binarios Excel/archivos) sobre HTTP.

```text
Navegador (Vue 3 + Pinia)
    │  Axios + Bearer token
    ▼
Apache + .htaccess  →  api_licitaciones/index.php
    │  Protection::verificarToken (rutas privadas)
    ▼
Controllers  →  Eloquent models  →  MySQL `licitaciones`
             ↘  disco: public/documentos_ofertas/
             ↘  PhpSpreadsheet (.xlsx)
```

## Enrutamiento de la API

`index.php` recorta `$basePath = '/php/index.php/licitaciones/api_licitaciones'` de `REQUEST_URI`. Si el proyecto se publica en otra carpeta, hay que cambiar ese valor **y** `authStore.baseUrl`. El `.htaccess` reescribe todo lo que no es archivo real hacia `index.php`, de modo que `GET /api/oferta/list` llega al mismo script.

Eso explica 404 “Ruta no encontrada” cuando Apache no reescribe, o cuando el prefijo de carpeta no coincide: el `if` de rutas nunca matchea.

## Autenticación en dos capas

1. **Login** genera 32 bytes aleatorios en hex, guarda el valor y su SHA-256, y devuelve el valor en claro una vez.
2. **Protection** no usa JWT: busca el hash en `api_tokens`. Un token vencido o revocado es 403, no 401. 401 es solo “no vino token”.

El front persiste la sesión en `localStorage`. Recargar la página no pega a `/api/user/get` para revalidar: restaura el JSON guardado. Por eso un token ya vencido se “siente” válido hasta la próxima llamada a la API.

Logout del navbar no revoca la fila en `api_tokens`. El token sigue usable hasta `expires_at` si alguien lo copió.

## Dónde se aplican los permisos

`Controller` define dos helpers:

- `filtrarRegistrosVisibles`: si el perfil es 3, agrega `where usuario_id = yo`. Se usa en **ofertas** (list, find, excel, documentos get) y al **resolver la actividad** en insert/update de oferta.
- `puedeGestionarRegistro`: perfil 1 **o** dueño. Se usa en update/delete de ofertas, actividades y documentos, y el update de usuario tiene una regla propia (perfil 1 o el mismo id).

El listado de **actividades** no usa `filtrarRegistrosVisibles`: el catálogo UNSPSC se trata como visible para todos los autenticados. Eso no contradice que un perfil 3 no pueda **editar** la actividad de otro, ni usarla al crear una oferta (el insert de oferta sí filtra la actividad).

## Archivos y Excel

Los adjuntos no van a la base: la fila guarda nombre y ruta relativa. Borrar una oferta intenta dejar el disco consistente después del commit; si `unlink` falla, la oferta ya no está y la API avisa 500.

Excel (plantilla, masivo, reporte) depende de PhpSpreadsheet, que a su vez necesita las extensiones `zip` y `gd` del PHP de Apache (no solo del CLI).

## Frontend: una vista, dos modos

`OfertasNewView` y `ActividadNewView` sirven de alta y de edición según la ruta (`/ofertasEditar/:id`, `/actividadEditar/:id`). Las pestañas de detalle son de solo lectura. El diseño visual está centralizado en `estilos.css` sobre Bootstrap, no en un design system aparte.

## Qué no es este sistema

No hay cola de jobs, ni refresh token, ni roles granulares más allá de `perfil` entero. El consecutivo se calcula con el último `id` cuyo `consecutivo` matchea `PO-%-{yy}`; no es un sequence de base de datos. La moneda y el estado de oferta son strings, no tablas de catálogo.
