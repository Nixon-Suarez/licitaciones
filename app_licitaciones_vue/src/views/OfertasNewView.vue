<template>
  <div class="content p-4">
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="presupuesto-tab" data-bs-toggle="tab" data-bs-target="#presupuesto" type="button" role="tab"> Presupuesto </button>
      </li>
      <li class="nav-item" role="presentation">
          <button class="nav-link" id="fechas-tab" data-bs-toggle="tab" data-bs-target="#fechas" type="button" role="tab">Periodo de ejecución</button>
      </li>
    </ul>

    <form @submit.prevent="guardarOferta">
      <div class="tab-content">
        <!-- TAB PRESUPUESTO -->
        <PresupuestoTab :data_ofertas="data_ofertas" @update:data_ofertas="actualizarDatos"/>
        <!-- TAB FECHAS -->
        <FechasTab :data_ofertas="data_ofertas" @update:data_ofertas="actualizarDatos"/>
      </div>

      <!-- BOTÓN -->
      <div class="mb-3 mt-2 d-flex justify-content-center">
          <button type="submit" id="btnGuardarTodo" class="btn btn-success">
              Guardar todo
          </button>
      </div>
    </form>
</div>
</template>

<script>
import PresupuestoTab from "@/components/Ofertas/PresupuestoTab.vue";
import FechasTab from "@/components/Ofertas/FechasTab.vue";
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "OfertasNewView",
  data() {
    return {
      data_ofertas: {
        oferta_id: '',
        objeto: '',
        descripcion: '',
        moneda: '',
        presupuesto: null,
        actividad: "",
        fecha_inicio: "",
        hora_inicio: "",
        fecha_cierre: "",
        hora_cierre: ""
      }
    }
  },
  components: {
    PresupuestoTab,
    FechasTab
  },
  methods: {
    actualizarDatos(datosActualizados) {
      // Sincronizar los datos del padre con los datos actualizados del hijo
      this.data_ofertas = { ...this.data_ofertas, ...datosActualizados }
    },
    guardarOferta(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.post(API_URL + 'oferta/insert', this.data_ofertas, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 201){
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
        if(error.response.status === 400){
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
  }
}
</script>
