<?php

namespace app\controllers;

use App\Models\OfertaDocumento;
use app\models\mainModel;

class ofertaDocumentController extends mainModel
{
    public function crearOfertaDocumentControlador()
    {
        $descripcion = trim($this->limpiarCadena($_POST['descripcion_Adjunto'] ?? ''));
        $titulo = trim($this->limpiarCadena($_POST['titulo_adjunto'] ?? ''));
        $id_oferta = trim($this->limpiarCadena($_POST['oferta_id'] ?? ''));

        // verificar campos obligatorios
        $campos = [
            $titulo,
            $descripcion
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
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $descripcion)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "La descripcion no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if (!is_numeric($id_oferta) || $id_oferta <= 0) {
            return [
                'tipo' => 'simple',
                'titulo' => 'Error',
                'texto' => 'ID de oferta no válido',
                'icono' => 'error'
            ];
        }
        if ($this->verificarDatos("[0-9]{3,100}", $titulo)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre del titulo no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $doc_dir = "../views/docs/uploads/ofertas/";
        $document_name = $_FILES['gasto_documento']['name'];
        if ($document_name != "" && $_FILES['gasto_documento']['size'] > 0) {
            //  creando directorio si no existe
            if (!file_exists($doc_dir)) {
                if (!mkdir($doc_dir, 0777)) {
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "No se pudo crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                }
            }
            // limitar que tipo de archivo
            $mimePermitidos = [
                'application/pdf',
                'application/zip'
            ];

            $mimeArchivo = mime_content_type($_FILES['gasto_documento']['tmp_name']);

            if (!in_array($mimeArchivo, $mimePermitidos)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Archivo no permitido, solo se permiten archivos .pdf, .zip",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            # limitar el peso del archivo
            if (($_FILES['gasto_documento']['size'] / 1024) > 10000) { // 10MB
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El archivo no puede ser mayor a 10MB",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Extencion del archivo
            switch ($mimeArchivo) {
                case 'application/pdf':
                    $extension = '.pdf';
                    break;
                case 'application/zip':
                    $extension = '.zip';
                    break;
                default:
                    $extension = '.pdf';
            }

            chmod($doc_dir, 0777);

            // renombra la archivo_ofertas
            $nombreLimpio = str_ireplace(" ", "_", pathinfo($document_name, PATHINFO_FILENAME));
            $archivo_ofertas = $nombreLimpio . "_" . rand(1000, 9999) . "_" . time() . $extension;

            // mover la img al directorio de imagenes
            if (!move_uploaded_file($_FILES['gasto_documento']['tmp_name'], $doc_dir . $archivo_ofertas)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Error al subir el archivo, intente nuevamente",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "Debe seleccionar un adjunto",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $datos_oferta_reg = [
            "licitacion_id" => $id_oferta,
            "descripcion" => $descripcion,
            "titulo" => $titulo,
            "archivo" => $archivo_ofertas,
            "ruta_archivo" => $doc_dir,
            "creado_en" => date("Y-m-d H:i:s")
        ];
        try {
            $nueva_oferta = OfertaDocumento::create($datos_oferta_reg);
            if ($nueva_oferta) {
                return json_encode([
                    "tipo" => "recargar",
                    "titulo" => "Adjunto creado",
                    "texto" => "El adjunto ha sido añadido exitosamente",
                    "icono" => "success",
                ]);
            } else {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "Error",
                    "texto" => "No se pudo el adjunto, por favor intente nuevamente",
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

    public function getOfertaDocumentControlar($id_of)
    {
        try {
            if (!is_numeric($id_of) || $id_of <= 0) {
                return [
                    'tipo' => 'simple',
                    'titulo' => 'Error',
                    'texto' => 'ID de oferta no válido',
                    'icono' => 'error'
                ];
            }
            $oferta_docs = OfertaDocumento::where("licitacion_id", $id_of)->get();
            // si no hay documentos, devolver un arreglo vacío (para que `empty()` funcione en las vistas)
            if ($oferta_docs->isEmpty()) {
                return [];
            }

            $mapped = $oferta_docs->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'licitacion_id' => $doc->licitacion_id,
                    'titulo' => $doc->titulo,
                    'descripcion' => $doc->descripcion,
                    'archivo' => $doc->archivo,
                ];
            });
            return $mapped->all();
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

    public function eliminargetOfertaDocumentControlar()
    {
        $id = trim($this->limpiarCadena($_POST['document_id'] ?? ''));
        try {
            if (!is_numeric($id) || $id <= 0) {
                return [
                    'tipo' => 'simple',
                    'titulo' => 'Error',
                    'texto' => 'ID de oferta no válido',
                    'icono' => 'error'
                ];
            }
            $oferta_doc = OfertaDocumento::where("id", $id)->first();
            if (!$oferta_doc) {
                return [
                    'tipo' => 'simple',
                    'titulo' => 'No encontrado',
                    'texto' => 'La oferta no existe',
                    'icono' => 'error'
                ];
            }
            $eliminar_doc = OfertaDocumento::destroy($id);
            if ($eliminar_doc) {
                if (is_file($oferta_doc['ruta_archivo'] . $oferta_doc['archivo'])) {
                    chmod($oferta_doc['ruta_archivo'] . $oferta_doc['archivo'], 0777);
                    unlink($oferta_doc['ruta_archivo'] . $oferta_doc['archivo']);
                }
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Documento eliminado",
                    "texto" => "El archivo " . $oferta_doc['archivo'] . " ha sido eliminado del sistema correctamente",
                    "icono" => "success"
                ];
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hemos podido eliminar el archivo " . $oferta_doc['archivo'] . " del sistema, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        } catch (\Exception $e) {
            error_log("Error en eliminargetOfertaDocumentControlar: " . $e->getMessage());
            return [
                'tipo' => 'simple',
                'titulo' => 'Error',
                'texto' => 'Error al cargar la oferta',
                'icono' => 'error'
            ];
        }
    }
}
