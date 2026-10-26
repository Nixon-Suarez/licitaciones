<template>
  <div class="tab-pane fade" :class="{ 'show active': activo }" id="actividad" role="tabpanel">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
          {{ soloLectura ? 'Visualizar actividad' : 'Registrar actividad' }}
      </div>
      <div class="card-body">
        <input type="hidden" name="modulo_actividades" v-model="modulo_actividades" value="registrar_actividad">
        <input type="hidden" name="id_actividad" v-model="id_actividad">
        <div class="row g-3">
            <!-- cod_segmento -->
            <div class="col-md-6">
              <label for="cod_segmento" class="form-label">Codigo segmento</label>
              <label for="cod_segmento" class="form-label asterisco-obligatorio">*</label>
              <input id="cod_segmento" type="number" class="form-control" v-model="cod_segmento" name="cod_segmento" maxlength="5" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  El campo codigo segmento es obligatorio.
              </div>
            </div>
            <!-- nom_segmento -->
            <div class="col-md-6">
              <label for="nom_segmento" class="form-label">Nombre segmento</label>
              <label for="nom_segmento" class="form-label asterisco-obligatorio">*</label>
              <input id="nom_segmento" type="text" class="form-control" v-model="nom_segmento" name="nom_segmento" maxlength="150" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  La nombre del segmento es obligatoria.
              </div>
            </div>
            <!-- cod_familia -->
            <div class="col-md-6">
              <label for="cod_familia" class="form-label">Codigo familia</label>
              <label for="cod_familia" class="form-label asterisco-obligatorio">*</label>
              <input id="cod_familia" type="number" class="form-control" v-model="cod_familia" name="cod_familia" maxlength="5" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  El campo codigo familia es obligatorio.
              </div>
            </div>
            <!-- nom_familia -->
            <div class="col-md-6">
              <label for="nom_familia" class="form-label">Nombre familia</label>
              <label for="nom_familia" class="form-label asterisco-obligatorio">*</label>
              <input id="nom_familia" type="text" class="form-control" v-model="nom_familia" name="nom_familia" maxlength="150" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  La nombre de la familia es obligatoria.
              </div>
            </div>
            <!-- cod_clase -->
            <div class="col-md-6">
              <label for="cod_clase" class="form-label">Codigo clase</label>
              <label for="cod_clase" class="form-label asterisco-obligatorio">*</label>
              <input id="cod_clase" type="number" class="form-control" v-model="cod_clase" name="cod_clase" maxlength="5" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  El campo codigo clase es obligatorio.
              </div>
            </div>
            <!-- nom_clase -->
            <div class="col-md-6">
              <label for="nom_clase" class="form-label">Nombre clase</label>
              <label for="nom_clase" class="form-label asterisco-obligatorio">*</label>
              <input id="nom_clase" type="text" class="form-control" v-model="nom_clase" name="nom_clase" maxlength="150" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  La nombre de la clase es obligatoria.
              </div>
            </div>
            <!-- cod_product -->
            <div class="col-md-6">
              <label for="cod_product" class="form-label">Codigo producto</label>
              <label for="cod_product" class="form-label asterisco-obligatorio">*</label>
              <input id="cod_product" type="number" class="form-control" v-model="cod_product" name="cod_product" maxlength="5" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  El campo codigo product es obligatorio.
              </div>
            </div>
            <!-- nom_product -->
            <div class="col-md-6">
              <label for="nom_product" class="form-label">Nombre producto</label>
              <label for="nom_product" class="form-label asterisco-obligatorio">*</label>
              <input id="nom_product" type="text" class="form-control" v-model="nom_product" name="nom_product" maxlength="150" :readonly="soloLectura" required>
              <div class="invalid-feedback">
                  El nombre del product es obligatorio.
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "InfoBasicaTab",
  props: {
    data_actividades: {
      type: Object,
      required: true
    },
    activo: {
      type: Boolean,
      default: false
    },
    soloLectura: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:data_actividades'],
  data() {
      return {
        modulo_actividades: 'registrar_actividad',
        actividad_invalido: false,
        moneda_invalida: false
      }
  },
  computed: {
    cod_segmento: {
      get() { return this.data_actividades.cod_segmento },
      set(val) { this.$emit('update:data_actividades', { cod_segmento: val }) }
    },
    nom_segmento: {
      get() { return this.data_actividades.nom_segmento },
      set(val) { this.$emit('update:data_actividades', { nom_segmento: val }) }
    },
    id_actividad: {
      get() { return this.data_actividades.id_actividad },
      set(val) { this.$emit('update:data_actividades', { id_actividad: val }) }
    },
    nom_familia: {
      get() { return this.data_actividades.nom_familia },
      set(val) { this.$emit('update:data_actividades', { nom_familia: val }) }
    },
    cod_familia: {
      get() { return this.data_actividades.cod_familia },
      set(val) { this.$emit('update:data_actividades', { cod_familia: val }) }
    },
    nom_clase: {
      get() { return this.data_actividades.nom_clase },
      set(val) { this.$emit('update:data_actividades', { nom_clase: val }) }
    },
    cod_clase: {
      get() { return this.data_actividades.cod_clase },
      set(val) { this.$emit('update:data_actividades', { cod_clase: val }) }
    },
    nom_product: {
      get() { return this.data_actividades.nom_product },
      set(val) { this.$emit('update:data_actividades', { nom_product: val }) }
    },
    cod_product: {
      get() { return this.data_actividades.cod_product },
      set(val) { this.$emit('update:data_actividades', { cod_product: val }) }
    }
  }
}
</script>
