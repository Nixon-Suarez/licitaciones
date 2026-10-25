<template>
  <div class="tab-pane fade" :class="{ 'show active': activo }" id="fechas" role="tabpanel">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-secondary text-white">
            Periodo de Ejecución
        </div>
        <div class="card-body">
          <div v-if="fechaInvalida" class="alert alert-danger" role="alert">
            La fecha y hora de cierre no puede ser menor o igual a la fecha y hora de inicio
          </div>
          <div class="row g-3">
              <!-- Fecha inicio -->
              <div class="col-md-6">
                  <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                  <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
                  <input v-model="fecha_inicio" type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
              </div>
              <!-- Hora inicio -->
              <div class="col-md-6">
                  <label for="hora_inicio" class="form-label">Hora de inicio</label>
                  <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
                  <input v-model="hora_inicio" type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
              </div>
              <!-- Fecha cierre -->
              <div class="col-md-6">
                  <label for="fecha_cierre" class="form-label">Fecha de cierre</label>
                  <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
                  <input v-model="fecha_cierre" type="date" class="form-control" id="fecha_cierre" name="fecha_cierre" required>
              </div>
              <!-- Hora cierre -->
              <div class="col-md-6">
                  <label for="hora_cierre" class="form-label">Hora de cierre</label>
                  <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
                  <input v-model="hora_cierre" type="time" class="form-control" id="hora_cierre" name="hora_cierre" required>
              </div>
          </div>
          <hr class="my-4">
        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "FechasTab",
  props: {
    data_ofertas: {
      type: Object,
      required: true
    },
    activo: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:data_ofertas'],
  data() {
    return {
      modulo_ofertas: 'registrar_oferta'
    }
  },
  computed: {
    fecha_inicio: {
      get() { return this.data_ofertas.fecha_inicio },
      set(val) { this.$emit('update:data_ofertas', { fecha_inicio: val }) }
    },
    hora_inicio: {
      get() { return this.data_ofertas.hora_inicio },
      set(val) { this.$emit('update:data_ofertas', { hora_inicio: val }) }
    },
    fecha_cierre: {
      get() { return this.data_ofertas.fecha_cierre },
      set(val) { this.$emit('update:data_ofertas', { fecha_cierre: val }) }
    },
    hora_cierre: {
      get() { return this.data_ofertas.hora_cierre },
      set(val) { this.$emit('update:data_ofertas', { hora_cierre: val }) }
    },
    fechaInvalida() {
      const d = this.data_ofertas
      if (!d.fecha_inicio || !d.hora_inicio || !d.fecha_cierre || !d.hora_cierre) return false
      try {
        const inicio = new Date(d.fecha_inicio + 'T' + d.hora_inicio)
        const cierre = new Date(d.fecha_cierre + 'T' + d.hora_cierre)
        return cierre <= inicio
      } catch (e) {
        return false
      }
    }
  }
}
</script>
