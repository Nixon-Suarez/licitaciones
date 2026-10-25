<template>
  <Teleport to="body">
    <div v-if="modalCreateUser">
      <div class="modal-backdrop fade show" @click="closeModal"></div>
      <div
        id="modalRegister"
        class="modal fade show"
        tabindex="-1"
        aria-labelledby="modalRegisterLabel"
        aria-modal="true"
        role="dialog"
        style="display: block;"
      >
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <h5 id="modalRegisterLabel" class="modal-title">Crear una cuenta</h5>
                <p class="text-secondary mb-0 mt-1">Completa tus datos para registrarte.</p>
              </div>
              <button type="button" class="btn-close" aria-label="Cerrar" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="register">
                <div v-if="errorMessage" class="alert alert-danger" role="alert" aria-live="assertive">
                  {{ errorMessage }}
                </div>

                <section aria-labelledby="datosPersonalesTitle">
                  <h6 id="datosPersonalesTitle" class="mb-3">Datos personales</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="register_usuario" class="form-label">
                        Usuario <span class="asterisco-obligatorio" aria-hidden="true">*</span>
                      </label>
                      <input
                        id="register_usuario"
                        v-model="usuario.register_usuario"
                        type="text"
                        class="form-control"
                        name="register_usuario"
                        pattern="[a-zA-Z0-9]{4,20}"
                        minlength="4"
                        maxlength="20"
                        autocomplete="username"
                        aria-describedby="usuarioAyuda"
                        required
                      >
                      <div id="usuarioAyuda" class="form-text">De 4 a 20 caracteres, solo letras y números.</div>
                    </div>
                    <div class="col-md-6">
                      <label for="register_nombre" class="form-label">
                        Nombre <span class="asterisco-obligatorio" aria-hidden="true">*</span>
                      </label>
                      <input
                        id="register_nombre"
                        v-model="usuario.register_nombre"
                        type="text"
                        class="form-control"
                        name="register_nombre"
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                        minlength="3"
                        maxlength="40"
                        autocomplete="given-name"
                        required
                      >
                    </div>
                    <div class="col-md-6">
                      <label for="register_apellido" class="form-label">
                        Apellido <span class="asterisco-obligatorio" aria-hidden="true">*</span>
                      </label>
                      <input
                        id="register_apellido"
                        v-model="usuario.register_apellido"
                        type="text"
                        class="form-control"
                        name="register_apellido"
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                        minlength="3"
                        maxlength="40"
                        autocomplete="family-name"
                        required
                      >
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                      <p class="form-text mb-2">Usa tu nombre y apellido tal como quieres que aparezcan en tu cuenta.</p>
                    </div>
                  </div>
                </section>

                <hr class="my-4">

                <section aria-labelledby="seguridadTitle">
                  <h6 id="seguridadTitle" class="mb-1">Seguridad</h6>
                  <p class="form-text mb-3">La clave debe tener entre 7 y 100 caracteres. Se permiten letras, números y los símbolos $, @, . y -.</p>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="register_clave1" class="form-label">
                        Clave <span class="asterisco-obligatorio" aria-hidden="true">*</span>
                      </label>
                      <input
                        id="register_clave1"
                        v-model="usuario.register_clave1"
                        :type="showPasswords ? 'text' : 'password'"
                        class="form-control"
                        name="register_clave1"
                        pattern="[a-zA-Z0-9$@.-]{7,100}"
                        minlength="7"
                        maxlength="100"
                        autocomplete="new-password"
                        required
                      >
                    </div>
                    <div class="col-md-6">
                      <label for="register_clave2" class="form-label">
                        Confirmar clave <span class="asterisco-obligatorio" aria-hidden="true">*</span>
                      </label>
                      <input
                        id="register_clave2"
                        v-model="usuario.register_clave2"
                        :type="showPasswords ? 'text' : 'password'"
                        class="form-control"
                        :class="{ 'is-invalid': usuario.register_clave2 && !passwordsMatch }"
                        name="register_clave2"
                        pattern="[a-zA-Z0-9$@.-]{7,100}"
                        minlength="7"
                        maxlength="100"
                        autocomplete="new-password"
                        :aria-describedby="usuario.register_clave2 ? 'passwordMatchStatus' : undefined"
                        required
                      >
                      <div
                        v-if="usuario.register_clave2"
                        id="passwordMatchStatus"
                        class="form-text"
                        :class="passwordsMatch ? 'text-success' : 'text-danger'"
                        aria-live="polite"
                      >
                        {{ passwordsMatch ? 'Las claves coinciden.' : 'Las claves no coinciden.' }}
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input id="showPasswords" v-model="showPasswords" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="showPasswords">Mostrar claves</label>
                      </div>
                    </div>
                  </div>
                </section>

                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-custom" :disabled="isSubmitting">
                    {{ isSubmitting ? 'Registrando...' : 'Crear cuenta' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
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
        showPasswords: false,
        isSubmitting: false,
        errorMessage: '',
      }
  },
  watch: {
    visible(newVal) {
      this.modalCreateUser = newVal;
      if (newVal) {
        this.limpiarFormulario();
      }
    }
  },
  computed: {
    passwordsMatch() {
      return this.usuario.register_clave1 === this.usuario.register_clave2;
    }
  },
  methods: {
    limpiarFormulario() {
      this.usuario = {
        register_usuario: '',
        register_nombre: '',
        register_apellido: '',
        register_clave1: '',
        register_clave2: '',
      };
      this.showPasswords = false;
      this.errorMessage = '';
    },
    closeModal() {
      this.modalCreateUser = false;
      this.$emit('close');
    },
    async register() {
      if (this.isSubmitting) return;
      if (!this.passwordsMatch) {
        this.errorMessage = 'Las claves no coinciden.';
        return;
      }

      this.errorMessage = '';
      this.isSubmitting = true;
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      try {
        const response = await axios.post(API_URL + 'user/insert', this.usuario);
        if (response.data.code === 200) {
          this.closeModal();
          this.$router.push('/');
          return;
        }

        this.errorMessage = response.data.data || 'No se pudo registrar la cuenta.';
      } catch (error) {
        console.error('Error en la solicitud:', error);
        this.errorMessage = error.response?.data?.data || 'Ocurrió un error al registrar la cuenta. Inténtalo de nuevo.';
      } finally {
        this.isSubmitting = false;
      }
    }
  }
}
</script>
