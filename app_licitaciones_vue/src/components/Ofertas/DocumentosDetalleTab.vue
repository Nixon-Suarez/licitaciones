<template>
  <div class="tab-pane fade" id="ver-adjunto">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-dark text-white">
            Documentos asociados {{oferta.consecutivo}}
        </div>
        <div class="card-body">
          <div v-if="documentos.length > 0" class="row g-3">
            <div v-for="(documento, index) in documentos" :key="index" class="col-md-12 border rounded p-3">
                <!-- TÍTULO -->
                <div class="mb-2">
                    <label class="form-label text-muted mb-0">Título</label>
                    <div class="fw-semibold">
                        {{documento.titulo}}
                    </div>
                </div>
                <!-- DESCRIPCIÓN -->
                <div class="mb-2">
                    <label class="form-label text-muted mb-0">Descripción</label>
                    <div>
                        {{documento.descripcion}}
                    </div>
                </div>
                <!-- ARCHIVO -->
                <div class="mb-2">
                    <label class="form-label text-muted mb-0">Archivo</label>
                    <div>
                        <a v-if="documento.archivo != ''"
                            class="btn btn-outline-primary btn-sm"
                            download>
                            <i class="bi bi-download"></i>
                            Descargar archivo
                        </a>
                        <p v-else > — </p>
                    </div>
                </div>
            </div>
          </div>
          <div v-else class="text-center text-muted py-4">
              <i class="bi bi-folder-x fs-3"></i>
              <p class="mt-2 mb-0">No hay documentos asociados</p>
          </div>
        </div>
    </div>
    <div v-if="alerta_exitosa" class="alert alert-success" role="alert">
      Documentos consultados con éxito
    </div>
    <div v-if="alerta_fallido" class="alert alert-error" role="alert">
      Consulta de documentos fallida, por favor intente nuevamente
    </div>
    <div v-if="alerta_vacio" class="alert alert-warning" role="alert">
      Oferta sin documentos asociados
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
export default {
  name: "DocumentosDetalleTab",
  props: {
    oferta: {
      type: Object,
      required: true
    }
  },
  watch: {
    oferta: {
      handler(newOferta) {
        if (newOferta && newOferta.id) {
          this.getOfertaDocumentos();
        }
      },
      immediate: true
    }
  },
  data() {
    return {
      modalDocumentos: false,
      documentos: {},
      alerta_exitosa: false,
      alerta_fallido: false,
      alerta_vacio: false
    }
  },
  methods: {
    getOfertaDocumentos(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'ofertaDocumento/get?' + 'id=' + this.oferta.id, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.documentos = response.data.data
              this.alerta_exitosa = true
              setTimeout(() => {
                this.alerta_exitosa = false
              }, 3000);
          }
          if(response.data.code == 204){
              this.documentos = response.data.data
              this.alerta_vacio = true
              setTimeout(() => {
                this.alerta_vacio = false
              }, 3000);
          }
      })
      .catch(function(error){
          console.error("Error en la solicitud:", error);
          this.alerta_fallido = true
          setTimeout(() => {
            this.alerta_fallido = false
          }, 3000);
      })
    }
  }
}
</script>
