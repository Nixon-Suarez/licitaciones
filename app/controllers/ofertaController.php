<?php

namespace app\controllers;

use app\models\mainModel;
use App\Models\Oferta;
use App\Models\Actividad;

class ofertaController extends mainModel{
    public function crearOfertaControlador(){
        $objeto = trim($this->limpiarCadena($_POST['objeto'] ?? ''));
        $descripcion = trim($this->limpiarCadena($_POST['descripcion'] ?? ''));
        $moneda = trim($this->limpiarCadena($_POST['moneda'] ?? ''));
        $presupuesto = trim($this->limpiarCadena($_POST['presupuesto'] ?? ''));
        $actividad = trim($this->limpiarCadena($_POST['actividad'] ?? ''));
        $fecha_inicio = trim($this->limpiarCadena($_POST['fecha_inicio'] ?? ''));
        $fecha_fin = trim($this->limpiarCadena($_POST['fecha_cierre'] ?? ''));
        $hora_inicio = trim($this->limpiarCadena($_POST['hora_inicio'] ?? ''));
        $hora_fin = trim($this->limpiarCadena($_POST['hora_cierre'] ?? ''));

        // verificar campos obligatorios
        $campos = [
            $objeto, $descripcion, $moneda, $presupuesto,
            $actividad, $fecha_inicio, $fecha_fin,
            $hora_inicio, $hora_fin
        ];
        if (in_array("", $campos, true)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No has llenado todos los campos obligatorios",
                "icono" => "error"
            ]);
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $objeto)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,400}$", $descripcion)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if (!is_numeric($presupuesto) || $presupuesto <= 0 ||
            $this->verificarDatos("^(?!0(\.0{1,2})?$)\d+(\.\d{1,2})?$", $presupuesto)){
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El presupuesto debe ser un número mayor a 0 con máximo 2 decimales",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $monedas_permitidas = ["COP", "USD", "EUR"];
        if (!in_array($moneda, $monedas_permitidas)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La moneda no es válida",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if (
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_inicio) ||
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_fin)
        ) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Formato de fecha inválido",
                "icono" => "error"
            ]);
        }
        if (
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_inicio) ||
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_fin)
        ) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Formato de hora inválido",
                "icono" => "error"
            ]);
        }
        $inicio = new \DateTime("$fecha_inicio $hora_inicio");
        $fin = new \DateTime("$fecha_fin $hora_fin");
        if ($fin <= $inicio) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "La fecha y hora de fin debe ser mayor a la de inicio",
                "icono" => "error"
            ]);
        }

        $check_actividad = Actividad::where("id", $actividad)->first();
        if (!$check_actividad) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La actividad seleccionada no existe",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $actividad_id = $check_actividad->id; 
        $concecutivo_oferta = $this->crearConsecutivoOfertaControlador();
        $datos_oferta_reg = [
            "consecutivo" => $concecutivo_oferta,
            "objeto" => $objeto,
            "descripcion" => $descripcion,
            "moneda" => $moneda,
            "presupuesto" => $presupuesto,
            "actividad_id" => $actividad_id,
            "fecha_inicio" => $fecha_inicio,
            "fecha_cierre" => $fecha_fin,
            "hora_inicio" => $hora_inicio,
            "hora_cierre" => $hora_fin,
            "estado" => "creacion",
            "creado_en" => date("Y-m-d H:i:s"),
            "actualizado_en" => date("Y-m-d H:i:s")
        ];
        try{
            $nueva_oferta = Oferta::create($datos_oferta_reg);
            if ($nueva_oferta) {
                return json_encode([
                    "tipo" => "redireccionar",
                    "titulo" => "Oferta creada",
                    "texto" => "La oferta ha sido creada exitosamente",
                    "icono" => "success",
                    "url" => APP_URL . "?view=ofertasList/"
                ]);
            } else {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "Error",
                    "texto" => "No se pudo crear la oferta, por favor intente nuevamente",
                    "icono" => "error"
                ]);
            }
        } catch (\Exception $e) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Ocurrió un error al procesar la solicitud: " . $e->getMessage(),
                "icono" => "error"
            ]);
        }
    }
    public function crearConsecutivoOfertaControlador(){
        // consecutivo 
        $anio = date('y');

        $ultimo = Oferta::where('consecutivo', 'LIKE', "PO-%-$anio")
            ->orderBy('id', 'DESC')
            ->first();

        if ($ultimo) {
            $ultimo_numero = (int) explode('-', $ultimo->consecutivo)[1];
            $nuevo_numero = $ultimo_numero + 1;
        } else {
            $nuevo_numero = 1;
        }
        // ceros a la izquierda (4 dÃ­gitos)
        $consecutivo = str_pad($nuevo_numero, 4, '0', STR_PAD_LEFT);

        $indice_oferta = "PO-$consecutivo-$anio";
        return $indice_oferta;
    }

    public function ActualizarOfertaControlador(){
        $id = trim($this->limpiarCadena($_POST['oferta_id'] ?? ''));
        $objeto = trim($this->limpiarCadena($_POST['objeto'] ?? ''));
        $descripcion = trim($this->limpiarCadena($_POST['descripcion'] ?? ''));
        $moneda = trim($this->limpiarCadena($_POST['moneda'] ?? ''));
        $presupuesto = trim($this->limpiarCadena($_POST['presupuesto'] ?? ''));
        $actividad = trim($this->limpiarCadena($_POST['actividad'] ?? ''));
        $fecha_inicio = trim($this->limpiarCadena($_POST['fecha_inicio'] ?? ''));
        $fecha_fin = trim($this->limpiarCadena($_POST['fecha_cierre'] ?? ''));
        $hora_inicio = trim($this->limpiarCadena($_POST['hora_inicio'] ?? ''));
        $hora_fin = trim($this->limpiarCadena($_POST['hora_cierre'] ?? ''));

        // verificar campos obligatorios
        $campos = [
            $objeto, $descripcion, $moneda, $presupuesto,
            $actividad, $fecha_inicio, $fecha_fin,
            $hora_inicio, $hora_fin, $id
        ];
        if (in_array("", $campos, true)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No has llenado todos los campos obligatorios",
                "icono" => "error"
            ]);
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $objeto)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,400}$", $descripcion)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if (!is_numeric($presupuesto) || $presupuesto <= 0 ||
            $this->verificarDatos("^(?!0(\.0{1,2})?$)\d+(\.\d{1,2})?$", $presupuesto)){
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El presupuesto debe ser un número mayor a 0 con máximo 2 decimales",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $monedas_permitidas = ["COP", "USD", "EUR"];
        if (!in_array($moneda, $monedas_permitidas)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La moneda no es válida",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if (
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_inicio) ||
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_fin)
        ) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Formato de fecha inválido",
                "icono" => "error"
            ]);
        }
        if (
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_inicio) ||
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_fin)
        ) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Formato de hora inválido",
                "icono" => "error"
            ]);
        }
        $inicio = new \DateTime("$fecha_inicio $hora_inicio");
        $fin = new \DateTime("$fecha_fin $hora_fin");
        if ($fin <= $inicio) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "La fecha y hora de fin debe ser mayor a la de inicio",
                "icono" => "error"
            ]);
        }

        $check_actividad = Actividad::where("id", $actividad)->first();
        if (!$check_actividad) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La actividad seleccionada no existe",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $actividad_id = $check_actividad->id; 
        $datos_oferta_up = [
            "objeto" => $objeto,
            "descripcion" => $descripcion,
            "moneda" => $moneda,
            "presupuesto" => $presupuesto,
            "actividad_id" => $actividad_id,
            "fecha_inicio" => $fecha_inicio,
            "fecha_cierre" => $fecha_fin,
            "hora_inicio" => $hora_inicio,
            "hora_cierre" => $hora_fin,
            "estado" => "creacion",
            "actualizado_en" => date("Y-m-d H:i:s")
        ];
        try {
            $update_oferta = Oferta::where('id', $id)
                ->update($datos_oferta_up);
            if ($update_oferta) {
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Gasto registrado",
                    "texto" => "El gasto " . $descripcion . " ha sido registrado exitosamente",
                    "icono" => "success"
                ];
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el gasto, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
        } catch (\Exception $e) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error de base de datos",
                "texto" => "Error: " . $e->getMessage(),
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }
    public function getOfertaControlador($id){
        try{
            if (!is_numeric($id) || $id <= 0) {
                return [
                    'tipo' => 'simple',
                    'titulo' => 'Error',
                    'texto' => 'ID de oferta no válido',
                    'icono' => 'error'
                ];
            }
            $oferta = Oferta::find($id);
            if (!$oferta) {
                return [
                    'tipo' => 'simple',
                    'titulo' => 'No encontrado',
                    'texto' => 'La oferta no existe',
                    'icono' => 'error'
                ];
            }
            return [
                'id' => $oferta->id,
                'consecutivo' => $oferta->consecutivo,
                'objeto' => $oferta->objeto,
                'descripcion' => $oferta->descripcion,
                'moneda' => $oferta->moneda,
                'presupuesto' => $oferta->presupuesto,
                'actividad_id' => $oferta->actividad_id,
                'fecha_inicio' => $oferta->fecha_inicio,
                'hora_inicio' => $oferta->hora_inicio,
                'fecha_cierre' => $oferta->fecha_cierre,
                'hora_cierre' => $oferta->hora_cierre
            ];
        } catch (\Exception $e) {
            error_log("Error en getOfertaControlador: " . $e->getMessage());
            return [
                'tipo' => 'simple',
                'titulo' => 'Error',
                'texto' => 'Error al cargar la oferta',
                'icono' => 'error'
            ];
        }
    }

    public function listarOfertaControlador($pagina, $registros, $url, $descripcion, $consecutivo)
    {
        $pagina = $this->limpiarCadena($pagina);
        $registros = $this->limpiarCadena($registros);
        $descripcion = $this->limpiarCadena($descripcion);
        $consecutivo = $this->limpiarCadena($consecutivo);
        $url = $this->limpiarCadena($url);
        $url = APP_URL . "?view=" . $url . "/";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($registros * $pagina) - $registros) : 0;
        $registros = ($registros > 0) ? (int)$registros : 10;
        // consulta
        $query = Oferta::query();
        // Filtro por búsqueda
        if (!empty($descripcion)) {
            $query->where("descripcion", 'LIKE', "%$descripcion%");
        }
        if (!empty($consecutivo)) {
            $query->where("consecutivo", 'LIKE', "%$consecutivo%");
        }
        $consulta_total = (clone $query)->count();

        $consulta_datos = $query->orderBy('id', 'DESC')
            ->skip($inicio)
            ->take($registros)
            ->get();

        $numeroPaginas = ceil($consulta_total / $registros);

        return [
            'datos' => $consulta_datos,
            'total' => $consulta_total,
            'paginas' => $numeroPaginas,
            'url' => $url,
            'pagina_actual' => $pagina,
            'inicio' => $inicio + 1
        ];
    }
}