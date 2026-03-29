<template>
    <div class="container py-4 content">

    <!-- Bienvenida -->
    <div class="text-center mb-4">
        <h2 class="h5 text-secondary">
            ¡Bienvenido <span class="text-primary">{{ usuario }}</span>!
        </h2>
    </div>

    <div class="row justify-content-center g-4">

        <!-- LICITACIONES -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4 bg-primary bg-opacity-10 border-0">
                <h5 class="mb-3 text-primary">LICITACIONES</h5>

                <div class="d-flex justify-content-center mb-3">
                    <img src="../assets/img/licitaciones.png"
                         class="rounded-circle img-fluid"
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-primary">
                        <router-link class="dropdown-item" to="/ofertasNew">Crear</router-link>
                    </a>
                    <a class="btn btn-outline-primary" href="/ofertasList">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>
            </div>
        </div>

        <!-- ACTIVIDADES -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4 bg-success bg-opacity-10 border-0">
                <h5 class="mb-3 text-success">ACTIVIDADES</h5>

                <div class="d-flex justify-content-center mb-3">
                    <img src="../assets/img/actividades.png"
                         class="rounded-circle img-fluid"
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-outline-success" href="/actividadList">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
import { useAuthStore } from '@/stores/authStore'

export default {
  name: "InicioView",
  data(){
    const authStore = useAuthStore()
    return {
      usuario: authStore.usuario
    }
  },
};
</script>

<style scoped>

</style>
