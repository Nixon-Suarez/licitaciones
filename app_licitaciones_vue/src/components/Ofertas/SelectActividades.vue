<template>
  <div class="mb-4">
      <label for="actividad" class="form-label">Actividad</label>
      <select class="form-select" v-model="actividad_selec" name="actividad" required>
          <option value="">Seleccione una actividad</option>
          <option v-for="actividad in actividades" :key="actividad.id" :value="actividad.id">{{ actividad.producto }}</option>
      </select>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'
export default {
  name: "SelectActividades",
  props: {
    data_ofertas: {
      type: Object,
      required: true
    }
  },
  emits: ['update:data_ofertas'],
  data() {
    return {
      modulo_ofertas: 'registrar_oferta',
      actividad: "",
      actividades: ([])
    }
  },
  computed: {
    actividad_selec: {
      get() { return this.data_ofertas.actividad_id},
      set(val) { this.$emit('update:data_ofertas', { actividad_id: val }) }
    },
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
          if(response.data.code == 200){
              this.actividades = response.data.data;
              console.log(this.actividades);
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        this.alertaEstado = true;
        if(error.response.status === 404){
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
  },
  created(){
    this.obtenerActividades();
  }
}
</script>
