<template>
  <div class="tab-pane fade" id="ver-fechas">
      <div class="card shadow-sm mt-3">
        <div class="card-header bg-secondary text-white">
            Periodo de Ejecución {{oferta.consecutivo}}
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-muted">Fecha de inicio</label>
              <div class="fw-semibold">
                  {{oferta.fecha_inicio}}
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Hora de inicio</label>
              <div class="fw-semibold">
                  {{oferta.hora_inicio}}
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Fecha de cierre</label>
              <div class="fw-semibold">
                  {{oferta.fecha_cierre}}
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Hora de cierre</label>
              <div class="fw-semibold">
                  {{oferta.hora_cierre}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: "FechasDetalleTab",
  props: {
    oferta: {
      type: Object,
      required: true
    }
  },
  data() {
  }
}
</script>
