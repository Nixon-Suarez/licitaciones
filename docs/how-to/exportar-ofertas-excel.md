# How-to: exportar ofertas a Excel

Objetivo: bajar un `.xlsx` con las ofertas que coinciden con los filtros del listado.

## Pasos

1. Abrí **Licitaciones → Listado**.
2. Aplicá (si hace falta) filtro de consecutivo y de objeto/descripción.
3. Usá la acción de generar Excel.

La petición es `GET /api/oferta/excel` con los mismos query params que el listado (`consecutivo`, `descripcion`, `objeto`). El archivo se llama `ofertas.xlsx`.

## Qué incluye el archivo

Columnas: Consecutivo, Objeto, Descripcion, Fecha de inicio, Fecha de cierre, Estado.

El export **respeta visibilidad**: el perfil 3 solo descarga sus ofertas. Perfiles 1 y 2 descargan todas las que pasen el filtro.

A diferencia del listado paginado, el Excel trae **todas** las filas filtradas, no solo la página actual.

Si la descarga falla, habilitá `zip` y `gd` en PHP y reintentá con un token válido.
