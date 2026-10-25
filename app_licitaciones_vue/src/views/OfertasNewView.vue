<template>
  <div class="content p-4">
    <h2 class="mb-4">{{ isEditing ? 'Editar Oferta' : 'Nueva Oferta' }}</h2>
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link" :class="{ active: activeTab === 'presupuesto' }" id="presupuesto-tab" type="button" role="tab" @click="activeTab = 'presupuesto'"> Información Básica </button>
      </li>
      <li class="nav-item" role="presentation">
          <button class="nav-link" :class="{ active: activeTab === 'fechas' }" id="fechas-tab" type="button" role="tab" @click="activeTab = 'fechas'">Cronograma</button>
      </li>
      <li v-if="isEditing" class="nav-item">
            <button class="nav-link" :class="{ active: activeTab === 'adjunto' }" id="adjunto-tab" type="button" role="tab" @click="activeTab = 'adjunto'">Documentos </button>
        </li>
    </ul>

    <form @submit.prevent="guardarOferta">
      <div class="tab-content">
        <!-- TAB PRESUPUESTO -->
        <PresupuestoTab :data_ofertas="data_ofertas" :activo="activeTab === 'presupuesto'" @update:data_ofertas="actualizarDatos"/>
        <!-- TAB FECHAS -->
        <FechasTab :data_ofertas="data_ofertas" :activo="activeTab === 'fechas'" @update:data_ofertas="actualizarDatos"/>
        <!-- TAB DOCUMENTOS -->
        <DocumentosTab :oferta="data_ofertas" :activo="activeTab === 'adjunto'" v-if="isEditing"/>
      </div>

      <!-- BOTÓN -->
      <div class="mb-3 mt-2 d-flex justify-content-center">
          <button type="submit" id="btnGuardarTodo" class="btn btn-success" :disabled="!formularioValido">
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

const datosIniciales = () => ({
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
});

export default {
  name: "OfertasNewView",
  data() {
    return {
      isEditing: false,
      ofertaId: null,
      activeTab: 'presupuesto',
      data_ofertas: datosIniciales()
    }
  },
  watch: {
    '$route'() {
      this.inicializar()
    }
  },
  computed: {
    formularioValido(){
      const d = this.data_ofertas
      if (!d.objeto || !d.objeto.toString().trim()) return false
      if (!d.descripcion || !d.descripcion.toString().trim()) return false
      if (!d.moneda) return false
      if (d.presupuesto === null || d.presupuesto === '' || isNaN(d.presupuesto) || Number(d.presupuesto) <= 0) return false
      if (!d.actividad_id) return false
      if (!d.fecha_inicio || !d.hora_inicio || !d.fecha_cierre || !d.hora_cierre) return false
      if (String(d.objeto).length > 150) return false
      if (String(d.descripcion).length > 400) return false
      try {
        const inicio = new Date(d.fecha_inicio + 'T' + d.hora_inicio)
        const fin = new Date(d.fecha_cierre + 'T' + d.hora_cierre)
        if (fin <= inicio) return false
      } catch(e) {
        return false
      }
      return true
    }
  },
  components: {
    PresupuestoTab,
    FechasTab,
    DocumentosTab
  },
  methods: {
    inicializar() {
      this.ofertaId = this.$route.params.id;
      this.isEditing = !!this.ofertaId;
      this.activeTab = 'presupuesto';
      this.data_ofertas = datosIniciales();
      if (this.isEditing) {
        this.cargarOferta();
      }
    },
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
    this.inicializar();
  }
}
</script>
