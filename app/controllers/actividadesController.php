<?php

namespace app\controllers;

use app\models\mainModel;
use App\Models\Actividad;

class ActividadesController extends mainModel
{
    public function getActividades($id = null)
    {
        if (empty($id)) {
            return Actividad::select('id', 'producto')->get();
        }
        return Actividad::select('id', 'producto')->find($id);
    }

    public function listarActividadesControlador($pagina, $registros, $url, $segmento, $producto)
    {
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
