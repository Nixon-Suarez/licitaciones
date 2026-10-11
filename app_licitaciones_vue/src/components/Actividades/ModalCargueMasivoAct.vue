<template>
  <!-- Modal Cargue Masivo de Actividades -->
  <Teleport to="body">
    <div v-if="modalCargueMasivo">
      <div class="modal-backdrop fade show" @click="closeModal"></div>
      <div class="modal fade show" id="modalCargueMasivoAct" tabindex="-1" aria-labelledby="modalCargueMasivoActLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCargueMasivoActLabel">Cargue masivo de actividades</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <div class="alert alert-info" role="alert">
                <p class="mb-1"><strong>Formato requerido (.xlsx)</strong></p>
                <p class="mb-1">La primera fila son los encabezados. El orden de las columnas debe ser:</p>
                <ol class="mb-1 ps-3">
                  <li>codigo_segmento (numérico)</li>
                  <li>segmento (texto)</li>
                  <li>codigo_familia (numérico)</li>
                  <li>familia (texto)</li>
                  <li>codigo_clase (numérico)</li>
                  <li>clase (texto)</li>
                  <li>codigo_producto (numérico)</li>
                  <li>producto (texto)</li>
                </ol>
                <p class="mb-2">Si una fila tiene error o el código de producto ya existe, no se carga ninguna.</p>
                <button type="button" class="btn btn-sm btn-success" @click="descargarPlantilla">
                  <i class="bi bi-download"></i> Descargar plantilla
                </button>
              </div>

              <form @submit.prevent="subirArchivo">
                <div class="mb-3">
                  <label for="archivo_actividades" class="form-label">Seleccione el archivo Excel</label>
                  <label for="archivo_actividades" class="form-label asterisco-obligatorio">*</label>
                  <input class="form-control" type="file" id="archivo_actividades" name="archivo_actividades" accept=".xlsx" @change="seleccionarArchivo" required>
                  <div class="form-text">Excel .xlsx (MAX 10MB)</div>
                </div>

                <div v-if="errores.length > 0" class="mb-3">
                  <div class="alert alert-danger mb-2" role="alert">
                    {{ resumenError }}
                  </div>
                  <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped mb-0">
                      <thead>
                        <tr>
                          <th style="width: 80px;">Fila</th>
                          <th>Motivo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(error, index) in errores" :key="index">
                          <td>{{ error.fila }}</td>
                          <td>{{ error.motivo }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-custom" :disabled="cargando">
                    <span v-if="cargando" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    {{ cargando ? 'Cargando...' : 'Cargar actividades' }}
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
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "ModalCargueMasivoAct",
  props: {
    visible: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      modalCargueMasivo: this.visible,
      archivo: null,
      cargando: false,
      errores: [],
      resumenError: '',
    }
  },
  watch: {
    visible(newVal) {
      this.modalCargueMasivo = newVal;
      if (newVal) {
        this.resetEstado();
      }
    }
  },
  methods: {
    resetEstado() {
      this.archivo = null;
      this.cargando = false;
      this.errores = [];
      this.resumenError = '';
      const input = document.getElementById('archivo_actividades');
      if (input) {
        input.value = '';
      }
    },
    closeModal() {
      this.modalCargueMasivo = false;
      this.resetEstado();
      this.$emit('close');
    },
    seleccionarArchivo(event) {
      this.archivo = event.target.files[0] || null;
      this.errores = [];
      this.resumenError = '';
    },
    descargarPlantilla() {
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'actividad/plantilla', {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        },
        responseType: 'blob'
      })
      .then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'plantilla_actividades.xlsx')
        document.body.appendChild(link)
        link.click()
        link.remove()
        window.URL.revokeObjectURL(url)
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        return alertas_ajax({
          tipo: 'simple',
          icono: 'error',
          titulo: 'Error',
          texto: "No se pudo descargar la plantilla, intente nuevamente",
        });
      })
    },
    subirArchivo() {
      if (!this.archivo) {
        return alertas_ajax({
          tipo: 'simple',
          icono: 'warning',
          titulo: 'Atención',
          texto: "Debe seleccionar un archivo Excel (.xlsx)",
        });
      }
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl

      const formData = new FormData()
      formData.append('archivo_actividades', this.archivo)

      this.cargando = true;
      this.errores = [];
      this.resumenError = '';

      axios.post(API_URL + 'actividad/masivo', formData, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token,
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
          if (response.data.code == 201) {
              this.$emit('cargado')
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
        if (error.response && error.response.status === 400) {
          const data = error.response.data
          if (Array.isArray(data.errores) && data.errores.length > 0) {
            this.errores = data.errores
            this.resumenError = data.data
            return
          }
          return alertas_ajax({
              tipo: 'simple',
              icono: 'error',
              titulo: 'Error',
              texto: data.data,
          });
        }
        return alertas_ajax({
            tipo: 'simple',
            icono: 'error',
            titulo: 'Error',
            texto: "Ocurrió un error en el servidor",
        });
      })
      .finally(() => {
        this.cargando = false;
      })
    }
  }
}
</script>
