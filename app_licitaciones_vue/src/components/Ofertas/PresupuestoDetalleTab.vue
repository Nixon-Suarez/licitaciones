<template>
  <div class="tab-pane fade show active" id="ver-presupuesto">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Detalle del Presupuesto {{ oferta.consecutivo }}
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label text-muted">Objeto</label>
              <div class="fw-semibold">
                  {{oferta.objeto}}
              </div>
            </div>
            <div class="col-md-12">
              <label class="form-label text-muted">Descripción / Alcance</label>
              <div class="fw-semibold">
                  {{oferta.descripcion}}
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label text-muted">Moneda</label>
              <div class="fw-semibold">
                  {{oferta.moneda}}
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label text-muted">Presupuesto</label>
              <div class="fw-semibold text-success">
                  {{oferta.presupuesto}}
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label text-muted">Actividad</label>
              <div class="fw-semibold">
                  {{actividad}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "PresupuestoDetalleTab",
  props: {
    oferta: {
      type: Object,
      required: true,
    }
  },
  data() {
    return {
      actividad: null,
    }
  },
  watch: {
    'oferta.actividad_id': {
      handler(newId) {
        if (newId) {
          this.obtenerActividades(newId)
        }
      },
      immediate: true
    }
  },
  methods: {
    obtenerActividades(actividadId){
      if (!actividadId) {
        return
      }
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'actividad/get?id=' + actividadId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.actividad = response.data.data.producto;
              console.log(this.actividad);
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        this.alertaEstado = true;
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
    }
  }
}
</script>
