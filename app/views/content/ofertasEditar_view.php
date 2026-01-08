<?php

use app\controllers\ofertaController;
use app\controllers\ofertaDocumentController;

$insOfertas = new ofertaController();
$insOfertas_doc = new ofertaDocumentController();
$id = $insOfertas->limpiarCadena($url[1]);
$documentos = $insOfertas_doc->getOfertaDocumentControlar($id);
$oferta = $insOfertas->getOfertaControlador($id);
$fecha_inicio = !empty($oferta['fecha_inicio']) ? date('Y-m-d', strtotime($oferta['fecha_inicio'])) : '';
$fecha_cierre = !empty($oferta['fecha_cierre']) ? date('Y-m-d', strtotime($oferta['fecha_cierre'])) : '';
$hora_inicio = !empty($oferta['hora_inicio']) ? date('H:i', strtotime($oferta['hora_inicio'])) : '';
$hora_cierre = !empty($oferta['hora_cierre']) ?  date('H:i', strtotime($oferta['hora_cierre'])) : '';

?>
<div class="content p-4">
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="presupuesto-tab" data-bs-toggle="tab" data-bs-target="#presupuesto" type="button" role="tab">
                Presupuesto
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="fechas-tab" data-bs-toggle="tab" data-bs-target="#fechas" type="button" role="tab">
                Periodo de ejecución
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" id="adjunto-tab" data-bs-toggle="tab" data-bs-target="#adjunto">
                Documentos
            </button>
        </li>
    </ul>
    </ul>

    <div class="tab-content">
        <!-- TAB PRESUPUESTO -->
        <div class="tab-pane fade show active" id="presupuesto" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Registrar Presupuesto <?php echo $oferta['consecutivo'] ?? '' ?>
                </div>
                <div class="card-body">
                    <form class="needs-validation FormularioAjax" action="<?php echo APP_URL ?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off" id="formPresupuesto" novalidate>
                        <!-- Objeto -->
                        <div class="mb-3">
                            <input type="hidden" name="modulo_ofertas" id="modulo_ofertas" value="actualizar_oferta">
                            <input type="hidden" name="oferta_id" id="oferta_id" value="<?= $oferta['id'] ?? '' ?>">
                            <label for="objeto" class="form-label">Objeto</label>
                            <input
                                type="text"
                                class="form-control"
                                id="objeto"
                                name="objeto"
                                maxlength="50"
                                required
                                value="<?= $oferta['objeto'] ?>">
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
                                required><?= $oferta['descripcion'] ?? '' ?></textarea>
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
                            <select class="form-select" id="moneda" name="moneda" required>
                                <option value="">Seleccione...</option>
                                <option value="COP" <?= ($oferta['moneda'] == 'COP') ? 'selected' : '' ?>>COP</option>
                                <option value="USD" <?= ($oferta['moneda'] == 'USD') ? 'selected' : '' ?>>USD</option>
                                <option value="EUR" <?= ($oferta['moneda'] == 'EUR') ? 'selected' : '' ?>>EUR</option>
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
                                value="<?= $oferta['presupuesto'] ?? '' ?>">
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
                            <select
                                class="form-select"
                                id="actividad"
                                name="actividad"
                                required
                                value="<?= $oferta['actividad_id'] ?? '' ?>">
                                <option value="">Seleccione una actividad</option>
                                <?php

                                use app\controllers\actividadesController;

                                $insActividades = new actividadesController();
                                $actividades = $insActividades->getActividades();
                                foreach ($actividades as $actividad) {
                                    $selected = ($actividad->id == $oferta['actividad_id']) ? 'selected' : '';
                                    echo '<option value="' . $actividad->id . '" ' . $selected . '>' . $actividad->producto . '</option>';
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
        <div class="tab-pane fade" id="fechas" role="tabpanel">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    Periodo de Ejecución <?php echo $oferta['consecutivo'] ?? '' ?>
                </div>
                <div class="card-body">
                    <form
                        class="needs-validation FormularioAjax"
                        action="<?php echo APP_URL ?>app/ajax/FunctionAjax.php"
                        method="POST"
                        autocomplete="off"
                        id="formFechas"
                        novalidate>
                        <div class="row g-3">

                            <!-- Fecha inicio -->
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $fecha_inicio ?>" required>
                            </div>

                            <!-- Hora inicio -->
                            <div class="col-md-6">
                                <label for="hora_inicio" class="form-label">Hora de inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="<?= $hora_inicio ?>" required>
                            </div>

                            <!-- Fecha cierre -->
                            <div class="col-md-6">
                                <label for="fecha_cierre" class="form-label">Fecha de cierre</label>
                                <input type="date" class="form-control" id="fecha_cierre" name="fecha_cierre" value="<?= $fecha_cierre ?>" required>
                            </div>

                            <!-- Hora cierre -->
                            <div class="col-md-6">
                                <label for="hora_cierre" class="form-label">Hora de cierre</label>
                                <input type="time" class="form-control" id="hora_cierre" name="hora_cierre" value="<?= $hora_cierre ?>" required>
                            </div>
                        </div>
                        <hr class="my-4">
                    </form>

                </div>
            </div>
        </div>

        <!-- TAB DOCUMENTOS -->
        <div class="tab-pane fade" id="adjunto">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-dark text-white">
                    Documentos <?php echo $oferta['consecutivo']; ?>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalAdjuntos" id="btnOpenRegistrarAdjuntos">
                        Agregar documento
                    </button>

                    <?php if (!empty($documentos)) : ?>
                        <div class="row g-3 mt-1">
                            <?php foreach ($documentos as $doc) : ?>
                                <div class="col-md-12 border rounded p-3">
                                    <!-- TÍTULO -->
                                    <div class="mb-2">
                                        <label class="form-label text-muted mb-0">Título</label>
                                        <div class="fw-semibold">
                                            <?= $doc['titulo'] ?? '—' ?>
                                        </div>
                                    </div>

                                    <!-- DESCRIPCIÓN -->
                                    <div class="mb-2">
                                        <label class="form-label text-muted mb-0">Descripción</label>
                                        <div>
                                            <?= $doc['descripcion'] ?? '—' ?>
                                        </div>
                                    </div>

                                    <!-- ARCHIVO -->
                                    <div class="mb-2">
                                        <label class="form-label text-muted mb-1">Archivo</label>

                                        <?php if (!empty($doc['archivo'])) : ?>
                                            <div class="d-flex gap-2 align-items-center flex-wrap">

                                                <a
                                                    href="<?= APP_URL . 'app/views/docs/uploads/ofertas/' . $doc['archivo']; ?>"
                                                    class="btn btn-outline-primary btn-sm"
                                                    download
                                                >
                                                    <i class="bi bi-download"></i> Descargar
                                                </a>

                                                <form
                                                    class="FormularioAjax m-0"
                                                    action="<?= APP_URL ?>app/ajax/FunctionAjax.php"
                                                    method="POST"
                                                >
                                                    <input type="hidden" name="modulo_ofertas_adjunto" value="eliminar_adjunto">
                                                    <input type="hidden" name="document_id" value="<?= $doc['id']; ?>">

                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>

                                            </div>
                                        <?php else : ?>
                                            <i class="text-danger">No se encontró el archivo</i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php else : ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-folder-x fs-3"></i>
                            <p class="mt-2 mb-0">No hay documentos asociados</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Botones -->
    <div class="mb-3 mt-2 d-flex justify-content-between align-items-center">
        <?php include_once './app/views/inc/btn_back.php'; ?>

        <button id="btnGuardarTodo" class="btn btn-success">
            Guardar todo
        </button>
    </div>
</div>

<!-- Modal Adjunto -->
<div class="modal fade" id="modalAdjuntos" tabindex="-1" aria-labelledby="modalAdjuntoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdjuntoLabel">Registrar Adjunto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="FormularioAjax" action="<?php echo APP_URL ?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off">
                    <input type="hidden" name="modulo_ofertas_adjunto" id="modulo_ofertas_adjunto" value="registrar_adjunto">
                    <input type="hidden" name="oferta_id" id="oferta_id" value="<?= $id ?>">
                    <!-- Título -->
                    <div class="mb-3">
                        <label for="    " class="form-label">Titulo</label>
                        <label for="titulo_adjunto" class="form-label asterisco-obligatorio">*</label>
                        <input type="number" class="form-control"
                            id="titulo_adjunto" name="titulo_adjunto" pattern="[0-9]{3,100}" required>
                        </input>
                    </div>
                    <!-- Descripcion -->
                    <div class="mb-3">
                        <label for="descripcion_Adjunto" class="form-label">Descripcion Gasto</label>
                        <label for="descripcion_Adjunto" class="form-label asterisco-obligatorio">*</label>
                        <textarea type="text" class="form-control"
                            id="descripcion_Adjunto" name="descripcion_Adjunto" required></textarea>
                    </div>
                    <!-- Documento -->
                    <a id="descargar_gasto" href="" hidden download class="btn btn-success">
                        <i class="bi bi-download"></i> Descargar
                    </a>
                    <div class="mb-3 text-center">
                        <label for="gasto_documento" class="form-label">Seleccione un archivo</label>
                        <label for="descripcion_Adjunto" class="form-label asterisco-obligatorio">*</label>
                        <input class="form-control" type="file" id="gasto_documento" name="gasto_documento" accept=".pdf,.zip" required>
                        <div class="form-text">PDF, ZIP. (MAX 10MB)</div>
                    </div>

                    <!-- Boton (Registrar/Actualizar) -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-custom" id="btnSubmitAdjunto">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>