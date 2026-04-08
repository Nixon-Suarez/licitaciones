<template>
  <!-- Modal Documentos -->
  <div v-if="visible">
    <div class="modal-backdrop fade show" @click="closeModal"></div>
    <div class="modal fade show" id="modalDocumentos" tabindex="-1" aria-labelledby="modalDocumentosLabel" aria-modal="true" role="dialog" style="display: block;">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAdjuntoLabel">Registrar Adjunto</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="registrarAdjunto">
            <input v-model="modulo_ofertas_adjunto" type="hidden" name="modulo_ofertas_adjunto" id="modulo_ofertas_adjunto" value="registrar_adjunto">
            <!-- Título -->
            <div class="mb-3">
              <label for="titulo_adjunto" class="form-label">Titulo</label>
              <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
              <input v-model="documento.titulo" type="text" class="form-control" id="titulo_adjunto" name="titulo_adjunto" required>
            </div>
            <!-- Descripcion -->
            <div class="mb-3">
              <label for="descripcion_Adjunto" class="form-label">Descripcion Gasto</label>
              <label for="descripcion_Adjunto" class="form-label asterisco-obligatorio">*</label>
              <textarea v-model="documento.descripcion" type="text" class="form-control" id="descripcion_Adjunto" name="descripcion_Adjunto" required></textarea>
            </div>
            <!-- Documento -->
            <a id="descargar_gasto" href="" hidden download class="btn btn-success">
                <i class="bi bi-download"></i> Descargar
            </a>
            <div class="mb-3 text-center">
              <label for="gasto_documento" class="form-label">Seleccione un archivo</label>
              <label for="descripcion_Adjunto" class="form-label asterisco-obligatorio">*</label>
              <input @change="onGastoDocumentoChange" class="form-control" type="file" id="gasto_documento" name="gasto_documento" accept=".pdf,.zip" required>
              <div class="form-text">PDF, ZIP. (MAX 10MB)</div>
            </div>
            <!-- Boton (Registrar/Actualizar) -->
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-custom" id="btnSubmitAdjunto">
                  Registrar
              </button>
            </div>
          </form>
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
  name: "DocumentosModal",
  props: {
    oferta_id: {
      type: String,
      required: true
    },
    visible: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      modulo_ofertas_adjunto: 'registrar_adjunto',
      documento: {
        titulo: '',
        descripcion: '',
        archivo_oferta: null,
      }
    }
  },
  methods:{
    closeModal() {
      this.$emit('close');
    },
    registrarAdjunto(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl

      const formData = new FormData()
      formData.append('licitacion_id', this.oferta_id)
      formData.append('titulo', this.documento.titulo)
      formData.append('descripcion', this.documento.descripcion)
      formData.append('archivo_oferta', this.documento.archivo_oferta)

      axios.post(API_URL + 'ofertaDocumento/insert', formData, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token,
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.$emit('saved')
              this.closeModal()
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
    },
    onGastoDocumentoChange(event) {
      this.documento.archivo_oferta = event.target.files[0]
    },
  }
}
</script>
