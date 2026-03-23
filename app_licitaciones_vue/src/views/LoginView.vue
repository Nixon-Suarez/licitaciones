<template>
    <div class="body-login">
        <div class="login-card">
          <h5 class="text-center text-uppercase mb-4">Iniciar Sesión</h5>
            <img class="card-img-top mb-3" src="../assets/img/Money.png" height="150" alt="Money">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Login</h5>
                <form @submit.prevent="login">
                    <div class="mb-3">
                      <label for="login_usuario" class="form-label">Usuario</label>
                      <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                      <input v-model="usuario.login_usuario" type="text" class="form-control" pattern="[a-zA-Z0-9]{4,20}" placeholder="usuario" maxlength="70" required>
                    </div>
                    <div class="mb-3">
                        <label for="login_clave" class="form-label">Clave</label>
                        <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                        <input v-model="usuario.login_clave" type="password" class="form-control" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" placeholder="********" required>
                    </div>
                    <!-- Botón iniciar sesión-->
                    <div class="d-grid mt-4">
                      <button type="submit" class="btn btn-custom">Iniciar Sesion</button>
                    </div>
                    <!-- Botón Registrarse-->
                    <div class="d-grid mt-4">
                        <button type="button" class="btn btn-custom" @click="modalRegistro = true">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
        <RegistroModal :visible="modalRegistro" @close="modalRegistro = false" />
    </div>
    <div>

    </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import RegistroModal from '@/components/RegistroModal.vue'
import { alertas_ajax } from '@/stores/alertStore'

export default {
  name: "LoginView",
  components: {
    RegistroModal
  },
  data(){
    return {
        usuario:{
            login_usuario: '',
            login_clave: ''
        },
        modalRegistro: false,
    }
  },
  methods: {
    login(){
        const authStore = useAuthStore()
        const API_URL = authStore.baseUrl
        axios.post(API_URL + 'user/login', this.usuario)
        .then(response => {
            if(response.data.code == 200){
                let datos = {
                    usuario: response.data.data.usuario,
                    id_usuario: response.data.data.id,
                    token: response.data.token
                }
                // guardar datos en el store
                authStore.Login(datos);
                // redireccion a home
                this.$router.push('/inicio');
                return alertas_ajax({
                    tipo: 'simple',
                    icono: 'success',
                    titulo: 'Exito',
                    texto: "Bienvenido " + response.data.data.usuario
                })
            }
        })
        .catch((error) => {
            console.error("Error en la solicitud:", error);
            if (error.response && error.response.status === 400) {
              // Manejar errores 400 específicamente
              return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error',
                texto: error.response.data.data || 'Error desconocido'
              });
            } else {
              // Para otros errores (500, etc.)
              return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error del servidor',
                texto: 'Ocurrió un error inesperado. Inténtalo de nuevo.'
              });
            }
        })
    }
  }
}
</script>
