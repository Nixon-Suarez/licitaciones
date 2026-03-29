<template>
  <div class="tab-pane fade show active" id="presupuesto" role="tabpanel">
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
          <input type="text" class="form-control contador-texto" v-model="objeto" name="objeto" maxlength="50" required data-contador="contadorObjeto">
          <div class="form-text">
              <span id="contadorObjeto">0</span>/150 caracteres
          </div>
          <div class="invalid-feedback">
              El campo Objeto es obligatorio.
          </div>
        </div>
        <!-- Descripción -->
        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción / Alcance</label>
          <textarea
              class="form-control contador-texto"
              v-model="descripcion"
              name="descripcion"
              rows="4"
              maxlength="400"
              required
              data-contador="contadorDescripcion"
          ></textarea>
          <div class="form-text">
              <span id="contadorDescripcion">0</span>/400 caracteres
          </div>
          <div class="invalid-feedback">
              La descripción es obligatoria.
          </div>
        </div>
        <!-- Moneda -->
        <div class="mb-3">
          <label for="moneda" class="form-label">Moneda</label>
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
          <input type="number" class="form-control" v-model="presupuesto" name="presupuesto" min="0" step="0.01" required>
          <div class="form-text">
              Solo números, máximo 2 decimales.
          </div>
          <div v-if="presupuesto_invalido" class="invalid-feedback">
              Ingrese un presupuesto válido.
          </div>
        </div>
        <!-- Actividad -->
        <SelectActividades/>
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
  data() {
      return {
        modulo_ofertas: 'registrar_oferta',
        data_ofertas: {
          oferta_id: '',
          objeto: '',
          descripcion: '',
          moneda: '',
          presupuesto: null,
          actividad: "",
        },
        presupuesto_invalido: false,
        moneda_invalida: false
      }
  }
}
</script>
