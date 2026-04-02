<template>
  <div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Ofertas</h2>
    <h5 class="text-secondary mb-4">Lista de Ofertas</h5>
    <!-- Formulario de búsqueda -->
    <div class="row">
      <div class="col-md-10 mx-auto">
        <form @submit.prevent="getOfertas" class="row g-3" >
          <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
          <!-- consecutivo -->
          <div class="col-md-5">
              <label for="txt_consecutivo" class="form-label">Consecutivo</label>
              <input v-model="txt_consecutivo" id="txt_consecutivo" type="text" name="txt_consecutivo" class="form-control rounded-pill" placeholder="¿Qué estás buscando?" maxlength="30">
          </div>
          <!-- Objeto / Descripción -->
          <div class="col-md-5">
              <label for="txt_buscador" class="form-label">Objeto / Descripción</label>
              <input v-model="txt_buscador" id="txt_buscador" type="text" name="txt_buscador" class="form-control rounded-pill" placeholder="¿Qué estás buscando?" maxlength="400">
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
                      <th>Consecutivo</th>
                      <th>Objeto</th>
                      <th>Descripcion</th>
                      <th>Fecha de inicio</th>
                      <th>Fecha de cierre</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                <tr v-for="(oferta, index) in ofertas" :key="oferta.id" class="text-center">
                    <td>{{ pag_inicio + index }}</td>
                    <td>{{ oferta.consecutivo }}</td>
                    <td>{{ oferta.objeto }}</td>
                    <td>{{ oferta.descripcion }}</td>
                    <td>{{ oferta.fecha_inicio }}</td>
                    <td>{{ oferta.fecha_cierre }}</td>
                    <td>{{ oferta.estado}}</td>
                    <!-- ver -->
                    <td class="d-flex justify-content-center gap-2">
                      <div type="submit" class="d-flex justify-content-center gap-3">
                          <a class="btn btn-primary">
                            <router-link :to="{ name: 'ofertasDetalle', params: { id: oferta.id } }" class="dropdown-item">Ver</router-link>
                          </a>
                      </div>
                    </td>
                </tr>
                <tr v-if="ofertas.length === 0" class="text-center">
                    <td colspan="8">
                        No hay registros en el sistema
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
      <p class="text-secondary text-center mb-3">Mostrando ofertas <strong>{{pag_inicio}}</strong> al <strong>{{pag_final}}</strong> de un <strong>total de {{consulta_total}} </strong></p>
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
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
export default {
  name: "ofertasListView",
  data() {
    return {
      txt_consecutivo: '',
      txt_buscador: '',
      ofertas: [],
      current_page: 0,
      registros_por_pagina: 10,
      pag_inicio : 0,
      consulta_total : 0,
      numero_paginas : 0,
      alerta_exitosa: false,
      alerta_fallido: false,
    }
  },
  methods: {
    getOfertas(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'oferta/list?' + 'pagina=' + this.current_page + '&registros=' + this.registros_por_pagina + '&consecutivo=' + this.txt_consecutivo + '&descripcion=' + this.txt_buscador, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              this.ofertas = response.data.data.datos
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
        this.getOfertas();
      }
    },
    nextPage() {
      if (this.current_page < this.numero_paginas) {
        this.current_page++;
        this.getOfertas();
      }
    }
  },
  created(){
    this.getOfertas();
  },
  computed: {
    pag_final: {
      get() { return this.current_page * this.registros_por_pagina}
    },
  },
}
</script>
