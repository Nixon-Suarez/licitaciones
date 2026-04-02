<template>
  <div class="container mb-4 content">
    <h2 class="text-secondary">Detalle de Oferta</h2>
    <p>ID de la oferta: {{ ofertaId }}</p>
    <!-- Aquí puedes agregar más detalles de la oferta -->
  </div>
</template>

<script>
export default {
  name: "OfertasDetalleView",
  data() {
    return {
      ofertaId: null
    }
  },
  created() {
    this.ofertaId = this.$route.params.id;
    // Aquí puedes hacer una petición para obtener los detalles de la oferta usando this.ofertaId
  }
}
</script>