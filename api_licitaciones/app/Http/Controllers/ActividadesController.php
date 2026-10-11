<?php

namespace App\Http\Controllers;
use App\models\Actividad;
use App\models\Oferta;

class ActividadesController extends Controller
{
    public function getActividades($id = null)
    {
        try {
            if (empty($id)) {
                $actividades = Actividad::select('id', 'producto')->get();
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => $actividades
                ]);
                return;
            }
            $actividad = Actividad::select('id', 'producto')->find($id);
            if (!$actividad) {
                http_response_code(404);
                echo json_encode([
                    "code" => 404,
                    "data" => "La actividad no existe"
                ]);
                return;
            }
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => $actividad
            ]);
            return;
        }
        catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $th->getMessage()
            ]);
            return;
        }
    }

    public function eliminarActividadControlador($id_actividad)
    {
        $id = trim($this->limpiarCadena($id_actividad ?? ''));
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'ID de actividad no válido'
                ]);
                return;
            }
            $actividad = Actividad::where("id", $id_actividad)->first();
            if (!$actividad) {
                http_response_code(404);
                echo json_encode([
                    "code" => 404,
                    "data" => 'La actividad no existe'
                ]);
                return;
            }
            $consecutivos = Oferta::where("actividad_id", $actividad->id)
                ->pluck('consecutivo');
            if ($consecutivos->isNotEmpty()) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'No se puede eliminar la actividad porque tiene ofertas asociadas con los consecutivos: ' . $consecutivos->implode(', ')
                ]);
                return;
            }
            $eliminar_actividad = Actividad::destroy($id);
            if ($eliminar_actividad) {
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => "La actividad" . $actividad['producto']." ha sido eliminada del sistema correctamente"
                ]);
                return;
            }
            else {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => "No hemos podido eliminar la actividad" . $actividad['producto'] . " del sistema, por favor intente nuevamente"]);
                return;
            }
        }
        catch (\Exception $e) {
            error_log("Error en eliminar la actividad: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al eliminar el actividad, intente nuevamente' . $e->getMessage()
            ]);
            return;
        }
    }

    public function listarActividadesControlador($pagina, $registros, $segmento, $producto)
    {
        try {
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $segmento = $this->limpiarCadena($segmento);
            $producto = $this->limpiarCadena($producto);
            $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
            $inicio = ($pagina > 0) ? (($registros * $pagina) - $registros) : 0;
            $registros = ($registros > 0) ? (int)$registros : 10;
            // consulta
            $query = Actividad::query();
            // Filtro por búsqueda
            if (!empty($segmento)) {
                $query->where("segmento", 'LIKE', "%$segmento%");
            }
            if (!empty($producto)) {
                $query->where("producto", 'LIKE', "%$producto%");
            }
            $consulta_total = (clone $query)->count();

            $consulta_datos = $query->orderBy('id', 'DESC')
                ->skip($inicio)
                ->take($registros)
                ->get();

            $numeroPaginas = ceil($consulta_total / $registros);

            $actividades = [
                'datos' => $consulta_datos,
                'total' => $consulta_total,
                'paginas' => $numeroPaginas,
                'pagina_actual' => $pagina,
                'inicio' => $inicio + 1
            ];
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => $actividades
            ]);
            return;
        }
        catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $th->getMessage()
            ]);
            return;
        }
    }
}
