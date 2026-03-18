<?php
namespace App\Http\Controllers;
use App\Models\Usuario;

use api\Request;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function registrarUsuarioControlador()
    {
        #Almacenar Datos
        $input = json_decode(file_get_contents("php://input"), true);
        $usuario_nombre = trim($this->limpiarCadena($input['register_nombre'] ?? ''));
        $usuario_apellido = trim($this->limpiarCadena($input['register_apellido'] ?? ''));
        $usuario = trim($this->limpiarCadena($input['register_usuario'] ?? ''));
        $usuario_clave_1 = trim($this->limpiarCadena($input['register_clave1'] ?? ''));
        $usuario_clave_2 = trim($this->limpiarCadena($input['register_clave2'] ?? ''));
        // verificar campos obligatorios
        if ($usuario_nombre == "" || $usuario == "" || $usuario_clave_1 == "" || $usuario_clave_2 == "") {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'No has llenado todos los campos que son obligatorios'
            ]);
            return;
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_nombre)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_apellido)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $usuario)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-Z0-9$@.-]{7,100}$", $usuario_clave_1)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'La clave no coincide con el formato solicitado'
            ]);
            return;
        }
        # Verificando el usuario
        $check_usuario = Usuario::where("usuario_usuario", $usuario)->first();
        if ($check_usuario) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El usuario ya se encuentra registrado'
            ]);
            return;
        }
        #Verificando si las claves son iguales
        if ($usuario_clave_1 != $usuario_clave_2) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'Las claves no coinciden'
            ]);
            return;
        }
        else {
            $clave_procesada = password_hash($usuario_clave_1, PASSWORD_BCRYPT, ["cost" => 10]); # encripta la clave
        }
        #Preparando datos para el registro
        $datos_usuario_reg = [
            "usuario_nombre" => $usuario_nombre,
            "usuario_apellido" => $usuario_apellido,
            "usuario_usuario" => $usuario,
            "usuario_clave" => $clave_procesada,
            'usuario_creado' => date("Y-m-d H:i:s")
        ];
        $agregar_usuario = Usuario::create($datos_usuario_reg);
        if ($agregar_usuario) {
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => 'Usuario registrado exitosamente'
            ]);
            return;
        }
        else {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al registrar el usuario, intente nuevamente'
            ]);
        }
    }
    public function eliminarUsuarioControlador($id_user){
        $id = trim($this->limpiarCadena($id_user ?? ''));
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'ID de usuario no válido'
                ]);
                return;
            }
            $oferta_doc = Usuario::where("id_usuario", $id)->first();
            if (!$oferta_doc) {
                http_response_code(404);
                echo json_encode([
                    "code" => 404,
                    "data" => 'El usuario no existe'
                ]);
                return;
            }
            $eliminar_usuario = Usuario::destroy($id);
            if ($eliminar_usuario) {
                http_response_code(200);
                echo json_encode([
                    "code" => 200,
                    "data" => "El usuario ha sido eliminado del sistema correctamente"
                ]);
                return;
            }
            else {
                http_response_code(500);
                echo json_encode([
                    "code" => 500,
                    "data" => "No hemos podido eliminar el usuario del sistema, por favor intente nuevamente"
                ]);
                return;
            }
        }
        catch (\Exception $e) {
            error_log("Error en eliminarUsuarioControlador: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "data" => 'Error al eliminar el usuario, intente nuevamente'
            ]);
            return;
        }
    }
    public function actualizarUsuarioControlador()
    {
        #Almacenar Datos
        $input = json_decode(file_get_contents("php://input"), true);

        $id = trim($this->limpiarCadena($input['id_usuario'] ?? ''));
        $input = json_decode(file_get_contents("php://input"), true);
        $usuario_nombre = trim($this->limpiarCadena($input['register_nombre'] ?? ''));
        $usuario_apellido = trim($this->limpiarCadena($input['register_apellido'] ?? ''));
        $usuario = trim($this->limpiarCadena($input['register_usuario'] ?? ''));
        $usuario_clave_1 = trim($this->limpiarCadena($input['register_clave1'] ?? ''));
        $usuario_clave_2 = trim($this->limpiarCadena($input['register_clave2'] ?? ''));
        // verificar campos obligatorios
        if ($usuario_nombre == "" || $usuario == "" || $usuario_clave_1 == "" || $usuario_clave_2 == "") {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'No has llenado todos los campos que son obligatorios'
            ]);
            return;
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_nombre)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_apellido)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $usuario)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El nombre no coincide con el formato solicitado'
            ]);
            return;
        }
        elseif ($this->verificarDatos("^[a-zA-Z0-9$@.-]{7,100}$", $usuario_clave_1)) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'La clave no coincide con el formato solicitado'
            ]);
            return;
        }
        # Verificando el usuario
        $check_usuario = Usuario::where("usuario_usuario", $usuario)->first();
        if ($check_usuario) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'El usuario ya se encuentra registrado'
            ]);
            return;
        }
        #Verificando si las claves son iguales
        if ($usuario_clave_1 != $usuario_clave_2) {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => 'Las claves no coinciden'
            ]);
            return;
        }
        else {
            $clave_procesada = password_hash($usuario_clave_1, PASSWORD_BCRYPT, ["cost" => 10]); # encripta la clave
        }
        $check_usuario = Usuario::where("id", $id)->first();
        if (!$check_usuario) {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => "El usuario seleccionado no existe"
                ]);
                return;
            }
        #Preparando datos para el registro
        $datos_usuario_reg = [
            "usuario_nombre" => $usuario_nombre,
            "usuario_apellido" => $usuario_apellido,
            "usuario_usuario" => $usuario,
            "usuario_clave" => $clave_procesada,
            'usuario_creado' => date("Y-m-d H:i:s")
        ];
        $update_usuario = Usuario::where('id', $id)
                ->update($datos_usuario_reg);
        if ($update_usuario) {
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "data" => "El Usuario " . $usuario . " ha sido actualizado exitosamente"
            ]);
            return;
        }
        else {
            http_response_code(400);
            echo json_encode([
                "code" => 400,
                "data" => "No se pudo actualizar al usuario, por favor intente nuevamente"
            ]);
            return;
        }
    }
}