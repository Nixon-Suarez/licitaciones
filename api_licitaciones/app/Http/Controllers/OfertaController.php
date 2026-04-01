<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Actividad;

class ofertaController extends Controller
{
    public function InsertOfertaControlador()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $objeto = trim($this->limpiarCadena($input['objeto'] ?? ''));
            $descripcion = trim($this->limpiarCadena($input['descripcion'] ?? ''));
            $moneda = trim($this->limpiarCadena($input['moneda'] ?? ''));
            $presupuesto = trim($this->limpiarCadena($input['presupuesto'] ?? ''));
            $actividad = trim($this->limpiarCadena($input['actividad'] ?? ''));
            $fecha_inicio = trim($this->limpiarCadena($input['fecha_inicio'] ?? ''));
            $fecha_fin = trim($this->limpiarCadena($input['fecha_cierre'] ?? ''));
            $hora_inicio = trim($this->limpiarCadena($input['hora_inicio'] ?? ''));
            $hora_fin = trim($this->limpiarCadena($input['hora_cierre'] ?? ''));

            // verificar campos obligatorios
            $campos = [
                $objeto, $descripcion, $moneda, $presupuesto,
                $actividad, $fecha_inicio, $fecha_fin,
                $hora_inicio, $hora_fin
            ];
            if (in_array("", $campos, true)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No has llenado todos los campos obligatorios"
                ]);
                return;
            }
            #Verificando integridad de los datos
            if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $objeto)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El nombre de la categoría no coincide con el formato solicitado"
                ]);
                return;
            }
            if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,400}$", $descripcion)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La descripcion de la categoría no coincide con el formato solicitado"
                ]);
                return;
            }
            if (!is_numeric($presupuesto) || $presupuesto <= 0 ||
            $this->verificarDatos("^(?!0(\.0{1,2})?$)\d+(\.\d{1,2})?$", $presupuesto)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El presupuesto debe ser un número mayor a 0 con máximo 2 decimales"
                ]);
                return;
            }
            $monedas_permitidas = ["COP", "USD", "EUR"];
            if (!in_array($moneda, $monedas_permitidas)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La moneda no es válida"
                ]);
                return;
            }
            if (
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_inicio) ||
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_fin)
            ) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Formato de fecha inválido"
                ]);
                return;
            }
            if (
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_inicio) ||
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_fin)
            ) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Formato de hora inválido"
                ]);
                return;
            }
            $inicio = new \DateTime("$fecha_inicio $hora_inicio");
            $fin = new \DateTime("$fecha_fin $hora_fin");
            if ($fin <= $inicio) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La fecha y hora de fin debe ser mayor a la de inicio"
                ]);
                return;
            }

            $check_actividad = Actividad::where("id", $actividad)->first();
            if (!$check_actividad) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La actividad seleccionada no existe"
                ]);
                return;
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
            $nueva_oferta = Oferta::create($datos_oferta_reg);
            if ($nueva_oferta) {
                http_response_code(201);
                echo json_encode([
                    "code" => 201, 
                    "data" => "La oferta ha sido creada exitosamente"
                ]);
            }
            else {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No se pudo crear la oferta, por favor intente nuevamente"
                ]);
            }
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["code" => 500, "data" => $e->getMessage()]);
        }
    }
    public function crearConsecutivoOfertaControlador()
    {   
        try{
            // consecutivo 
            $anio = date('y');

            $ultimo = Oferta::where('consecutivo', 'LIKE', "PO-%-$anio")
                ->orderBy('id', 'DESC')
                ->first();

            if ($ultimo) {
                $ultimo_numero = (int)explode('-', $ultimo->consecutivo)[1];
                $nuevo_numero = $ultimo_numero + 1;
            }
            else {
                $nuevo_numero = 1;
            }
            // ceros a la izquierda (4 dÃ­gitos)
            $consecutivo = str_pad($nuevo_numero, 4, '0', STR_PAD_LEFT);

            $indice_oferta = "PO-$consecutivo-$anio";
            return $indice_oferta;
        }catch (\Exception $e) {
            throw new \Exception("Error al generar el consecutivo: " . $e->getMessage());
        }
    }
    public function ActualizarOfertaControlador()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            $id = trim($this->limpiarCadena($input['oferta_id'] ?? ''));
            $objeto = trim($this->limpiarCadena($input['objeto'] ?? ''));
            $descripcion = trim($this->limpiarCadena($input['descripcion'] ?? ''));
            $moneda = trim($this->limpiarCadena($input['moneda'] ?? ''));
            $presupuesto = trim($this->limpiarCadena($input['presupuesto'] ?? ''));
            $actividad = trim($this->limpiarCadena($input['actividad'] ?? ''));
            $fecha_inicio = trim($this->limpiarCadena($input['fecha_inicio'] ?? ''));
            $fecha_fin = trim($this->limpiarCadena($input['fecha_cierre'] ?? ''));
            $hora_inicio = trim($this->limpiarCadena($input['hora_inicio'] ?? ''));
            $hora_fin = trim($this->limpiarCadena($input['hora_cierre'] ?? ''));

            // verificar campos obligatorios
            $campos = [
                $objeto, $descripcion, $moneda, $presupuesto,
                $actividad, $fecha_inicio, $fecha_fin,
                $hora_inicio, $hora_fin, $id
            ];
            if (in_array("", $campos, true)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No has llenado todos los campos obligatorios"
                ]);
                return;
            }
            #Verificando integridad de los datos
            if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $objeto)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El nombre de la categoría no coincide con el formato solicitado"
                ]);
                return;
            }
            if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,400}$", $descripcion)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El nombre de la categoría no coincide con el formato solicitado"
                ]);
                return;
            }
            if (!is_numeric($presupuesto) || $presupuesto <= 0 ||
            $this->verificarDatos("^(?!0(\.0{1,2})?$)\d+(\.\d{1,2})?$", $presupuesto)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El presupuesto debe ser un número mayor a 0 con máximo 2 decimales"
                ]);
                return;
            }
            $monedas_permitidas = ["COP", "USD", "EUR"];
            if (!in_array($moneda, $monedas_permitidas)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La moneda no es válida"
                ]);
                return;
            }
            if (
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_inicio) ||
            $this->verificarDatos('\d{4}-\d{2}-\d{2}', $fecha_fin)
            ) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Formato de fecha inválido"
                ]);
                return;
            }
            if (
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_inicio) ||
            $this->verificarDatos('([01]\d|2[0-3]):[0-5]\d', $hora_fin)
            ) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Formato de hora inválido"
                ]);
                return;

            }
            $inicio = new \DateTime("$fecha_inicio $hora_inicio");
            $fin = new \DateTime("$fecha_fin $hora_fin");
            if ($fin <= $inicio) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La fecha y hora de fin debe ser mayor a la de inicio"
                ]);
                return;
            }

            $check_actividad = Actividad::where("id", $actividad)->first();
            if (!$check_actividad) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La actividad seleccionada no existe"
                ]);
                return;
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
            $update_oferta = Oferta::where('id', $id)
                ->update($datos_oferta_up);
            if ($update_oferta) {
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => "la oferta " . $id . " ha sido actualizada exitosamente"
                ]);
                return;
            }
            else {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No se pudo registrar la oferta, por favor intente nuevamente"
                ]);
                return;
            }
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["code" => 500, "data" => $e->getMessage()]);
        }
    }
    public function getOfertaControlador($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "ID de oferta no válido"
                ]);
                return;
            }
            $oferta = Oferta::find($id);
            if (!$oferta) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "La oferta no existe"
                ]);
                return;
            }
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => [
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
                ]
            ]);
            return;
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $e->getMessage()
            ]);
            return;
        }
    }

    public function listarOfertaControlador($pagina, $registros, $descripcion, $consecutivo)
    {
        try {
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $descripcion = $this->limpiarCadena($descripcion);
            $consecutivo = $this->limpiarCadena($consecutivo);
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
                ->get()
                ->map(function($oferta) {
                    return [
                        'id' => $oferta->id,
                        'consecutivo' => $oferta->consecutivo,
                        'objeto' => $oferta->objeto,
                        'descripcion' => $oferta->descripcion,
                        'moneda' => $oferta->moneda,
                        'presupuesto' => $oferta->presupuesto,
                        'actividad_id' => $oferta->actividad_id,
                        'fecha_inicio' => $oferta->fecha_inicio->format('Y-m-d'),
                        'fecha_cierre' => $oferta->fecha_cierre->format('Y-m-d'),
                        'hora_inicio' => $oferta->hora_inicio,
                        'hora_cierre' => $oferta->hora_cierre,
                        'estado' => $oferta->estado,
                        'creado_en' => $oferta->creado_en,
                        'actualizado_en' => $oferta->actualizado_en,
                    ];
                });

            $numeroPaginas = ceil($consulta_total / $registros);

            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => [
                    'datos' => $consulta_datos,
                    'total' => $consulta_total,
                    'paginas' => $numeroPaginas,
                    'pagina_actual' => $pagina,
                    'inicio' => $inicio + 1
                ]
            ]);
            return;
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $e->getMessage()
            ]);
            return;
        }
    }
}