<?php

namespace App\Http\Controllers;
use App\Models\Actividad;

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
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
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

    public function listarActividadesControlador($pagina, $registros, $url, $segmento, $producto)
    {
        try {
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $segmento = $this->limpiarCadena($segmento);
            $producto = $this->limpiarCadena($producto);
            $url = $this->limpiarCadena($url);
            $url = APP_URL . "?view=" . $url . "/";
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
                'url' => $url,
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
