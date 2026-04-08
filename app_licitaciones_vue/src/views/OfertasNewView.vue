<template>
  <div class="content p-4">
    <h2 class="mb-4">{{ isEditing ? 'Editar Oferta' : 'Nueva Oferta' }}</h2>
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="presupuesto-tab" data-bs-toggle="tab" data-bs-target="#presupuesto" type="button" role="tab"> Presupuesto </button>
      </li>
      <li class="nav-item" role="presentation">
          <button class="nav-link" id="fechas-tab" data-bs-toggle="tab" data-bs-target="#fechas" type="button" role="tab">Periodo de ejecución</button>
      </li>
      <li v-if="isEditing" class="nav-item">
            <button class="nav-link" id="adjunto-tab" data-bs-toggle="tab" data-bs-target="#adjunto">Documentos </button>
        </li>
    </ul>

    <form @submit.prevent="guardarOferta">
      <div class="tab-content">
        <!-- TAB PRESUPUESTO -->
        <PresupuestoTab :data_ofertas="data_ofertas" @update:data_ofertas="actualizarDatos"/>
        <!-- TAB FECHAS -->
        <FechasTab :data_ofertas="data_ofertas" @update:data_ofertas="actualizarDatos"/>
        <!-- TAB DOCUMENTOS -->
        <DocumentosTab :oferta="data_ofertas" v-if="isEditing"/>
      </div>

      <!-- BOTÓN -->
      <div class="mb-3 mt-2 d-flex justify-content-center">
          <button type="submit" id="btnGuardarTodo" class="btn btn-success">
              {{ isEditing ? 'Actualizar' : 'Guardar' }}
          </button>
      </div>
    </form>
</div>
</template>

<script>
import PresupuestoTab from "@/components/Ofertas/PresupuestoTab.vue";
import FechasTab from "@/components/Ofertas/FechasTab.vue";
import DocumentosTab from "@/components/Ofertas/DocumentosTab.vue";
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "OfertasNewView",
  data() {
    return {
      isEditing: false,
      ofertaId: null,
      data_ofertas: {
        id: null,
        objeto: '',
        descripcion: '',
        moneda: '',
        presupuesto: null,
        actividad_id: "",
        fecha_inicio: "",
        hora_inicio: "",
        fecha_cierre: "",
        hora_cierre: ""
      }
    }
  },
  components: {
    PresupuestoTab,
    FechasTab,
    DocumentosTab
  },
  methods: {
    actualizarDatos(datosActualizados) {
      // Sincronizar los datos del padre con los datos actualizados del hijo
      this.data_ofertas = { ...this.data_ofertas, ...datosActualizados }
    },
    cargarOferta(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'oferta/find/' + this.ofertaId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.data_ofertas = response.data.data;
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
    guardarOferta(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      const method = this.isEditing ? 'put' : 'post'
      const url = this.isEditing ? API_URL + 'oferta/update' : API_URL + 'oferta/insert'
      axios[method](url, this.data_ofertas, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == (this.isEditing ? 200 : 201)){
              this.$router.push('/ofertasList');
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
        if(error.response && error.response.status === 400){
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
    this.ofertaId = this.$route.params.id;
    if (this.ofertaId) {
      this.isEditing = true;
      this.cargarOferta();
    }
  }
}
</script>
