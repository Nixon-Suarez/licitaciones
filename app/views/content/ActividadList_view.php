<div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Actividad</h2>
    <h5 class="text-secondary mb-4">Lista de Actividad</h5>

    <!-- Formulario de búsqueda -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <form class="FormularioAjax row g-3" 
                  action="<?php echo APP_URL; ?>app/ajax/FunctionAjax.php" 
                  method="POST" autocomplete="off">

                <input type="hidden" name="modulo_buscador" value="buscar_actividad">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">

                <?php 
                    $current_segmento = isset($_SESSION[$url[0]]['segmento']) ? $_SESSION[$url[0]]['segmento'] : '';
                    $current_producto = isset($_SESSION[$url[0]]['producto']) ? $_SESSION[$url[0]]['producto'] : '';
                ?>

                <!-- segmento -->
                <div class="col-md-5">
                    <label for="txt_segmento" class="form-label">Segmento</label>
                    <input id="txt_segmento" type="text" name="txt_segmento" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           maxlength="30"
                           value="<?php echo $current_segmento; ?>">
                </div>
                
                <!-- producto -->
                <div class="col-md-5">
                    <label for="txt_producto" class="form-label">Producto</label>
                    <input id="txt_producto" type="text" name="txt_producto" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           maxlength="400"
                           value="<?php echo $current_producto; ?>">
                </div>

                <!-- Botón -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-pill w-100">
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div>
        <?php 
            use app\controllers\actividadesController;
            $insActividades = new actividadesController();
            $pagina = isset($url[1]) ? $url[1] : 1;
            $tabla_data = $insActividades->listarActividadesControlador($pagina, 10, $url[0], $current_segmento, $current_producto);
            $consulta_datos = $tabla_data['datos'];
            $consulta_total = $tabla_data['total'];
            $numeroPaginas = $tabla_data['paginas'];
            $url = $tabla_data['url'];
            $pagina = $tabla_data['pagina_actual'];
            $inicio = $tabla_data['inicio'];

            $tabla = ' 
                <br>
                <!-- Tabla -->
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover">
                        <thead class="custom-header text-center">
                            <tr>
                                <th>#</th>
                                <th>Segmento</th>
                                <th>Familia</th>
                                <th>Clase</th>
                                <th>Producto</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
            if ($consulta_total && $pagina <= $numeroPaginas) {
                $contador = $inicio;
                $pag_inicio = $inicio;
                foreach ($consulta_datos as $rows) {
                    $tabla .= '
                            <tr class="text-center">
                                <td>' . $contador . '</td>
                                <td>' . $rows->segmento . '</td>
                                <td>' . $rows->familia . '</td>
                                <td>' . $rows->clase . '</td>
                                <td>' . $rows->producto . '</td>
                            </tr>
                        ';
                    $contador++;
                }
                $pag_final = $contador - 1;
            } else {
                if ($consulta_total) {
                    $tabla .= '
                            <tr class="text-center">
                                <td colspan="4">
                                    <a href="' . $url . '1/"
                                    class="btn btn-info text-white rounded-pill btn-sm mt-3 mb-3">
                                        Haga clic acá para recargar el listado
                                    </a>
                                </td>
                            </tr>
                        ';
                } else {
                    $tabla .= '
                            <tr class="text-center">
                                <td colspan="5">
                                    No hay registros en el sistema
                                </td>
                            </tr>
                        ';
                }
            }
            $tabla .= '
                        </tbody>
                    </table>
                </div>
            ';
            if ($consulta_total && $pagina <= $numeroPaginas) {
                $tabla .= '<p class="text-secondary text-center mb-3">Mostrando actividad <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $consulta_total . '</strong></p>';
                $tabla .= $insActividades->paginadorTablas($pagina, $numeroPaginas, $url, 5);
            }
            echo $tabla;
        ?>
    </div>
</div>