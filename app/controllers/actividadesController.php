<?php

namespace app\controllers;

use app\models\mainModel;
use App\Models\Actividad;

class actividadesController extends mainModel
{
    public function getActividades($id = null)
    {
        if (empty($id)) {
            return Actividad::select('id', 'producto')->get();
        }
        return Actividad::select('id', 'producto')->find($id);
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
        $query = Actividad::query();
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
