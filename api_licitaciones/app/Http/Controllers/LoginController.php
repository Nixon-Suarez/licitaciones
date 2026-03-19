<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use App\Models\Token;


class loginController extends Controller
{
    public function iniciarSesionControlador()
    {   
        try{
            #Almacenar Datos
            $input = json_decode(file_get_contents("php://input"), true);
            $usuario = trim($this->limpiarCadena($input['login_usuario'] ?? ''));
            $clave = trim($this->limpiarCadena($input['login_clave'] ?? ''));
            // verificar campos obligatorios
            if ($usuario == "" || $clave == "") {
                http_response_code(400);
                echo json_encode([
                    "code" => 400,
                    "data" => 'No has llenado todos los campos que son obligatorios'
                ]);
                return;
            }
            else {
                #Verificando integridad de los datos
                if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
                    http_response_code(400);
                    echo json_encode([
                        "code" => 400,
                        "data" => 'El USUARIO no coincide con el formato solicitado'
                    ]);
                    return;
                }
                elseif ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
                    http_response_code(400);
                    echo json_encode([
                        "code" => 400,
                        "data" => 'La CLAVE no coincide con el formato solicitado'
                    ]);
                    return;
                }
                else {
                    $check_user = Usuario::where("usuario_usuario", $usuario)->first();
                    if ($check_user) {
                        if (password_verify($clave, $check_user->usuario_clave)) {
                            $user_data = [
                                "id" => $check_user->id,
                                "usuario" => $check_user->usuario_usuario
                            ];
                            $token = bin2hex(random_bytes(32));
                            $token_hash = hash('sha256', $token);
                            $datos_acceso = [
                                "user_id" => $check_user->id,
                                "token" => $token,
                                "token_hash" => $token_hash,
                                "expires_at" => date("Y-m-d H:i:s", strtotime("+1 day")),
                                "revoked" => false,
                            ];
                            $nuevo_acceso = Token::create($datos_acceso);
                            if ($nuevo_acceso) {
                                http_response_code(200);
                                echo json_encode([
                                    'code' => 200,
                                    'data' => $user_data,
                                    'token' => $token
                                ]);
                                return;
                            }
                            else {
                                http_response_code(400);
                                echo json_encode([
                                    "code" => 400,
                                    "data" => 'No se pudo crear el token, por favor intente nuevamente'
                                ]);
                                return;
                            }
                        }
                        else {
                            http_response_code(400);
                            echo json_encode([
                                "code" => 400,
                                "data" => 'La CLAVE no coincide con el formato solicitado'
                            ]);
                            return;
                        }
                    }
                    else {
                        http_response_code(400);
                        echo json_encode([
                            "code" => 400,
                            "data" => 'Usuario o clave incorrectos'
                        ]);
                        return;
                    }
                }
            }
        }catch(\Exception $e){
            http_response_code(500);
            echo json_encode(["error" => "Error del servidor: " . $e->getMessage()]);
            exit;
        }
    }
}