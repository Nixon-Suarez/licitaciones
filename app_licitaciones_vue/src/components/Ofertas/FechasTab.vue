<template>
  <div class="tab-pane fade" id="fechas" role="tabpanel">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-secondary text-white">
            Periodo de Ejecución
        </div>
        <div class="card-body">
          <div class="row g-3">
              <!-- Fecha inicio -->
              <div class="col-md-6">
                  <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                  <input v-model="fecha_inicio" type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
              </div>
              <!-- Hora inicio -->
              <div class="col-md-6">
                  <label for="hora_inicio" class="form-label">Hora de inicio</label>
                  <input v-model="hora_inicio" type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
              </div>
              <!-- Fecha cierre -->
              <div class="col-md-6">
                  <label for="fecha_cierre" class="form-label">Fecha de cierre</label>
                  <input v-model="fecha_cierre" type="date" class="form-control" id="fecha_cierre" name="fecha_cierre" required>
              </div>
              <!-- Hora cierre -->
              <div class="col-md-6">
                  <label for="hora_cierre" class="form-label">Hora de cierre</label>
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
  name: "PresupuestoTab",
  data() {
    return {
      modulo_ofertas: 'registrar_oferta',
      data_ofertas: {
        fecha_inicio: "",
        hora_inicio: "",
        fecha_cierre: "",
        hora_cierre: ""
      }
    }
  }
}
</script>
