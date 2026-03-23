<template>
  <div class="content p-4">
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="presupuesto-tab" data-bs-toggle="tab" data-bs-target="#presupuesto" type="button" role="tab"> Presupuesto </button>
      </li>
      <li class="nav-item" role="presentation">
          <button class="nav-link" id="fechas-tab" data-bs-toggle="tab" data-bs-target="#fechas" type="button" role="tab">Periodo de ejecución</button>
      </li>
    </ul>

    <div class="tab-content">
      <!-- TAB PRESUPUESTO -->
      

        <!-- TAB FECHAS -->
        <div
            class="tab-pane fade"
            id="fechas"
            role="tabpanel"
        >
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    Periodo de Ejecución
                </div>
                <div class="card-body">
                    <form
                        class="needs-validation FormularioAjax"
                        action="<?php echo APP_URL?>app/ajax/FunctionAjax.php"
                        method="POST"
                        autocomplete="off"
                        id="formFechas"
                        novalidate
                    >
                        <div class="row g-3">

                            <!-- Fecha inicio -->
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>

                            <!-- Hora inicio -->
                            <div class="col-md-6">
                                <label for="hora_inicio" class="form-label">Hora de inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                            </div>

                            <!-- Fecha cierre -->
                            <div class="col-md-6">
                                <label for="fecha_cierre" class="form-label">Fecha de cierre</label>
                                <input type="date" class="form-control" id="fecha_cierre" name="fecha_cierre" required>
                            </div>

                            <!-- Hora cierre -->
                            <div class="col-md-6">
                                <label for="hora_cierre" class="form-label">Hora de cierre</label>
                                <input type="time" class="form-control" id="hora_cierre" name="hora_cierre" required>
                            </div>

                        </div>

                        <hr class="my-4">
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- BOTÓN -->
    <div class="mb-3 mt-2 d-flex justify-content-center">
        <button id="btnGuardarTodo" class="btn btn-success">
            Guardar todo
        </button>
    </div>
</div>
</template>

<script>
</script>
