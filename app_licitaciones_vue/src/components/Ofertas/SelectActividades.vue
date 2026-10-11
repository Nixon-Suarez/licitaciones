<!-- <template> -->
  <!-- <div class="mb-4">
      <label for="actividad" class="form-label">Actividad</label>
      <select class="form-select" v-model="actividad_selec" name="actividad" required>
          <option value="">Seleccione una actividad</option>
          <option v-for="actividad in actividades" :key="actividad.id" :value="actividad.id">{{ actividad.producto }}</option>
      </select>
  </div> -->
<!-- </template> -->
<template>
  <div class="mb-4">
    <label for="actividad" class="form-label">Actividad</label>
    <input readonly @click="abrirModal" type="text" class="form-select" style="cursor: pointer;" v-model="actividad_nombre" placeholder="Seleccione una actividad" required>
  </div>
  <!-- Modal Actividades -->
  <Teleport to="body">
  <div v-if="visible">
    <div class="modal-backdrop fade show" @click="closeModal"></div>
    <div class="modal fade show" id="modalActividades" tabindex="-1" aria-labelledby="modalActividadesLabel" aria-modal="true" role="dialog" style="display: block;">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAdjuntoLabel">Selecciona la Actividad</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <!-- Formulario de búsqueda -->
          <div class="row">
            <div class="col-md-10 mx-auto">
              <form @submit.prevent="getActividades" class="row g-3" >
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <!-- Segmento -->
                <div class="col-md-5">
                    <label for="txt_segmento" class="form-label">Segmento</label>
                    <input v-model="txt_segmento" id="txt_segmento" type="text" name="txt_segmento" class="form-control rounded-pill" placeholder="¿Qué estás buscando?" maxlength="30">
                </div>
                <!-- Producto -->
                <div class="col-md-5">
                    <label for="txt_producto" class="form-label">Producto</label>
                    <input v-model="txt_producto" id="txt_producto" type="text" name="txt_producto" class="form-control rounded-pill" placeholder="¿Qué estás buscando?" maxlength="400">
                </div>
                <!-- Botón -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-pill w-100">
                        Buscar
                    </button>
                </div>
              </form>
            </div>
          </div>
          <div>
            <br>
            <!-- Tabla -->
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="custom-header text-center">
                        <tr>
                            <th>#</th>
                            <th>Clase</th>
                            <th>Producto</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(actividad, index) in Actividades" :key="actividad.id" class="text-center">
                          <td>{{ pag_inicio + index }}</td>
                          <td>{{ actividad.clase}}</td>
                          <td>{{ actividad.producto}}</td>
                          <td>
                            <button @click="seleccionarActividad(actividad)" class="btn btn-primary btn-sm">Seleccionar</button>
                          </td>
                      </tr>
                      <tr v-if="Actividades.length === 0" class="text-center">
                          <td colspan="8">
                              No hay registros en el sistema
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>
            <p class="text-secondary text-center mb-3">Mostrando Actividades <strong>{{pag_inicio}}</strong> al <strong>{{pag_final}}</strong> de un <strong>total de {{consulta_total}} </strong></p>
          </div>
          <!-- Paginador -->
          <div class="d-flex justify-content-center mt-3 mb-3">
            <button @click="previousPage" :disabled="current_page <= 1" class="btn btn-secondary me-2 rounded-pill">Anterior</button>
            <span class="align-self-center mx-3">Página {{ current_page }} de {{ numero_paginas }}</span>
            <button @click="nextPage" :disabled="current_page >= numero_paginas" class="btn btn-secondary ms-2 rounded-pill">Siguiente</button>
          </div>
          <div v-if="alerta_exitosa" class="alert alert-success" role="alert">
            Consulta realizada con éxito
          </div>
          <div v-if="alerta_fallido" class="alert alert-error" role="alert">
            Consulta fallida, por favor intente nuevamente
          </div>
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
export default {
  name: "SelectActividades",
  props: {
    data_ofertas: {
      type: Object,
      required: true
    },
  },
  emits: ['update:data_ofertas'],
  data() {
    return {
      visible: false,
      modulo_ofertas: 'registrar_oferta',
      actividad: "",
      actividad_nombre: "",
      txt_segmento: '',
      txt_producto: '',
      Actividades: [],
      current_page: 0,
      registros_por_pagina: 10,
      pag_inicio : 0,
      consulta_total : 0,
      numero_paginas : 0,
      alerta_exitosa: false,
      alerta_fallido: false,
    }
  },
  computed: {
    actividad_selec: {
      get() { return this.data_ofertas.actividad_id},
      set(val) { this.$emit('update:data_ofertas', { actividad_id: val }) }
    },
    pag_final: {
      get() { return this.current_page * this.registros_por_pagina}
    },
  },
  watch: {
    'data_ofertas.actividad_id': {
      handler(newId) {
        if (newId && !this.actividad_nombre) {
          this.getActividadNombre(newId)
        }
      },
      immediate: true
    }
  },
  methods: {
    getActividadNombre(actividadId) {
      if (!actividadId) { return }
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'actividad/get?id=' + actividadId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
        if (response.data.code == 200) {
          this.actividad_nombre = response.data.data.producto
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error)
      })
    },
    abrirModal() {
      this.txt_segmento = '';
      this.txt_producto = '';
      this.current_page = 0;
      this.getActividades();
      this.visible = true;
    },
    seleccionarActividad(actividad) {
      this.actividad_selec = actividad.id;
      this.actividad_nombre = actividad.producto;
      this.closeModal();
    },
    closeModal() {
      this.visible = false;
    },
    getActividades(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'actividad/list?' + 'pagina=' + this.current_page + '&registros=' + this.registros_por_pagina + '&segmento=' + this.txt_segmento + '&producto=' + this.txt_producto, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.Actividades = response.data.data.datos
              this.consulta_total = response.data.data.total
              this.numero_paginas = response.data.data.paginas
              this.current_page = response.data.data.pagina_actual
              this.pag_inicio = response.data.data.inicio
              this.alerta_exitosa = true
              setTimeout(() => {
                this.alerta_exitosa = false
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
    },
    previousPage() {
      if (this.current_page > 1) {
        this.current_page--;
        this.getActividades();
      }
    },
    nextPage() {
      if (this.current_page < this.numero_paginas) {
        this.current_page++;
        this.getActividades();
      }
    },
  },
  created(){
    this.getActividades();
  },
}
</script>
