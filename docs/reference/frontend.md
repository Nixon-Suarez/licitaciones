# Referencia: frontend Vue 3

Paquete: `app_licitaciones_vue`. Vue **3.5**, Vue Router **5**, Pinia **3**, Bootstrap **5.3**, Axios, SweetAlert2, Vite.

Scripts: `npm run dev`, `npm run build`, `npm run preview`, `npm run lint`.

## Rutas (`src/router/index.js`)

| Path | Nombre | Vista |
| --- | --- | --- |
| `/` | `login` | `LoginView` |
| `/inicio` | `inicio` | `InicioView` |
| `/ofertasNew` | `ofertasNew` | `OfertasNewView` (alta) |
| `/ofertasList` | `ofertasList` | `OfertasListView` |
| `/ofertasDetalle/:id` | `ofertasDetalle` | `OfertasDetalleView` |
| `/ofertasEditar/:id` | `ofertasEditar` | `OfertasNewView` (edición) |
| `/actividadList` | `actividadList` | `ActividadListView` |
| `/actividadNew` | `actividadNew` | `ActividadNewView` |
| `/actividadEditar/:id` | `actividadEditar` | `ActividadNewView` |
| `/usuarioUpdate` | `usuarioUpdate` | `UsuarioUpdateView` |

No hay guard de router por perfil. `App.vue` en `created` lee `localStorage.userData`: si hay datos restaura el store y manda a `/inicio`; si no, a `/`.

**Logout** limpia el store y `localStorage`. No llama a la API (el token en servidor sigue hasta vencer).

## Store de autenticación (`src/stores/authStore.js`)

Estado: `usuario`, `token`, `id_usuario`, `perfil`, `baseUrl`.

- `Login(data)`: setea estado y `localStorage['userData']`.
- `ActualizarUsuario(data)`: mezcla el objeto guardado (para que el username nuevo se vea en Inicio).
- `Logout()`: limpia estado y storage.

Todas las llamadas autenticadas envían `Authorization: Bearer ` + `authStore.token`.

## Componentes principales

| Área | Archivos |
| --- | --- |
| Shell | `App.vue` (navbar Licitaciones / Actividades / Usuario) |
| Login / registro | `LoginView.vue`, `RegistroModal.vue` |
| Ofertas | `PresupuestoTab`, `FechasTab`, `DocumentosTab`, `DocumentosModal`, `SelectActividades`; variantes `*DetalleTab` |
| Actividades | `InfoBasicaTab.vue`, `ModalCargueMasivoAct.vue` |
| Usuario | `UsuarioTab.vue` (mismo orden de campos que el registro) |
| Alertas | `alertStore.js` (`alertas_ajax`, SweetAlert2) |

Estilos globales: `src/assets/estilos.css`, importados desde `main.js` después de Bootstrap.

Alias Vite: `@` → `src/`.
