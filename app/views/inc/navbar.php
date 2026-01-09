<nav class="navbar navbar-expand-lg navbar-custom shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand me-2" href="<?php echo APP_URL; ?>?view=dashboard">
            <img src="<?php echo APP_URL; ?>app/views/img/Money.png" class="img-profile me-2">
        </a>

        <!-- Botón colapsable (móvil) -->
        <button class="navbar-toggler btn btn-outline-light " type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo APP_URL; ?>?view=dashboard">Dashboard</a>
                </li>
                <!-- ofertas -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ofertaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Licitaciones
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="ofertaDropdown">
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>?view=ofertasList/">Listado</a></li>
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>?view=ofertasNew/">Nuevo</a></li>
                    </ul>
                </li>
                <!-- Actividades -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="actividadesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actividades
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="actividadesDropdown">
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>?view=actividadList/">Listado</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Usuario -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="usuarioDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['usuario']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                        <li><a class="dropdown-item" href="<?php echo APP_URL . "?view=userUpdate/" . $_SESSION['id'] . "/"; ?>">Mi cuenta</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>?view=logout/" id="btn_exit">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>