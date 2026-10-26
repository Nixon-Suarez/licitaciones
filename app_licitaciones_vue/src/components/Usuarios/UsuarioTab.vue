<template>
  <div class="tab-pane fade" :class="{ 'show active': activo }" id="usuario" role="tabpanel">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <div>
          <h5 class="mb-0">{{ soloLectura ? 'Visualizar usuario' : 'Actualizar usuario' }}</h5>
          <p class="mb-0 mt-1">Completa tus datos para actualizar tu cuenta.</p>
        </div>
      </div>
      <div class="card-body">
        <input type="hidden" name="modulo_usuario" v-model="modulo_usuario" value="actualizar_usuario">
        <input type="hidden" name="id_usuario" v-model="id_usuario">

        <section aria-labelledby="datosPersonalesTitle">
          <h6 id="datosPersonalesTitle" class="mb-3">Datos personales</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="usuario" class="form-label">
                Usuario <span class="asterisco-obligatorio" aria-hidden="true">*</span>
              </label>
              <input
                id="usuario"
                v-model="usuario"
                type="text"
                class="form-control"
                name="usuario"
                pattern="[a-zA-Z0-9]{4,20}"
                minlength="4"
                maxlength="20"
                autocomplete="username"
                aria-describedby="usuarioAyuda"
                :readonly="soloLectura"
                required
              >
              <div id="usuarioAyuda" class="form-text">De 4 a 20 caracteres, solo letras y números.</div>
            </div>
            <div class="col-md-6">
              <label for="nombre" class="form-label">
                Nombre <span class="asterisco-obligatorio" aria-hidden="true">*</span>
              </label>
              <input
                id="nombre"
                v-model="nombre"
                type="text"
                class="form-control"
                name="nombre"
                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                minlength="3"
                maxlength="40"
                autocomplete="given-name"
                :readonly="soloLectura"
                required
              >
            </div>
            <div class="col-md-6">
              <label for="apellido" class="form-label">
                Apellido <span class="asterisco-obligatorio" aria-hidden="true">*</span>
              </label>
              <input
                id="apellido"
                v-model="apellido"
                type="text"
                class="form-control"
                name="apellido"
                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                minlength="3"
                maxlength="40"
                autocomplete="family-name"
                :readonly="soloLectura"
                required
              >
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <p class="form-text mb-2">Usa tu nombre y apellido tal como quieres que aparezcan en tu cuenta.</p>
            </div>
          </div>
        </section>

        <hr class="my-4">

        <section aria-labelledby="seguridadTitle">
          <h6 id="seguridadTitle" class="mb-1">Seguridad</h6>
          <p class="form-text mb-3">La clave debe tener entre 7 y 100 caracteres. Se permiten letras, números y los símbolos $, @, . y -. Déjala en blanco si no deseas cambiarla.</p>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="clave" class="form-label">
                Clave
              </label>
              <input
                id="clave"
                v-model="clave"
                :type="showPasswords ? 'text' : 'password'"
                class="form-control"
                name="clave"
                pattern="[a-zA-Z0-9$@.-]{7,100}"
                minlength="7"
                maxlength="100"
                autocomplete="new-password"
                :readonly="soloLectura"
              >
            </div>
            <div class="col-md-6">
              <label for="clave2" class="form-label">
                Confirmar clave
              </label>
              <input
                id="clave2"
                v-model="clave2"
                :type="showPasswords ? 'text' : 'password'"
                class="form-control"
                :class="{ 'is-invalid': (clave || clave2) && !passwordsMatch }"
                name="clave2"
                pattern="[a-zA-Z0-9$@.-]{7,100}"
                minlength="7"
                maxlength="100"
                autocomplete="new-password"
                :aria-describedby="clave2 ? 'passwordMatchStatus' : undefined"
                :readonly="soloLectura"
              >
              <div
                v-if="clave || clave2"
                id="passwordMatchStatus"
                class="form-text"
                :class="passwordsMatch ? 'text-success' : 'text-danger'"
                aria-live="polite"
              >
                {{ passwordsMatch ? 'Las claves coinciden.' : 'Las claves no coinciden.' }}
              </div>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input id="showPasswords" v-model="showPasswords" class="form-check-input" type="checkbox">
                <label class="form-check-label" for="showPasswords">Mostrar claves</label>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "usuarioTab",
  props: {
    data_usuario: {
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
  emits: ['update:data_usuario'],
  data() {
      return {
        modulo_usuario: 'actualizar_usuario',
        usuario_invalido: false,
        showPasswords: false,
      }
  },
  computed: {
    passwordsMatch() {
      return this.clave === this.clave2;
    },
    id_usuario: {
      get() { return this.data_usuario.id_usuario },
      set(val) { this.$emit('update:data_usuario', { id_usuario: val }) }
    },
    nombre: {
      get() { return this.data_usuario.nombre },
      set(val) { this.$emit('update:data_usuario', { nombre: val }) }
    },
    apellido: {
      get() { return this.data_usuario.apellido },
      set(val) { this.$emit('update:data_usuario', { apellido: val }) }
    },
    usuario: {
      get() { return this.data_usuario.usuario },
      set(val) { this.$emit('update:data_usuario', { usuario: val }) }
    },
    clave: {
      get() { return this.data_usuario.clave },
      set(val) { this.$emit('update:data_usuario', { clave: val }) }
    },
    clave2: {
      get() { return this.data_usuario.clave2 },
      set(val) { this.$emit('update:data_usuario', { clave2: val }) }
    }
  }
}
</script>
