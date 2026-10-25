<?php

namespace App\Http\Controllers;

use App\models\Actividad;
use App\models\Oferta;
use Illuminate\Database\Capsule\Manager as Capsule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ActividadesController extends Controller
{
    public function getActividades($id, $usuario)
    {
        try {
            if (empty($id)) {
                $query = Actividad::select('id', 'producto', 'usuario_id');
                $actividades = $query->get();
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => $actividades
                ]);
                return;
            }
            $query = Actividad::select(
                'id',
                'codigo_segmento',
                'segmento',
                'codigo_familia',
                'familia',
                'codigo_clase',
                'clase',
                'codigo_producto',
                'producto',
                'usuario_id'
            );
            $actividad = $query->find($id);
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
        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $th->getMessage()
            ]);
            return;
        }
    }

    public function InsertActividadControlador($usuario)
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $cod_segmento = trim($this->limpiarCadena($input['cod_segmento'] ?? ''));
            $nom_segmento = trim($this->limpiarCadena($input['nom_segmento'] ?? ''));
            $cod_familia = trim($this->limpiarCadena($input['cod_familia'] ?? ''));
            $nom_familia = trim($this->limpiarCadena($input['nom_familia'] ?? ''));
            $cod_clase = trim($this->limpiarCadena($input['cod_clase'] ?? ''));
            $nom_clase = trim($this->limpiarCadena($input['nom_clase'] ?? ''));
            $cod_product = trim($this->limpiarCadena($input['cod_product'] ?? ''));
            $nom_product = trim($this->limpiarCadena($input['nom_product'] ?? ''));

            // verificar campos obligatorios
            $camposLet = [
                $nom_clase,
                $nom_familia,
                $nom_segmento,
                $nom_product
            ];
            $camposNum = [
                $cod_clase,
                $cod_familia,
                $cod_segmento,
                $cod_product
            ];

            $campos = array_merge($camposLet, $camposNum);

            if (in_array("", $campos, true)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No has llenado todos los campos obligatorios"
                ]);
                return;
            }
            #Verificando integridad de los datos
            foreach ($camposLet as $campo) {
                if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $campo)) {
                    http_response_code(400);
                    echo json_encode([
                        "code" => 400,
                        "data" => "Los campos de texto no coinciden con el formato solicitado"
                    ]);
                    return;
                }
            }
            foreach ($camposNum as $campo) {
                if ($this->verificarDatos("^(?!\s*$)[0-9]{1,10}$", $campo)) {
                    http_response_code(400);
                    echo json_encode([
                        "code" => 400,
                        "data" => "Los campos numéricos no coinciden con el formato solicitado"
                    ]);
                    return;
                }
            }

            $datos_actividad_reg = [
                "codigo_segmento" => $cod_segmento,
                "segmento" => $nom_segmento,
                "codigo_familia" => $cod_familia,
                "familia" => $nom_familia,
                "codigo_clase" => $cod_clase,
                "clase" => $nom_clase,
                "codigo_producto" => $cod_product,
                "producto" => $nom_product,
                "usuario_id" => $usuario->id
            ];
            $nueva_actividad = Actividad::create($datos_actividad_reg);
            if ($nueva_actividad) {
                http_response_code(201);
                echo json_encode([
                    "code" => 201,
                    "data" => "La actividad ha sido creada exitosamente"
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No se pudo crear la actividad, por favor intente nuevamente"
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["code" => 500, "data" => $e->getMessage()]);
        }
    }

    public function ActualizarActividadControlador($usuario){
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $actividad = trim($this->limpiarCadena($input['id_actividad'] ?? ''));
            $actividadExistente = Actividad::find($actividad);
            if (!$actividadExistente) {
                http_response_code(404);
                echo json_encode([
                    "code" => 404,
                    "data" => "La actividad no existe"
                ]);
                return;
            }
            if (!$this->puedeGestionarRegistro($usuario, $actividadExistente->usuario_id)) {
                $this->responderAccesoDenegado();
                return;
            }
            $cod_segmento = trim($this->limpiarCadena($input['cod_segmento'] ?? ''));
            $nom_segmento = trim($this->limpiarCadena($input['nom_segmento'] ?? ''));
            $cod_familia = trim($this->limpiarCadena($input['cod_familia'] ?? ''));
            $nom_familia = trim($this->limpiarCadena($input['nom_familia'] ?? ''));
            $cod_clase = trim($this->limpiarCadena($input['cod_clase'] ?? ''));
            $nom_clase = trim($this->limpiarCadena($input['nom_clase'] ?? ''));
            $cod_product = trim($this->limpiarCadena($input['cod_product'] ?? ''));
            $nom_product = trim($this->limpiarCadena($input['nom_product'] ?? ''));

            // verificar campos obligatorios
            $camposLet = [
                $nom_clase,
                $nom_familia,
                $nom_segmento,
                $nom_product
            ];
            $camposNum = [
                $cod_clase,
                $cod_familia,
                $cod_segmento,
                $cod_product
            ];

            $campos = array_merge($camposLet, $camposNum);

            if (in_array("", $campos, true)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No has llenado todos los campos obligatorios"
                ]);
                return;
            }

            $datos_actividad_up = [
                "codigo_segmento" => $cod_segmento,
                "segmento" => $nom_segmento,
                "codigo_familia" => $cod_familia,
                "familia" => $nom_familia,
                "codigo_clase" => $cod_clase,
                "clase" => $nom_clase,
                "codigo_producto" => $cod_product,
                "producto" => $nom_product
            ];

            $update_actividad = Actividad::where('id', $actividad)
                ->update($datos_actividad_up);
            if ($update_actividad !== false) {
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => "la actividad " . $actividad . " ha sido actualizada exitosamente"
                ]);
                return;
            }
            else {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No se pudo registrar la actividad, por favor intente nuevamente"
                ]);
                return;
            }


        } catch (\Throwable $th) {
            error_log("Error en crearActividadMasivoControlador: " . $th->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error al procesar el cargue masivo, intente nuevamente"
            ]);
            return;
        }
    }

    public function crearActividadMasivoControlador($usuario)
    {
        try {
            $archivo = $_FILES['archivo_actividades'] ?? null;
            if (!$archivo || ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Debe adjuntar un archivo Excel (.xlsx)"
                ]);
                return;
            }
            #Validando la extensión del archivo
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            if ($extension !== 'xlsx') {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "Archivo no permitido, solo se permiten archivos .xlsx"
                ]);
                return;
            }
            #Limitando el peso del archivo a 10MB
            if (($archivo['size'] / 1024) > 10240) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El archivo no puede ser mayor a 10MB"
                ]);
                return;
            }
            #Leyendo el excel
            try {
                $lector = IOFactory::createReaderForFile($archivo['tmp_name']);
                $lector->setReadDataOnly(true);
                $documento = $lector->load($archivo['tmp_name']);
            } catch (\Throwable $th) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "No se pudo leer el archivo, verifique que sea un Excel .xlsx válido"
                ]);
                return;
            }

            $hoja = $documento->getActiveSheet();
            $filas = $hoja->toArray(null, true, false, false);

            $patronTexto = "/^[\p{L}\p{N}\s,()\-\/&'#;%°]{1,200}$/u";
            $patronNumero = "/^\d{1,10}$/";

            $errores = [];
            $registros = [];
            $productosEnArchivo = [];
            $numeroFila = 0;

            foreach ($filas as $fila) {
                $numeroFila++;
                #Se omite la fila de encabezados
                if ($numeroFila === 1) {
                    continue;
                }
                $fila = array_map(function ($valor) {
                    return trim((string)$valor);
                }, $fila);
                #Se omiten las filas completamente vacías
                if (count(array_filter($fila, function ($valor) {
                    return $valor !== '';
                })) === 0) {
                    continue;
                }

                $cod_segmento = trim($this->limpiarCadena($fila[0] ?? ''));
                $nom_segmento = trim($this->limpiarCadena($fila[1] ?? ''));
                $cod_familia  = trim($this->limpiarCadena($fila[2] ?? ''));
                $nom_familia  = trim($this->limpiarCadena($fila[3] ?? ''));
                $cod_clase    = trim($this->limpiarCadena($fila[4] ?? ''));
                $nom_clase    = trim($this->limpiarCadena($fila[5] ?? ''));
                $cod_producto = trim($this->limpiarCadena($fila[6] ?? ''));
                $nom_producto = trim($this->limpiarCadena($fila[7] ?? ''));

                $obligatorios = [$cod_segmento, $nom_segmento, $cod_familia, $nom_familia, $cod_clase, $nom_clase, $cod_producto, $nom_producto];
                if (in_array("", $obligatorios, true)) {
                    $errores[] = ["fila" => $numeroFila, "motivo" => "Tiene campos obligatorios vacíos"];
                    continue;
                }
                #Validando los campos de texto
                $camposTexto = [$nom_segmento, $nom_familia, $nom_clase, $nom_producto];
                $textoInvalido = false;
                foreach ($camposTexto as $campo) {
                    if (!preg_match($patronTexto, $campo)) {
                        $textoInvalido = true;
                        break;
                    }
                }
                if ($textoInvalido) {
                    $errores[] = ["fila" => $numeroFila, "motivo" => "Los campos de texto no coinciden con el formato solicitado (máximo 200 caracteres)"];
                    continue;
                }
                #Validando los campos numéricos
                $camposNumero = [$cod_segmento, $cod_familia, $cod_clase, $cod_producto];
                $numeroInvalido = false;
                foreach ($camposNumero as $campo) {
                    if (!preg_match($patronNumero, $campo)) {
                        $numeroInvalido = true;
                        break;
                    }
                }
                if ($numeroInvalido) {
                    $errores[] = ["fila" => $numeroFila, "motivo" => "Los campos de código deben ser numéricos (máximo 10 dígitos)"];
                    continue;
                }
                #Validando duplicados dentro del mismo archivo
                if (isset($productosEnArchivo[$cod_producto])) {
                    $errores[] = ["fila" => $numeroFila, "motivo" => "El codigo_producto $cod_producto está repetido en el archivo (fila " . $productosEnArchivo[$cod_producto] . ")"];
                    continue;
                }
                $productosEnArchivo[$cod_producto] = $numeroFila;

                $registros[] = [
                    "fila" => $numeroFila,
                    "codigo_segmento" => (int)$cod_segmento,
                    "segmento" => $nom_segmento,
                    "codigo_familia" => (int)$cod_familia,
                    "familia" => $nom_familia,
                    "codigo_clase" => (int)$cod_clase,
                    "clase" => $nom_clase,
                    "codigo_producto" => (int)$cod_producto,
                    "producto" => $nom_producto
                ];
            }

            if (empty($registros) && empty($errores)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El archivo no contiene actividades para cargar"
                ]);
                return;
            }
            #Validando duplicados contra la base de datos
            if (!empty($registros)) {
                $codigos = array_column($registros, 'codigo_producto');
                $existentes = array_flip(Actividad::whereIn('codigo_producto', $codigos)->pluck('codigo_producto')->all());
                foreach ($registros as $indice => $registro) {
                    if (isset($existentes[$registro['codigo_producto']])) {
                        $errores[] = ["fila" => $registro['fila'], "motivo" => "El codigo producto " . $registro['codigo_producto'] . " ya existe en el sistema"];
                        unset($registros[$indice]);
                    }
                }
                $registros = array_values($registros);
            }
            #Si hay errores no se carga nada
            if (!empty($errores)) {
                usort($errores, function ($a, $b) {
                    return $a['fila'] <=> $b['fila'];
                });
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El archivo contiene " . count($errores) . " fila(s) con error. No se cargó ninguna actividad.",
                    "errores" => $errores
                ]);
                return;
            }

            $datos = array_map(function ($registro) use ($usuario) {
                unset($registro['fila']);
                $registro['usuario_id'] = $usuario->id;
                return $registro;
            }, $registros);

            try {
                Capsule::connection()->beginTransaction();
                foreach (array_chunk($datos, 500) as $lote) {
                    Actividad::insert($lote);
                }
                Capsule::connection()->commit();
            } catch (\Throwable $th) {
                Capsule::connection()->rollBack();
                throw $th;
            }

            http_response_code(201);
            echo json_encode([
                "code" => 201,
                "data" => "Se cargaron " . count($datos) . " actividades correctamente"
            ]);
            return;
        } catch (\Throwable $th) {
            error_log("Error en crearActividadMasivoControlador: " . $th->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error al procesar el cargue masivo, intente nuevamente"
            ]);
            return;
        }
    }

    public function descargarPlantillaControlador()
    {
        try {
            $documento = new Spreadsheet();
            $hoja = $documento->getActiveSheet();
            $hoja->setTitle('Actividades');
            $encabezados = ['codigo segmento', 'segmento', 'codigo familia', 'familia', 'codigo clase', 'clase', 'codigo producto', 'producto'];
            $hoja->fromArray($encabezados, null, 'A1');
            $ejemplo = [10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101501, 'Gatos'];
            $hoja->fromArray($ejemplo, null, 'A2');
            foreach (range('A', 'H') as $columna) {
                $hoja->getColumnDimension($columna)->setAutoSize(true);
            }
            $nombre_archivo = 'plantilla_actividades.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
            header('Cache-Control: max-age=0');
            $escritor = new Xlsx($documento);
            $escritor->save('php://output');
            exit;
        } catch (\Throwable $th) {
            error_log("Error en descargarPlantillaControlador: " . $th->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error al generar la plantilla, intente nuevamente"
            ]);
            return;
        }
    }

    public function eliminarActividadControlador($id_actividad, $usuario)
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
            if (!$this->puedeGestionarRegistro($usuario, $actividad->usuario_id)) {
                $this->responderAccesoDenegado();
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
                    "data" => "La actividad" . $actividad['producto'] . " ha sido eliminada del sistema correctamente"
                ]);
                return;
            } else {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => "No hemos podido eliminar la actividad" . $actividad['producto'] . " del sistema, por favor intente nuevamente"
                ]);
                return;
            }
        } catch (\Exception $e) {
            error_log("Error en eliminar la actividad: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al eliminar el actividad, intente nuevamente' . $e->getMessage()
            ]);
            return;
        }
    }

    public function listarActividadesControlador($pagina, $registros, $segmento, $producto, $usuario)
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
        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => "Error: " . $th->getMessage()
            ]);
            return;
        }
    }
}
