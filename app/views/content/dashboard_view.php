<div class="container py-4 content">

    <!-- Bienvenida -->
    <div class="text-center mb-4">
        <h2 class="h5 text-secondary">
            ¡Bienvenido <span class="text-primary"><?php echo $_SESSION['nombre'] ?></span>!
        </h2>
    </div>

    <div class="row justify-content-center g-4">

        <!-- LICITACIONES -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4 bg-primary bg-opacity-10 border-0">
                <h5 class="mb-3 text-primary">LICITACIONES</h5>

                <div class="d-flex justify-content-center mb-3">
                    <img src="<?php echo APP_URL; ?>app/views/img/Money.png"
                         class="rounded-circle img-fluid"
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-success" href="<?php echo APP_URL;?>?view=ofertasNew">
                        <i class="bi bi-plus-circle"></i> Crear
                    </a>
                    <a class="btn btn-primary" href="<?php echo APP_URL;?>?view=ofertasList">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>
            </div>
        </div>

        <!-- USUARIOS -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4 bg-success bg-opacity-10 border-0">
                <h5 class="mb-3 text-success">USUARIOS</h5>

                <div class="d-flex justify-content-center mb-3">
                    <img src="<?php echo APP_URL; ?>app/views/img/Money.png"
                         class="rounded-circle img-fluid"
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Crear
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </div>
            </div>
        </div>

        <!-- ACTIVIDADES -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4 bg-warning bg-opacity-10 border-0">
                <h5 class="mb-3 text-warning">ACTIVIDADES</h5>

                <div class="d-flex justify-content-center mb-3">
                    <img src="<?php echo APP_URL; ?>app/views/img/Money.png"
                         class="rounded-circle img-fluid"
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-warning text-white">
                        <i class="bi bi-plus-circle"></i> Crear
                    </button>
                    <button class="btn btn-outline-warning">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

