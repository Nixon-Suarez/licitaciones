import { createRouter, createWebHistory } from 'vue-router'
import InicioView from '../views/InicioView.vue'
import LoginView from '../views/LoginView.vue'
import OfertasNewView from '../views/OfertasNewView.vue'
import OfertasListView from '../views/OfertasListView.vue'
import OfertasDetalleView from '../views/OfertasDetalleView.vue'
import ActividadListView from '../views/ActividadListView.vue'

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
    }
  ],
})

export default router
