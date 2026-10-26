# Referencia: validaciones

Las reglas viven en controladores PHP (`verificarDatos` / `preg_match`) y, en paralelo, en `pattern` / `required` del HTML Vue.

`limpiarCadena()` recorta, quita slashes, elimina un listado de substrings (etiquetas script, fragmentos SQL, `<?php`, etc.) y aplica `htmlspecialchars`.

## Usuario

| Campo | Alta | Actualización |
| --- | --- | --- |
| Nombre / apellido | `^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$` | Igual |
| Usuario | `^[a-zA-Z0-9]{4,20}$` | Igual; único salvo el propio `id` |
| Clave | `^[a-zA-Z0-9$@.-]{7,100}$` y debe coincidir con confirmación | Opcional: ambas vacías = no cambia; una sola o distintas = 400 |
| Perfil | Forzado a `3` en el registro | No se envía desde Configuración |

## Oferta

| Campo | Regla |
| --- | --- |
| objeto | 1–150, letras (con tildes), números, espacios; no solo espacios |
| descripcion | 1–400, mismo juego de caracteres |
| presupuesto | numérico > 0, hasta 2 decimales |
| moneda | `COP` \| `USD` \| `EUR` |
| fechas | `YYYY-MM-DD` |
| horas | `HH:MM` o `HH:MM:SS` (00–23) |
| cierre | datetime de cierre **estrictamente posterior** al de inicio |
| actividad_id | debe existir; en insert/update de oferta, perfil 3 solo si `usuario_id` de la actividad es el suyo |
| documentos en update | al menos 1 fila en `ofertas_documentos` |

## Documentos

| Campo | Regla |
| --- | --- |
| titulo / descripcion | 1–150, mismo juego alfanumérico con espacios |
| archivo | obligatorio; MIME `application/pdf` o `application/zip`; ≤ 10 MB |

## Actividad (formulario)

| Campo | Regla |
| --- | --- |
| nombres (segmento, familia, clase, producto) | 1–150, letras/números/espacios |
| códigos | 1–10 dígitos |

## Cargue masivo

| Campo | Regla |
| --- | --- |
| archivo | `.xlsx`, ≤ 10 MB, campo `archivo_actividades` |
| textos | `/^[\p{L}\p{N}\s,()\-\/&'#;%°]{1,200}$/u` (sin punto) |
| códigos | `/^\d{1,10}$/` |
| `codigo producto` | único en el archivo y ausente en BD |

Si hay al menos un error de fila, **no se inserta ninguna**.
