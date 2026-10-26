# Explicación: perfiles, dueños y tokens

El control de acceso no es un ACL por pantalla. Es un número en `usuario.perfil` más un `usuario_id` en cada oferta y actividad. Entender esas dos ideas evita sorpresas del tipo “veo el registro pero no lo puedo guardar”.

## Los tres perfiles

| Perfil | Cómo se obtiene | Idea |
| --- | --- | --- |
| **3** | Valor fijo del registro público | Usuario operativo “estrecho”: ve y opera sobre **sus** ofertas. El catálogo de actividades lo ve completo, pero no edita las ajenas y no puede colgar una oferta de una actividad que no es suya. |
| **2** | Asignado en base (no hay pantalla de promoción) | Ve **todas** las ofertas. Solo gestiona las propias. |
| **1** | Asignado en base | Administrador: ve y gestiona cualquier oferta/actividad/documento, y puede editar o borrar usuarios. |

No hay nombres de rol en la UI. El navbar es el mismo para todos. Lo que cambia son las acciones que el backend acepta y, en listados de ofertas, las filas que existen para ese token.

## Visibilidad frente a gestión

Son chequeos distintos:

- **Visibilidad** (`filtrarRegistrosVisibles`): recorta queries. Solo perfil 3. Aplica a ofertas, no al listado general de actividades.
- **Gestión** (`puedeGestionarRegistro`): perfil 1 o coincidencia de `usuario_id`. Aplica a mutaciones.

Por eso un perfil 2 puede abrir el detalle de una oferta ajena y aún así recibir 403 al actualizarla o al subir un PDF. Un perfil 3 ni siquiera obtiene esa oferta en `list` / `find`.

Al **crear** una oferta, el perfil 3 además necesita que la actividad exista **dentro de su recorte**. El selector (`GET /api/actividad/get`) sí lista productos de todos; el error aparece al guardar si eligió una actividad ajena. Ese desajuste es del diseño actual, no un bug de “la actividad no está en la base”.

## Autoedición de perfil

`PUT /api/user/update` no usa `puedeGestionarRegistro`. Compara el `id_usuario` del body con el usuario del token: si no sos perfil 1, solo podés coincidir con vos. Así un usuario 2 o 3 cambia su nombre o clave sin poder enumerar ni alterar al resto. Borrar usuarios sigue siendo un camino aparte, protegido con `requerirPerfil(..., [1])`.

La clave opcional existe para no forzar un password nuevo en cada guardado de datos personales. El hash solo se reescribe si llegan las dos claves iguales y con formato válido.

## Ciclo de vida del token

Cada login **agrega** una fila en `api_tokens`; no invalida las anteriores. Podés tener varios tokens vivos para el mismo usuario hasta que cada uno cumpla 24 horas.

El middleware exige que el perfil del usuario dueño del token sea exactamente 1, 2 o 3. Un valor raro en base corta con 403 aunque el token exista.

Llevar el token en query string (`?token=`) existe para que el navegador descargue Excel o plantillas con el mismo secreto. Eso implica que el valor puede quedar en logs de Apache: es un compromiso de implementación, no un segundo mecanismo de auth.

## Propiedad de los registros

`usuario_id` se setea al crear (oferta, actividad, cargue masivo) y **no se reasigna** en los updates. Transferir una oferta a otro usuario no es un flujo del producto. Eliminar una actividad se bloquea si hay ofertas, aunque seas perfil 1: la integridad de negocio (catálogo referenciado) pesa más que el rol.
