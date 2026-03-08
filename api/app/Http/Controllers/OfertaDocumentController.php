<?php

namespace App\Http\Controllers;

use App\Models\OfertaDocumento;

class ofertaDocumentController extends Controller
{
    public function crearOfertaDocumentControlador()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $descripcion = trim($this->limpiarCadena($input['descripcion_Adjunto'] ?? ''));
        $titulo = trim($this->limpiarCadena($input['titulo_adjunto'] ?? ''));
        $id_oferta = trim($this->limpiarCadena($input['oferta_id'] ?? ''));

        // verificar campos obligatorios
        $campos = [
            $titulo,
            $descripcion
        ];
        if (in_array("", $campos, true)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'No has llenado todos los campos que son obligatorios'
            ]);
            return;
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $descripcion)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'La descripcion no coincide con el formato solicitado'
            ]);
            return;
        }
        if (!is_numeric($id_oferta) || $id_oferta <= 0) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'ID de oferta no válido'
            ]);
            return;
        }
        if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,150}$", $titulo)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre del titulo no coincide con el formato solicitado'
            ]);
            return;
        }
        $doc_dir = "../views/docs/uploads/ofertas/";
        $document_name = $_FILES['gasto_documento']['name'];
        if ($document_name != "" && $_FILES['gasto_documento']['size'] > 0) {
            //  creando directorio si no existe
            if (!file_exists($doc_dir)) {
                if (!mkdir($doc_dir, 0777)) {
                    http_response_code(500);
                    echo json_encode([
                        "code" => 500,
                        "data" => 'No se pudo crear el directorio'
                    ]);
                    return;
                }
            }
            // limitar que tipo de archivo
            $mimePermitidos = [
                'application/pdf',
                'application/zip'
            ];

            $mimeArchivo = mime_content_type($_FILES['gasto_documento']['tmp_name']);

            if (!in_array($mimeArchivo, $mimePermitidos)) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'Archivo no permitido, solo se permiten archivos .pdf, .zip'
                ]);
                return;
            }
            # limitar el peso del archivo
            if (($_FILES['gasto_documento']['size'] / 1024) > 10000) { // 10MB
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'El archivo no puede ser mayor a 10MB'
                ]);
                return;
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

            // mover el doc al directorio de imagenes
            if (!move_uploaded_file($_FILES['gasto_documento']['tmp_name'], $doc_dir . $archivo_ofertas)) {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => 'Error al subir el archivo, intente nuevamente'
                ]);
                return;
            }
        }
        else {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'Debe seleccionar un adjunto'
            ]);
            return;
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
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => 'Adjunto creado exitosamente'
                ]);
                return;
            }
            else {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => 'Error al crear el adjunto, intente nuevamente'
                ]);
            }
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    public function getOfertaDocumentControlador($id_of)
    {
        try {
            if (!is_numeric($id_of) || $id_of <= 0) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'ID de oferta no válido'
                ]);
                return;
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
        }
        catch (\Exception $e) {
            error_log("Error en getOfertaControlador: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al cargar la oferta'
            ]);
            return;
        }
    }

    public function eliminarOfertaDocumentControlador()
    {
        $id = trim($this->limpiarCadena($_POST['document_id'] ?? ''));
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'ID de documento no válido'
                ]);
                return;
            }
            $oferta_doc = OfertaDocumento::where("id", $id)->first();
            if (!$oferta_doc) {
                http_response_code(404);
                echo json_encode([
                    "code" => 404,
                    "data" => 'La oferta no existe'
                ]);
                return;
            }
            $eliminar_doc = OfertaDocumento::destroy($id);
            if ($eliminar_doc) {
                if (is_file($oferta_doc['ruta_archivo'] . $oferta_doc['archivo'])) {
                    chmod($oferta_doc['ruta_archivo'] . $oferta_doc['archivo'], 0777);
                    unlink($oferta_doc['ruta_archivo'] . $oferta_doc['archivo']);
                }
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => "El archivo " . $oferta_doc['archivo'] . " ha sido eliminado del sistema correctamente"
                ]);
                return;
            }
            else {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => "No hemos podido eliminar el archivo " . $oferta_doc['archivo'] . " del sistema, por favor intente nuevamente"
                ]);
                return;
            }
        }
        catch (\Exception $e) {
            error_log("Error en eliminargetOfertaDocumentControlar: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al eliminar el documento, intente nuevamente'
            ]);
            return;
        }
    }
}
