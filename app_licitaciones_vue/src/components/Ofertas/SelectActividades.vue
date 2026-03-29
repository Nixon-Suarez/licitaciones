<template>
  <div class="mb-4">
      <label for="actividad" class="form-label">Actividad</label>
      <!-- Lo mejor es hacer una modal ya que son 4mil actividades 😑 -->
      <select class="form-select" v-model="actividad" name="actividad" required>
          <option value="">Seleccione una actividad</option>
          <option v-for="actividad in actividades" :key="actividad.id">{{ actividad.producto }}</option>
      </select>
      <div class="invalid-feedback">
          Debe seleccionar una actividad.
      </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'

export default {
  name: "SelectActividades",
  data() {
      return {
        actividad: "",
        actividades: ([])
      }
  },
  methods: {
      obtenerActividades(){
        const authStore = useAuthStore()
        const API_URL = authStore.baseUrl
        axios.get(API_URL + 'actividad/get', {
          headers: {
            'Authorization': 'Bearer ' + authStore.token
          }
        })
        .then(response => {
            let res = response.data;
            if(res.code == 200){
                this.actividades = res.data;
                console.log(this.actividades);
            }else{
                return alertas_ajax({
                    tipo: 'simple',
                    icono: 'error',
                    titulo: 'Error',
                    texto: res.data,
                })
            }
        })
        .catch(function(error){
          console.error("Error en la solicitud:", error);
          return alertas_ajax({
              tipo: 'simple',
              icono: 'error',
              titulo: 'Error',
              texto: "Ocurrió un error en el servidor",
          })
        })
    }
  },
  created(){
    this.obtenerActividades();
  }
}
</script>
