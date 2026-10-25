<template>
  <div class="tab-pane fade" :class="{ 'show active': activo }" id="presupuesto" role="tabpanel">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
          Registrar Presupuesto
      </div>
      <div class="card-body">
        <!-- Objeto -->
        <div class="mb-3">
          <input type="hidden" name="modulo_ofertas" v-model="modulo_ofertas" value="registrar_oferta">
          <input type="hidden" name="oferta_id" v-model="oferta_id">
          <label for="objeto" class="form-label">Objeto</label>
          <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
<input type="text" class="form-control contador-texto" :class="{ 'border-danger': objetoAlLimite }" v-model="objeto" name="objeto" maxlength="150" required data-contador="contadorObjeto">
          <div class="form-text" :class="{ 'text-danger fw-semibold': objetoAlLimite }">
              <span id="contadorObjeto">{{ objetoContador }}</span>/150 caracteres
          </div>
          <div class="invalid-feedback">
              El campo Objeto es obligatorio.
          </div>
        </div>
        <!-- Descripción -->
        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción / Alcance</label>
          <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
          <textarea
              class="form-control contador-texto"
              :class="{ 'border-danger': descripcionAlLimite }"
              v-model="descripcion"
              name="descripcion"
              rows="4"
              maxlength="400"
              required
              data-contador="contadorDescripcion"
          ></textarea>
          <div class="form-text" :class="{ 'text-danger fw-semibold': descripcionAlLimite }">
              <span id="contadorDescripcion">{{ descripcionContador }}</span>/400 caracteres
          </div>
          <div class="invalid-feedback">
              La descripción es obligatoria.
          </div>
        </div>
        <!-- Moneda -->
        <div class="mb-3">
          <label for="moneda" class="form-label">Moneda</label>
          <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
          <select class="form-select" v-model="moneda" name="moneda" required>
              <option value="">Seleccione...</option>
              <option value="COP">COP</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
          </select>
          <div v-if="moneda_invalida" class="invalid-feedback">
              Seleccione una moneda válida.
          </div>
        </div>
        <!-- Presupuesto -->
        <div class="mb-3">
          <label for="presupuesto" class="form-label">Presupuesto</label>
          <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
          <input type="number" class="form-control" v-model="presupuesto" name="presupuesto" min="0" step="0.01" required>
          <div class="form-text">
              Solo números, máximo 2 decimales.
          </div>
          <div v-if="presupuesto_invalido" class="invalid-feedback">
              Ingrese un presupuesto válido.
          </div>
        </div>
        <!-- Actividad -->
        <SelectActividades :data_ofertas="data_ofertas" @update:data_ofertas="$emit('update:data_ofertas', $event)"/>
      </div>
    </div>
  </div>
</template>

<script>
import SelectActividades from '@/components/Ofertas/SelectActividades.vue'
export default {
  name: "PresupuestoTab",
  components: {
    SelectActividades
  },
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
        modulo_ofertas: 'registrar_oferta',
        presupuesto_invalido: false,
        moneda_invalida: false
      }
  },
  computed: {
    objeto: {
      get() { return this.data_ofertas.objeto },
      set(val) { this.$emit('update:data_ofertas', { objeto: val }) }
    },
    descripcion: {
      get() { return this.data_ofertas.descripcion },
      set(val) { this.$emit('update:data_ofertas', { descripcion: val }) }
    },
    moneda: {
      get() { return this.data_ofertas.moneda },
      set(val) { this.$emit('update:data_ofertas', { moneda: val }) }
    },
    presupuesto: {
      get() { return this.data_ofertas.presupuesto },
      set(val) { this.$emit('update:data_ofertas', { presupuesto: val }) }
    },
    oferta_id: {
      get() { return this.data_ofertas.oferta_id },
      set(val) { this.$emit('update:data_ofertas', { oferta_id: val }) }
    },
    actividad: {
      get() { return this.data_ofertas.actividad },
      set(val) { this.$emit('update:data_ofertas', { actividad: val }) }
    },
    objetoContador() {
      return (this.data_ofertas.objeto || '').length
    },
    descripcionContador() {
      return (this.data_ofertas.descripcion || '').length
    },
    objetoAlLimite() {
      return this.objetoContador >= 150
    },
    descripcionAlLimite() {
      return this.descripcionContador >= 400
    }
  }
}
</script>
