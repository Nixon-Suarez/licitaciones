# Referencia: API REST

Base de ejemplo:

`http://localhost/php/index.php/licitaciones/api_licitaciones`

El frontend concatena rutas sobre:

`.../api_licitaciones/api/`

El enrutador vive en `api_licitaciones/index.php`. El `basePath` que recorta de `REQUEST_URI` está hardcodeado a `/php/index.php/licitaciones/api_licitaciones`.

## Convenciones

- JSON de aplicación: `{ "code": <int>, "data": <mensaje|objeto|lista> }`. Login también envía `token` en la raíz.
- Errores de middleware de token: `{ "error": "..." }` con HTTP 401 o 403.
- Autenticación: `Authorization: Bearer <token>`. También se acepta el token en `?token=` o `?Authorization=` (útil para descargas).
- CORS: `Access-Control-Allow-Origin: *`. `OPTIONS` responde 200 y corta.
- Zona horaria del backend: `America/Bogota`.

## Rutas públicas

| Método | Ruta | Cuerpo | Respuesta |
| --- | --- | --- | --- |
| GET | `/` | — | `{ "message": "API Licitaciones" }` |
| POST | `/api/user/login` | `{ "login_usuario", "login_clave" }` | `200` + `data` (`id`, `usuario`, `perfil`) + `token` |
| POST | `/api/user/insert` | `{ "register_nombre", "register_apellido", "register_usuario", "register_clave1", "register_clave2" }` | `200` mensaje. Crea perfil **3**. |

## Usuarios (autenticadas)

| Método | Ruta | Notas |
| --- | --- | --- |
| GET | `/api/user/get` | Usuario del token. Campos: `id`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`. |
| PUT | `/api/user/update` | JSON: `id_usuario`, `nombre`, `apellido`, `usuario`, `clave`, `clave2`. Claves vacías = no cambiar hash. Perfil 1 puede editar a otros. |
| DELETE | `/api/user/delete/{id}` | Solo perfil 1 (`requerirPerfil([1])`). |

## Ofertas

| Método | Ruta | Parámetros / cuerpo |
| --- | --- | --- |
| POST | `/api/oferta/insert` | JSON: `objeto`, `descripcion`, `moneda`, `presupuesto`, `actividad_id`, `fecha_inicio`, `fecha_cierre`, `hora_inicio`, `hora_cierre`. HTTP **201**. Consecutivo automático. `usuario_id` del token. Estado `creacion`. |
| GET | `/api/oferta/find/{id}` | Detalle. Perfil 3: solo si es dueño. |
| GET | `/api/oferta/list` | Query: `pagina`, `registros`, `consecutivo`, `descripcion`, `objeto`. `data`: `datos`, `total`, `paginas`, `pagina_actual`, `inicio`. |
| PUT | `/api/oferta/update` | Igual que insert más `id`. Exige ≥ 1 documento. No cambia `usuario_id` ni consecutivo. |
| DELETE | `/api/oferta/delete/{id}` | Transacción documentos + oferta; luego `unlink` de archivos. |
| GET | `/api/oferta/excel` | Mismos filtros que list. Descarga `ofertas.xlsx` (sin paginar). |

## Documentos de oferta

| Método | Ruta | Notas |
| --- | --- | --- |
| POST | `/api/ofertaDocumento/insert` | `multipart/form-data`: `titulo`, `descripcion`, `licitacion_id`, `archivo_oferta`. MIME `application/pdf` o `application/zip`. Máx. 10 MB. |
| GET | `/api/ofertaDocumento/get?id={oferta_id}` | Lista. Sin documentos: HTTP **204**. Cada ítem incluye `url_descarga`. |
| DELETE | `/api/ofertaDocumento/delete/{id}` | Borra fila y archivo. Permiso según dueño de la **oferta**. |

## Actividades

| Método | Ruta | Notas |
| --- | --- | --- |
| GET | `/api/actividad/get` | Sin `id`: lista `id`, `producto`, `usuario_id`. Con `?id=`: detalle UNSPSC. Sin filtro de perfil. |
| GET | `/api/actividad/list` | Query: `pagina`, `registros`, `segmento`, `producto`. Sin filtro de perfil. |
| POST | `/api/actividad/insert` | JSON: `cod_segmento`, `nom_segmento`, `cod_familia`, `nom_familia`, `cod_clase`, `nom_clase`, `cod_product`, `nom_product`. HTTP **201**. |
| PUT | `/api/actividad/update` | Más `id_actividad`. Requiere poder gestionar el registro. |
| DELETE | `/api/actividad/delete/{id}` | 400 si hay ofertas (incluye consecutivos en el mensaje). |
| POST | `/api/actividad/masivo` | `multipart`: `archivo_actividades`. Ver [cargue masivo](../how-to/cargue-masivo-actividades.md). `400` + `errores[{fila,motivo}]` o `201`. |
| GET | `/api/actividad/plantilla` | Binario `plantilla_actividades.xlsx`. Token requerido. |

## Códigos HTTP usados

| Código | Uso |
| --- | --- |
| 200 | OK (incluye login, updates, delete, listados, adjunto creado) |
| 201 | Alta de oferta/actividad o cargue masivo OK |
| 204 | Oferta sin documentos |
| 400 | Validación, duplicados, actividad con ofertas |
| 401 | Token ausente |
| 403 | Token inválido/vencido/revocado, perfil inválido, sin permiso de gestión |
| 404 | Ruta o recurso inexistente |
| 500 | Excepción de servidor o fallo al borrar archivos físicos |

Ruta no reconocida:

```json
{ "code": 404, "data": "Ruta no encontrada" }
```
