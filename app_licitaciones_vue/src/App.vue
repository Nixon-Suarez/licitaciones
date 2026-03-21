<template>
  <div v-if="usuario">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistema de Registro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/inicio">Inicio</router-link>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Administración
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><router-link class="dropdown-item" to="/curso">Cursos</router-link></li>
              </ul>
            </li>
          </ul>
          <button class="btn btn-outline-light" @click="logout">Logout</button>
        </div>
      </div>
    </nav>
    <main class="container mt-4">
      <router-view/>
    </main>
  </div>
  <div v-else>
    <router-view/>
  </div>
</template>

<script>
import { useAuthStore } from '@/stores/authStore'

export default {
  name: 'App',

  computed: {
    usuario() {
      const authStore = useAuthStore()
      return authStore.usuario
    }
  },

  methods: {
    logout() {
      const authStore = useAuthStore()
      authStore.Logout()
      this.$router.push('/')
    },
    validarAcceso() {
      const authStore = useAuthStore()
      let datos = localStorage.getItem('userData')
      if (!datos) {
        this.$router.push('/')
      } else {
        authStore.Login(JSON.parse(datos))
        this.$router.push('/inicio')
      }
    }
  },
  created() {
    this.validarAcceso()
  }
}
</script>
