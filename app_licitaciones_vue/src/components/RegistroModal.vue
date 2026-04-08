<template>
  <!-- Modal de Registro -->
  <div v-if="modalCreateUser">
    <div class="modal-backdrop fade show" @click="closeModal"></div>
    <div class="modal fade show" id="modalRegister" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-modal="true" role="dialog" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalRegisterLabel">Registro</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="register">
            <!-- Usuario -->
            <div class="mb-3">
                <label for="register_usuario" class="form-label">Usuario</label>
                <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                <input v-model="usuario.register_usuario" type="text" class="form-control" id="register_usuario" name="register_usuario"
                    pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
            </div>
            <!-- Nombre -->
            <div class="mb-3">
                <label for="register_nombre" class="form-label">Nombre</label>
                <label for="register_nombre" class="form-label asterisco-obligatorio">*</label>
                <input v-model="usuario.register_nombre" type="text" class="form-control" id="register_nombre" name="register_nombre"
                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="100" required>
            </div>
            <!-- Apellido -->
            <div class="mb-3">
                <label for="register_apellido" class="form-label">Apellido</label>
                <label for="register_apellido" class="form-label asterisco-obligatorio">*</label>
                <input v-model="usuario.register_apellido" type="text" class="form-control" id="register_apellido" name="register_apellido"
                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="100" required>
            </div>
            <!-- Clave -->
            <div class="mb-3">
                <label for="register_clave1" class="form-label">Clave</label>
                <label for="register_clave1" class="form-label asterisco-obligatorio">*</label>
                <input v-model="usuario.register_clave1" type="password" class="form-control" id="register_clave1" name="register_clave1"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
            </div>
            <!-- Clave confirmacion -->
            <div class="mb-3">
                <label for="register_clave2" class="form-label">Confirmacion Clave</label>
                <label for="register_clave2" class="form-label asterisco-obligatorio">*</label>
                <input v-model="usuario.register_clave2" type="password" class="form-control" id="register_clave2" name="register_clave2"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
            </div>
            <!-- Botón Registrase-->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-custom">Registrar</button>
            </div>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
export default {
  name: "RegistroModal",
  props: {
    visible: {
      type: Boolean,
      default: false
    }
  },
  data(){
      return {
        usuario: {
          register_usuario: '',
          register_nombre: '',
          register_apellido: '',
          register_clave1: '',
          register_clave2: '',
        },
        modalCreateUser: this.visible,
      }
  },
  watch: {
    visible(newVal) {
      this.modalCreateUser = newVal;
    }
  },
  methods: {
    closeModal() {
      this.modalCreateUser = false;
      this.$emit('close');
    },
    register(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.post(API_URL + 'user/insert', this.usuario)
      .then(response => {
          if(response.data.code == 200){
              // si se creó el usuario, cerrar modal y redireccionar a login
              this.$emit('close');
              this.$router.push('/');
          }
      })
      .catch((error) => {
          console.error("Error en la solicitud:", error);
          this.alertaEstado = true;
          if(error.response.status === 401){
              this.alertaMensaje = error.response.data.data;
          }else{
              this.alertaMensaje = "Ocurrió un error en el servidor";
          }
          setTimeout(() => {
              this.alertaEstado = false;
          }, 1500);
      })
    }
  }
}
</script>
