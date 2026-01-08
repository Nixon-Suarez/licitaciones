<?php

use app\controllers\ofertaController;
use app\controllers\ofertaDocumentController;
use app\controllers\actividadesController;

$insOfertas = new ofertaController();
$insOfertas_doc = new ofertaDocumentController();
$insActividad = new ActividadesController();
$id = $insOfertas->limpiarCadena($url[1]);
$oferta = $insOfertas->getOfertaControlador($id);
$actividad = $insActividad->getActividades($oferta['actividad_id'] ?? '');
$documentos = $insOfertas_doc->getOfertaDocumentControlar($id);
?>
<div class="content p-4">
    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ver-presupuesto">
                Presupuesto
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ver-fechas">
                Periodo de ejecución
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ver-adjunto">
                Documentos
            </button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- TAB PRESUPUESTO -->
        <div class="tab-pane fade show active" id="ver-presupuesto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Detalle del Presupuesto <?php echo $oferta['consecutivo'] ?? '—'; ?>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label text-muted">Objeto</label>
                            <div class="fw-semibold">
                                <?= $oferta['objeto'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted">Descripción / Alcance</label>
                            <div class="fw-semibold">
                                <?= $oferta['descripcion'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Moneda</label>
                            <div class="fw-semibold">
                                <?= $oferta['moneda'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Presupuesto</label>
                            <div class="fw-semibold text-success">
                                $ <?= number_format($oferta['presupuesto'] ?? 0, 2) ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Actividad</label>
                            <div class="fw-semibold">
                                <?= isset($actividad->producto) ? $actividad->producto : '—' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB FECHAS -->
        <div class="tab-pane fade" id="ver-fechas">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    Periodo de Ejecución <?php echo $oferta['consecutivo'] ?? '—'; ?>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label text-muted">Fecha de inicio</label>
                            <div class="fw-semibold">
                                <?= $oferta['fecha_inicio'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Hora de inicio</label>
                            <div class="fw-semibold">
                                <?= $oferta['hora_inicio'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Fecha de cierre</label>
                            <div class="fw-semibold">
                                <?= $oferta['fecha_cierre'] ?? '—' ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Hora de cierre</label>
                            <div class="fw-semibold">
                                <?= $oferta['hora_cierre'] ?? '—' ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- TAB DOCUMENTOS -->
        <div class="tab-pane fade" id="ver-adjunto">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-dark text-white">
                    Documentos asociados <?php echo $oferta['consecutivo'] ?? '—'; ?>
                </div>

                <div class="card-body">

                    <?php if (!empty($documentos)) : ?>
                        <div class="row g-3">

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
                                        <label class="form-label text-muted mb-0">Archivo</label>
                                        <div>
                                            <?php if (!empty($doc['archivo'])) : ?>
                                                <a
                                                    href="<?= APP_URL . 'uploads/' . $doc['archivo']; ?>"
                                                    class="btn btn-outline-primary btn-sm"
                                                    download>
                                                    <i class="bi bi-download"></i>
                                                    Descargar archivo
                                                </a>
                                            <?php else : ?>
                                                —
                                            <?php endif; ?>
                                        </div>
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

    <!-- BOTONES -->
    <div class="mb-3 mt-2 d-flex justify-content-between align-items-center">
        <?php include_once './app/views/inc/btn_back.php'; 
        if (isset($oferta['id'])){
        ?>
        <a href="<?= APP_URL . '?view=ofertasEditar/' . $oferta['id']; ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <?php }?>
    </div>
</div>