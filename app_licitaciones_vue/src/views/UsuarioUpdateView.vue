<template>
  <div class="content p-4">
    <h2 class="mb-4">Visualizar usuario</h2>
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link" :class="{ active: activeTab === 'usuario' }" id="usuario-tab" type="button" role="tab" @click="activeTab = 'usuario'"> Información Usuario </button>
      </li>
    </ul>

    <form @submit.prevent="Actualizarusuario">
      <div class="tab-content">
        <!-- TAB usuario -->
        <usuarioTab :data_usuario="data_usuario" :activo="activeTab === 'usuario'"  @update:data_usuario="actualizarDatos"/>
      </div>

      <!-- BOTÓN -->
      <div class="mb-3 mt-2 d-flex justify-content-center">
          <button v-if="!isEditing || puedeEditar" type="submit" id="btnGuardarTodo" class="btn btn-success" :disabled="!formularioValido">
              Actualizar
          </button>
      </div>
    </form>
</div>
</template>

<script>
import usuarioTab from "@/components/usuarios/UsuarioTab.vue";
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'

export default {
  name: "usuarioUpdateView",
  data() {
    return {
      isEditing: false,
      usuarioId: null,
      activeTab: 'usuario',
      data_usuario: {
        id_usuario: null,
        nombre: '',
        apellido: '',
        usuario: '',
        clave: '',
        clave2: '',
      }
    }
  },
  watch: {
    '$route'() {
      this.inicializar()
    }
  },
  computed: {
    formularioValido(){
      const d = this.data_usuario
      if (!d.id_usuario) return false
      if (!d.nombre || !d.nombre.toString().trim()) return false
      if (!d.apellido || !d.apellido.toString().trim()) return false
      if (!d.usuario || !d.usuario.toString().trim()) return false

      const clave = d.clave ?? ''
      const clave2 = d.clave2 ?? ''
      if (clave !== '' || clave2 !== '') {
        if (clave === '' || clave2 === '') return false
        if (clave !== clave2) return false
      }

      return true
    }
  },
  components: {
    usuarioTab
  },
  methods: {
    inicializar() {
      this.usuarioId = this.$route.params.id;
      this.activeTab = 'usuario';
      this.cargarusuario();
    },
    actualizarDatos(datosActualizados) {
      // Sincronizar los datos del padre con los datos actualizados del hijo
      this.data_usuario = { ...this.data_usuario, ...datosActualizados }
    },
    cargarusuario(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'user/get', {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              const usuario = response.data.data;
              const authStore = useAuthStore()
              this.puedeEditar = Number(authStore.perfil) === 1
                || Number(authStore.id_usuario) === Number(usuario.usuario_id)
              this.data_usuario = {
                id_usuario: usuario.id,
                nombre: usuario.usuario_nombre,
                apellido: usuario.usuario_apellido,
                usuario: usuario.usuario_usuario,
                clave: '',
                clave2: '',
              };
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        if(error.response && error.response.status === 404){
            return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error',
                texto: error.response.data.data,
            });
        }else{
            return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error',
                texto: "Ocurrió un error en el servidor",
            });
        }
      })
    },
    Actualizarusuario(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      const method = 'put'
      const url =  API_URL + 'user/update'
      axios[method](url, this.data_usuario, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              if (Number(authStore.id_usuario) === Number(this.data_usuario.id_usuario)) {
                authStore.ActualizarUsuario({ usuario: this.data_usuario.usuario })
              }
              this.$router.push('/inicio');
              return alertas_ajax({
                  tipo: 'simple',
                  icono: 'success',
                  titulo: 'Éxito',
                  texto: response.data.data,
              })
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        this.alertaEstado = true;
        if(error.response && (error.response.status === 400 || error.response.status === 403)){
            return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error',
                texto: error.response.data.data,
            });
        }else{
            return alertas_ajax({
                tipo: 'simple',
                icono: 'error',
                titulo: 'Error',
                texto: "Ocurrió un error en el servidor",
            });
        }
      })
    }
  },
  created() {
    this.inicializar();
  }
}
</script>
