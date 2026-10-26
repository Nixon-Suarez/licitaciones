# How-to: gestionar actividades (catálogo UNSPSC)

Usá esta guía para dar de alta, editar, listar o borrar una actividad de una en una. Para muchas filas a la vez, usá [Cargue masivo](cargue-masivo-actividades.md).

## Crear una actividad

1. **Actividades → Nuevo**.
2. Completá los cuatro niveles: código y nombre de segmento, familia, clase y producto.
3. Códigos: solo dígitos, hasta 10. Nombres en el formulario: hasta 150 caracteres (letras, números y espacios).
4. Guardá. Queda asociada a tu `usuario_id`.

## Listar y filtrar

**Actividades → Listado** pagina de a 10. Filtros por **segmento** y **producto** (coincidencia parcial).

El listado de actividades **no** se recorta por perfil: todos los autenticados ven el catálogo. La restricción de dueño aplica a **editar** y **eliminar**.

## Editar

Solo perfil 1 o el propietario. Enviá `id_actividad` más los mismos campos que en el alta.

## Eliminar

No se puede borrar una actividad que tenga ofertas. La API lista los consecutivos de esas ofertas en el mensaje de error.

Si no tiene ofertas y tenés permiso de gestión, se elimina el registro.

## Selector de actividades en una oferta

El combo de ofertas llama `GET /api/actividad/get` (sin `id`) y muestra el producto. El detalle de una actividad usa el mismo endpoint con `?id=`.
