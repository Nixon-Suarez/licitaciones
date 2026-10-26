# How-to: actualizar tu perfil

Objetivo: cambiar nombre, apellido o usuario de sesión sin perder el acceso, y cambiar la clave solo si lo pedís.

## Desde la interfaz

1. Menú **Usuario → Configuracion** (`/usuarioUpdate`).
2. El formulario pide usuario, nombre, apellido, clave y confirmar clave (mismo orden que el registro).
3. Dejá **ambas** claves vacías para conservar la actual.
4. Guardá.

Si cambiaste el nombre de usuario, Inicio debe mostrar el valor nuevo: el front actualiza Pinia y `localStorage['userData']`.

## Quién puede editar a quién

- Cualquier usuario autenticado puede editar **su** perfil (`id_usuario` igual al del token).
- El **perfil 1** puede editar cualquier `id_usuario`.
- Los perfiles 2 y 3 reciben 403 si intentan editar a otra persona.

Eliminar usuarios (`DELETE /api/user/delete/{id}`) es solo perfil 1. La pantalla de configuración no expone esa acción.

## Si querés cambiar la clave

Completá **las dos** claves, con el mismo formato que en el registro (7–100: letras, números, `$ @ . -`). Si enviás una sola, la API responde que las claves no coinciden y no toca el hash.
