<template>
  <div class="content p-4">
  <!-- NAV TABS -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ver-presupuesto">
          Presupuesto
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ver-fechas">
          Periodo de ejecución
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ver-adjunto">
          Documentos
      </button>
    </li>
  </ul>
  <div class="tab-content">
    <!-- TAB PRESUPUESTO -->
    <PresupuestoDetalleTab :oferta="oferta"/>
    <!-- TAB FECHAS -->
    <FechasDetalleTab :oferta="oferta"/>
    <!-- TAB DOCUMENTOS -->
    <DocumentosTab :oferta="oferta"/>
  </div>
  <!-- BOTONES -->
  <div class="mb-3 mt-2 d-flex justify-content-between align-items-center">
      <a class="btn btn-primary">
        <router-link :to="{ name: 'ofertasEditar', params: { id: oferta.id } }" class="dropdown-item">Editar</router-link>
      </a>
  </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'
import DocumentosTab from "@/components/Ofertas/DocumentosDetalleTab.vue";
import PresupuestoDetalleTab from "@/components/Ofertas/PresupuestoDetalleTab.vue";
import FechasDetalleTab from "@/components/Ofertas/FechasDetalleTab.vue";
export default {
  name: "OfertasDetalleView",
  data() {
    return {
      ofertaId: null,
      oferta: {},
    }
  },
  components: {
    DocumentosTab,
    PresupuestoDetalleTab,
    FechasDetalleTab
  },
  methods: {
    obtenerOferta(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'oferta/find/' + this.ofertaId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.oferta = response.data.data;
              console.log(this.oferta);
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        this.alertaEstado = true;
        if(error.response.status == 404){
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
    this.obtenerOferta();
  }
}
</script>
