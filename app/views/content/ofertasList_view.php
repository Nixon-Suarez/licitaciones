<div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Ofertas</h2>
    <h5 class="text-secondary mb-4">Lista de Ofertas</h5>

    <!-- Formulario de búsqueda -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <form class="FormularioAjax row g-3" 
                  action="<?php echo APP_URL; ?>app/ajax/FunctionAjax.php" 
                  method="POST" autocomplete="off">

                <input type="hidden" name="modulo_buscador" value="buscar_ofertas">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">

                <?php 
                    $current_consecutivo = isset($_SESSION[$url[0]]['consecutivo']) ? $_SESSION[$url[0]]['consecutivo'] : '';
                    $current_text = isset($_SESSION[$url[0]]['texto']) ? $_SESSION[$url[0]]['texto'] : '';
                ?>

                <!-- consecutivo -->
                <div class="col-md-5">
                    <label for="txt_consecutivo" class="form-label">Consecutivo</label>
                    <input id="txt_consecutivo" type="text" name="txt_consecutivo" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           maxlength="30"
                           value="<?php echo $current_consecutivo; ?>">
                </div>
                
                <!-- Objeto / Descripción -->
                <div class="col-md-5">
                    <label for="txt_buscador" class="form-label">Objeto / Descripción</label>
                    <input id="txt_buscador" type="text" name="txt_buscador" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           maxlength="400"
                           value="<?php echo $current_text; ?>">
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
            use app\controllers\ofertaController;
            $insofertass = new ofertaController();
            $pagina = isset($url[1]) ? $url[1] : 1;

            $tabla_data = $insofertass->listarOfertaControlador($pagina, 10, $url[0], $current_text, $current_consecutivo);
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
                                <th>Consecutivo</th>
                                <th>Objeto</th>
                                <th>Descripcion</th>
                                <th>Fecha de inicio</th>
                                <th>Fecha de cierre</th>
                                <th>Estado</th>
                                <th>Acciones</th>
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
                                <td>' . $rows->consecutivo . '</td>
                                <td>' . $rows->objeto . '</td>
                                <td>' . $rows->descripcion . '</td>
                                <td>' . $rows->fecha_inicio . '</td>
                                <td>' . $rows->fecha_cierre . '</td>
                                <td>' . strtoupper($rows->estado) . '</td>
                                <!-- ver -->
                                <td class="d-flex justify-content-center gap-2">
                                    <div type="submit class="d-flex justify-content-center gap-3">
                                        <a href="'. APP_URL .'?view=ofertasDetalle/'.$rows->id.'/" class="btn btn-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </div>
                                </td>
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
                                <td colspan="8">
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
                $tabla .= '<p class="text-secondary text-center mb-3">Mostrando ofertas <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $consulta_total . '</strong></p>';
                $tabla .= $insofertass->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
            echo $tabla;
        ?>
    </div>
</div>