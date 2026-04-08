<template>
  <div class="tab-pane fade" id="adjunto">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-dark text-white">
            Documentos asociados {{oferta.consecutivo}}
        </div>
        <div class="card-body">
          <button type="button" class="btn btn-primary" @click="modalDocumentos = true">Agregar documento</button>
          <div v-if="documentos.length > 0" class="row g-3 mt-1">
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
                  <label class="form-label text-muted mb-1">Archivo</label>
                      <div v-if="documento.archivo != ''" class="d-flex gap-2 align-items-center flex-wrap">
                          <a href="" class="btn btn-outline-primary btn-sm" download> <i class="bi bi-download"></i> Descargar </a>
                          <form @submit.prevent="eliminarDocumento(documento.id)">
                              <button type="submit" class="btn btn-outline-danger btn-sm">
                                  <i class="bi bi-trash"></i> Eliminar
                              </button>
                          </form>
                      </div>
                      <div v-else>
                        <i class="text-danger">No se encontró el archivo</i>
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
    <!-- MODAL AGREGAR/EDITAR DOCUMENTO -->
    <DocumentosModal :visible="modalDocumentos" :oferta_id="oferta.id" @close="modalDocumentos = false" @saved="getOfertaDocumentos"/>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import DocumentosModal from './DocumentosModal.vue';
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "DocumentosTab",
  props: {
    oferta: {
      type: Object,
      required: true
    }
  },
  components: {
    DocumentosModal
  },
  data() {
    return {
      modalDocumentos: false,
      documentos: [],
      alerta_exitosa: false,
      alerta_vacio: false,
      alerta_fallido: false
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
      .catch((error) => {
          console.error("Error en la solicitud:", error);
          this.alerta_fallido = true
          setTimeout(() => {
            this.alerta_fallido = false
          }, 3000);
      })
    },
    eliminarDocumento(documento_id){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl

      // Guardar índice para posible revertir
      const indexDocumento = this.documentos.findIndex(doc => doc.id === documento_id)
      const documentoEliminado = this.documentos[indexDocumento]

      // Optimistic update: eliminar del array inmediatamente
      this.documentos.splice(indexDocumento, 1)

      axios.delete(API_URL + 'ofertaDocumento/delete/' + documento_id, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
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

        // Revertir cambio si hay error
        this.documentos.splice(indexDocumento, 0, documentoEliminado)

        if(error.response && error.response.status === 400 || error.response.status === 404){
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
