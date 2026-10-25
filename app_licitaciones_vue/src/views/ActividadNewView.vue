<template>
  <div class="content p-4">
    <h2 class="mb-4">{{ isEditing ? 'Editar Actividad' : 'Nueva Actividad' }}</h2>
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link" :class="{ active: activeTab === 'actividad' }" id="actividad-tab" type="button" role="tab" @click="activeTab = 'actividad'"> Información Básica </button>
      </li>
    </ul>

    <form @submit.prevent="guardarActividad">
      <div class="tab-content">
        <!-- TAB actividad -->
        <ActividadTab :data_actividades="data_actividades" :activo="activeTab === 'actividad'" @update:data_actividades="actualizarDatos"/>
      </div>

      <!-- BOTÓN -->
      <div class="mb-3 mt-2 d-flex justify-content-center">
          <button type="submit" id="btnGuardarTodo" class="btn btn-success" :disabled="!formularioValido">
              {{ isEditing ? 'Actualizar' : 'Guardar' }}
          </button>
      </div>
    </form>
</div>
</template>

<script>
import ActividadTab from "@/components/Actividades/InfoBasicaTab.vue";
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore'
import { alertas_ajax } from '@/stores/alertStore'

const datosIniciales = () => ({
  id_actividad: null,
  cod_segmento: '',
  nom_segmento: '',
  cod_familia: '',
  nom_familia: '',
  cod_clase: '',
  nom_clase: '',
  cod_product: '',
  nom_product: '',
});

export default {
  name: "ActividadNewView",
  data() {
    return {
      isEditing: false,
      ActividadId: null,
      activeTab: 'actividad',
      data_actividades: datosIniciales()
    }
  },
  watch: {
    '$route'() {
      this.inicializar()
    }
  },
  computed: {
    formularioValido(){
      const d = this.data_actividades
      if (!d.nom_segmento || !d.nom_segmento.toString().trim()) return false
      if (!d.nom_familia || !d.nom_familia.toString().trim()) return false
      if (!d.nom_clase || !d.nom_clase.toString().trim()) return false
      if (!d.nom_product || !d.nom_product.toString().trim()) return false

      if (d.cod_segmento === null || d.cod_segmento === '' || isNaN(d.cod_segmento) || Number(d.cod_segmento) <= 0) return false
      if (d.cod_familia === null || d.cod_familia === '' || isNaN(d.cod_familia) || Number(d.cod_familia) <= 0) return false
      if (d.cod_product === null || d.cod_product === '' || isNaN(d.cod_product) || Number(d.cod_product) <= 0) return false
      if (d.cod_clase === null || d.cod_clase === '' || isNaN(d.cod_clase) || Number(d.cod_clase) <= 0) return false

      if (!d.id_actividad && this.isEditing) return false

      if (String(d.nom_segmento).length > 150) return false
      if (String(d.nom_familia).length > 150) return false
      if (String(d.nom_clase).length > 150) return false
      if (String(d.nom_product).length > 150) return false

      return true
    }
  },
  components: {
    ActividadTab
  },
  methods: {
    inicializar() {
      this.ActividadId = this.$route.params.id;
      this.isEditing = !!this.ActividadId;
      this.activeTab = 'actividad';
      this.data_actividades = datosIniciales();
      if (this.isEditing) {
        this.cargarActividad();
      }
    },
    actualizarDatos(datosActualizados) {
      // Sincronizar los datos del padre con los datos actualizados del hijo
      this.data_actividades = { ...this.data_actividades, ...datosActualizados }
    },
    cargarActividad(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      axios.get(API_URL + 'actividad/get?id=' + this.ActividadId, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == 200){
              const actividad = response.data.data;
              this.data_actividades = {
                id_actividad: actividad.id,
                cod_segmento: actividad.codigo_segmento,
                nom_segmento: actividad.segmento,
                cod_familia: actividad.codigo_familia,
                nom_familia: actividad.familia,
                cod_clase: actividad.codigo_clase,
                nom_clase: actividad.clase,
                cod_product: actividad.codigo_producto,
                nom_product: actividad.producto,
              };
          }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
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
    },
    guardarActividad(){
      const authStore = useAuthStore()
      const API_URL = authStore.baseUrl
      const method = this.isEditing ? 'put' : 'post'
      const url = this.isEditing ? API_URL + 'actividad/update' : API_URL + 'actividad/insert'
      axios[method](url, this.data_actividades, {
        headers: {
          'Authorization': 'Bearer ' + authStore.token
        }
      })
      .then(response => {
          if(response.data.code == (this.isEditing ? 200 : 201)){
              this.$router.push('/actividadList');
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
    }
  },
  created() {
    this.inicializar();
  }
}
</script>
