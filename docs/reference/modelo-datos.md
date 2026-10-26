# Referencia: modelo de datos

Script de origen: `BD/licitaciones.sql`. Motor esperado: MySQL/MariaDB, `utf8mb4`.

## Diagrama de relaciones

```text
usuario 1───* api_tokens     (user_id)
usuario 1───* actividades    (usuario_id)
usuario 1───* ofertas        (usuario_id)
actividades 1───* ofertas    (actividad_id)   ON UPDATE CASCADE
ofertas 1───* ofertas_documentos (licitacion_id)  ON DELETE CASCADE ON UPDATE CASCADE
```

No hay FK declarada de `ofertas.usuario_id` ni de `actividades.usuario_id` hacia `usuario`.

## `usuario`

| Columna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK AI | |
| `usuario_nombre` | varchar(70) | |
| `usuario_apellido` | varchar(70) | |
| `usuario_usuario` | varchar(30) UNIQUE | Login |
| `usuario_clave` | varchar(200) | bcrypt (`PASSWORD_BCRYPT`, cost 10) |
| `usuario_creado` | timestamp | Eloquent `CREATED_AT` |
| `usuario_actualizado` | timestamp | Eloquent `UPDATED_AT` |
| `clave_no_cript` | varchar(150) | Campo legado en el dump; el login usa `usuario_clave` |
| `perfil` | int | 1, 2 o 3 |

Modelo: `App\Models\Usuario`. Hidden: `usuario_clave`.

## `api_tokens`

| Columna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK AI | |
| `user_id` | int | Dueño |
| `token` | varchar(255) UNIQUE | Valor en claro al crear la fila |
| `token_hash` | varchar(255) | SHA-256 del token que viaja en `Authorization` |
| `created_at` | datetime | |
| `expires_at` | datetime | Login pone +1 día |
| `revoked` | tinyint(1) | 0 = vigente |

Modelo: `App\Models\Token`. Hidden: `token`, `token_hash`. Sin `UPDATED_AT`.

La validación compara `hash('sha256', $token)` con `token_hash`, `revoked = 0` y `expires_at` futuro o nulo.

## `actividades`

| Columna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK AI | |
| `usuario_id` | int | Propietario |
| `codigo_segmento` | int | |
| `segmento` | varchar(200) | |
| `codigo_familia` | int | |
| `familia` | varchar(200) | |
| `codigo_clase` | int | |
| `clase` | varchar(200) | |
| `codigo_producto` | int | Usado para duplicados del cargue masivo |
| `producto` | varchar(200) | |

Sin timestamps Eloquent.

## `ofertas`

| Columna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK AI | |
| `consecutivo` | varchar(50) UNIQUE | Formato `PO-{n}-{yy}`, n con 3 dígitos |
| `objeto` | varchar(150) | |
| `descripcion` | varchar(400) | |
| `moneda` | varchar(3) | `COP`, `USD`, `EUR` |
| `presupuesto` | decimal(15,2) | |
| `actividad_id` | int FK | |
| `usuario_id` | int | Propietario |
| `fecha_inicio` / `hora_inicio` | date / time | |
| `fecha_cierre` / `hora_cierre` | date / time | |
| `estado` | varchar(20) | Alta y update dejan `creacion` |
| `creado_en` / `actualizado_en` | datetime | Timestamps Eloquent |

## `ofertas_documentos`

| Columna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK AI | |
| `licitacion_id` | int FK | Oferta |
| `titulo` | varchar(100) | Validación de API permite hasta 150; el límite real es la columna |
| `descripcion` | varchar(200) | |
| `archivo` | varchar(255) | Nombre en disco |
| `nombre_original` | varchar(255) | Nombre subido |
| `ruta_archivo` | varchar(255) | Típicamente `public/documentos_ofertas/` |
| `creado_en` | datetime | |

Archivos físicos: `api_licitaciones/public/documentos_ofertas/`.
