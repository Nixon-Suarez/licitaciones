import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    usuario: null,
    token: null,

    baseUrl: 'http://localhost/php/index.php/licitaciones/api_licitaciones/api/'
  }),

  actions: {
    Login(data) {
      this.usuario = data.usuario
      this.token = data.token
      this.id_usuario = data.id_usuario

      localStorage.setItem('userData', JSON.stringify(data))
    },

    Logout() {
      this.usuario = null
      this.token = null

      localStorage.removeItem('userData')
    }
  }
})
