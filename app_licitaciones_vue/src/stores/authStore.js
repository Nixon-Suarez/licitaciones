import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    usuario: null,
    token: null,
    id_usuario: null,
    perfil: null,

    baseUrl: 'http://localhost/php/index.php/licitaciones/api_licitaciones/api/'
  }),

  actions: {
    Login(data) {
      this.usuario = data.usuario
      this.token = data.token
      this.id_usuario = data.id_usuario
      this.perfil = data.perfil

      localStorage.setItem('userData', JSON.stringify(data))
    },

    ActualizarUsuario(data) {
      this.usuario = data.usuario ?? this.usuario
      this.token = data.token ?? this.token
      this.id_usuario = data.id_usuario ?? this.id_usuario
      this.perfil = data.perfil ?? this.perfil

      const guardado = localStorage.getItem('userData')
      const actual = guardado ? JSON.parse(guardado) : {}
      localStorage.setItem('userData', JSON.stringify({ ...actual, ...data }))
    },

    Logout() {
      this.usuario = null
      this.token = null
      this.id_usuario = null
      this.perfil = null

      localStorage.removeItem('userData')
    }
  }
})
