import { createRouter, createWebHistory } from 'vue-router'
import InicioView from '../views/InicioView.vue'
import LoginView from '../views/LoginView.vue'
import OfertasNewView from '../views/OfertasNewView.vue'
import OfertasListView from '../views/OfertasListView.vue'
import OfertasDetalleView from '../views/OfertasDetalleView.vue'
import ActividadListView from '../views/ActividadListView.vue'
import ActividadNewView from '../views/ActividadNewView.vue'
import UsuarioUpdateView from '../views/UsuarioUpdateView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/inicio',
      name: 'inicio',
      component: InicioView
    },
    {
      path: '/ofertasNew',
      name: 'ofertasNew',
      component: OfertasNewView
    },
    {
      path: '/ofertasList',
      name: 'ofertasList',
      component: OfertasListView
    },
    {
      path: '/ofertasDetalle/:id',
      name: 'ofertasDetalle',
      component: OfertasDetalleView
    },
    {
      path: '/ofertasEditar/:id',
      name: 'ofertasEditar',
      component: OfertasNewView
    },
    {
      path: '/actividadList',
      name: 'actividadList',
      component: ActividadListView
    },
    {
      path: '/actividadNew',
      name: 'actividadNew',
      component: ActividadNewView
    },
    {
      path: '/actividadEditar/:id',
      name: 'actividadEditar',
      component: ActividadNewView
    },
    {
      path: '/usuarioUpdate',
      name: 'usuarioUpdate',
      component: UsuarioUpdateView
    },

  ],
})

export default router
