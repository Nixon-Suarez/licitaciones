# How-to: gestionar ofertas (licitaciones)

Usá esta guía cuando ya tenés la aplicación corriendo y querés crear, editar, adjuntar documentos o eliminar una oferta.

## Crear una oferta

1. Entrá a **Licitaciones → Nuevo** (o **Crear** desde Inicio).
2. Completá **Información básica**: objeto, descripción, moneda (`COP`, `USD` o `EUR`), presupuesto mayor a 0 (hasta 2 decimales) y actividad UNSPSC.
3. Completá **Cronograma**: fecha/hora de inicio y de cierre. El cierre debe ser posterior al inicio.
4. Guardá. El backend asigna el consecutivo `PO-XXX-YY` (año de dos dígitos) y el estado `creacion`.

Al crear **no** se exigen documentos. Eso sí se exige al **editar**.

## Editar una oferta

1. Andá a **Licitaciones → Listado**.
2. Abrí la oferta con **editar**. Solo ves esa acción si sos perfil 1 o el dueño del registro.
3. En la pestaña **Documentos**, cargá al menos un PDF o ZIP (máximo 10 MB).
4. Guardá los cambios de información y cronograma.

Si guardás la edición sin documentos, la API responde 400: *En edición, debe existir al menos 1 documento cargado*.

## Adjuntar o quitar un documento

1. En edición, pestaña **Documentos**, abrí el modal de carga.
2. Título, descripción y archivo (`archivo_oferta`) son obligatorios.
3. El archivo se guarda en `api_licitaciones/public/documentos_ofertas/` con un nombre sanitizado.
4. Para borrar un adjunto, usá eliminar en esa misma pestaña. Se borra la fila y el archivo físico.

Podés descargar desde el detalle (`/ofertasDetalle/:id`) o desde la edición.

## Filtrar y paginar el listado

El listado pide `pagina` y `registros` (la UI usa 10 por página). Filtros:

- consecutivo (coincidencia parcial)
- texto sobre objeto/descripción

El perfil 3 solo ve ofertas cuyo `usuario_id` es el suyo. Los perfiles 1 y 2 ven todas.

## Eliminar una oferta

La eliminación está en la API (`DELETE /api/oferta/delete/{id}`). Borra en transacción la oferta y sus filas de documentos, y después intenta borrar los archivos del disco.

Solo perfil 1 o el propietario pueden eliminar.

## Problemas frecuentes

| Síntoma | Qué revisar |
| --- | --- |
| 401 / 403 | Token vencido (24 h) o sin permiso de gestión |
| 400 al guardar edición | Falta un documento |
| Error al subir | MIME distinto de PDF/ZIP, más de 10 MB, o carpeta sin escritura |
| Actividad “no existe” (perfil 3) | El perfil 3 solo puede asociar actividades de las que es dueño al crear/editar la oferta |
