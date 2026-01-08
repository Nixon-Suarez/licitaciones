<div class="content p-4">
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item" role="presentation">
        <button
            class="nav-link active"
            id="presupuesto-tab"
            data-bs-toggle="tab"
            data-bs-target="#presupuesto"
            type="button"
            role="tab"
        >
            Presupuesto
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="fechas-tab"
            data-bs-toggle="tab"
            data-bs-target="#fechas"
            type="button"
            role="tab"
        >
            Periodo de ejecución
        </button>
    </li>
    </ul>

    <div class="tab-content">
    <!-- TAB PRESUPUESTO -->
        <div
            class="tab-pane fade show active"
            id="presupuesto"
            role="tabpanel"
        >
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Registrar Presupuesto
                </div>
                <div class="card-body">
                    <form class="needs-validation FormularioAjax" action="<?php echo APP_URL?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off" id="formPresupuesto" novalidate>
                        <!-- Objeto -->
                        <div class="mb-3">
                            <input type="hidden" name="modulo_ofertas" id="modulo_ofertas" value="registrar_oferta">
                            <input type="hidden" name="oferta_id" id="oferta_id">
                            <label for="objeto" class="form-label">Objeto</label>
                            <input
                                type="text"
                                class="form-control"
                                id="objeto"
                                name="objeto"
                                maxlength="50"
                                required
                            >
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
                                class="form-control"
                                id="descripcion"
                                name="descripcion"
                                rows="4"
                                maxlength="400"
                                required
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
                            <select
                                class="form-select"
                                id="moneda"
                                name="moneda"
                                required
                            >
                                <option value="">Seleccione...</option>
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <div class="invalid-feedback">
                                Seleccione una moneda válida.
                            </div>
                        </div>

                        <!-- Presupuesto -->
                        <div class="mb-3">
                            <label for="presupuesto" class="form-label">Presupuesto</label>
                            <input
                                type="number"
                                class="form-control"
                                id="presupuesto"
                                name="presupuesto"
                                min="0"
                                step="0.01"
                                required
                            >
                            <div class="form-text">
                                Solo números, máximo 2 decimales.
                            </div>
                            <div class="invalid-feedback">
                                Ingrese un presupuesto válido.
                            </div>
                        </div>

                        <!-- Actividad -->
                        <div class="mb-4">
                            <label for="actividad" class="form-label">Actividad</label>
                            <!-- Lo mejor es hacer una modal ya que son 4mil actividades 😑 -->
                            <select class="form-select" id="actividad" name="actividad" required>
                                <option value="">Seleccione una actividad</option>
                                <?php
                                    use app\controllers\actividadesController;
                                    $insActividades = new actividadesController();
                                    $actividades = $insActividades->getActividades(); 
                                    foreach($actividades as $actividad){
                                        echo '<option value="'.$actividad->id.'">'.$actividad->producto.'</option>';
                                    }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Debe seleccionar una actividad.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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