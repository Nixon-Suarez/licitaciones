<template>
  <div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Actividades</h2>
    <h5 class="text-secondary mb-4">Lista de Actividades</h5>
    <!-- Modal de cargue masivo -->
    <ModalCargueMasivoAct :visible="modalCargue" @close="modalCargue = false" @cargado="getActividades" />
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
    <!-- Acciones -->
    <div class="d-flex justify-content-end mt-3 mb-2">
      <button type="button" class="btn btn-success rounded-pill" @click="modalCargue = true">
        <i class="bi bi-upload"></i> Cargue masivo
      </button>
    </div>
    <div>
      <br>
      <!-- Tabla -->
      <div class="table-responsive mt-1">
          <table class="table table-striped table-hover">
              <thead class="custom-header text-center">
                  <tr>
                      <th>#</th>
                      <th>Segmento</th>
                      <th>Familia</th>
                      <th>Clase</th>
                      <th>Producto</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                <tr v-for="(actividad, index) in Actividades" :key="actividad.id" class="text-center">
                    <td>{{ pag_inicio + index }}</td>
                    <td>{{ actividad.segmento}}</td>
                    <td>{{ actividad.familia}}</td>
                    <td>{{ actividad.clase}}</td>
                    <td>{{ actividad.producto}}</td>
                    <!-- eliminar -->
                    <td class="justify-content-center gap-2">
                      <div type="submit" class="d-flex justify-content-center gap-3">
                          <router-link :to="`/actividadEditar/${actividad.id}`" class="btn btn-primary btn-sm">
                              Editar
                          </router-link>
                          <form @submit.prevent="eliminarActividad(actividad.id)">
                              <button type="submit" class="btn btn-danger btn-sm">
                                  <i class="bi bi-trash"></i> Eliminar
                              </button>
                          </form>
                      </div>
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
    <div v-if="alerta_fallido" class="alert alert-danger" role="alert">
      Consulta fallida, por favor intente nuevamente
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { alertas_ajax } from '@/stores/alertStore';
import { useAuthStore } from '@/stores/authStore'
import ModalCargueMasivoAct from '@/components/Actividades/ModalCargueMasivoAct.vue'
export default {
  name: "ActividadesListView",
  components: {
    ModalCargueMasivoAct
  },
  data() {
    return {
      txt_segmento: '',
      txt_producto: '',
      Actividades: [],
      current_page: 0,
      registros_por_pagina: 10,
      pag_inicio : 0,
      consulta_total : 0,
      numero_paginas : 0,
      modalCargue: false,
      alerta_exitosa: false,
      alerta_fallido: false,
    }
  },
  methods: {
    eliminarActividad(actividadId) {
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.delete(API_URL + 'actividad/delete/' + actividadId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.getActividades();
              this.alerta_exitosa = true
              setTimeout(() => {
                this.alerta_exitosa = false
              }, 3000);
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        if(error.response && error.response.status === 400 || error.response.status === 404){
            return alertas_ajax({
                tipo: 'simple',
                icono: 'warning',
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
    }
  },
  created(){
    this.getActividades();
  },
  computed: {
    pag_final: {
      get() { return this.current_page * this.registros_por_pagina}
    },
  },
}
</script>
