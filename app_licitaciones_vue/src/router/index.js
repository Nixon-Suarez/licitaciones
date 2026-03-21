import { createRouter, createWebHistory } from 'vue-router'
import InicioView from '../views/InicioView.vue'
import LoginView from '../views/LoginView.vue'

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
      component: 234
    },
    {
      path: '/ofertasList',
      name: 'ofertasList',
      component: 234
    },
    {
      path: '/actividadList',
      name: 'actividadList',
      component: 234
    }
  ],
})

export default router
