<template>
  <div v-if="usuario">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <!-- inicio -->
            <a class="navbar-brand me-2" href="/inicio">
                <img src="./assets/img/licitaciones.png" class="img-profile me-2">
            </a>
            <!-- Ofertas -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="OfertasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Licitaciones
              </a>
              <ul class="dropdown-menu" aria-labelledby="ofertaDropdown">
                  <li><router-link class="dropdown-item" to="/ofertasList">Listado</router-link></li>
                  <li><router-link class="dropdown-item" to="/ofertasNew">Nuevo</router-link></li>
              </ul>
            </li>
            <!-- Actividades -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="ActividadesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Actividades
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><router-link class="dropdown-item" to="/actividadList">Listado</router-link></li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="UsuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Usuario
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><router-link class="dropdown-item" to="/usuarioUpdate">Configuracion</router-link></li>
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
