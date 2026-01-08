<?php
    namespace app\controllers;
    use app\models\mainModel;
    use App\Models\Usuario;  

    class userController extends mainModel{
        public function registrarUsuarioControlador(){
            #Almacenar Datos
            $usuario_nombre = trim($this->limpiarCadena($_POST['register_nombre'] ?? ''));
            $usuario_apellido = trim($this->limpiarCadena($_POST['register_apellido'] ?? ''));
            $usuario = trim($this->limpiarCadena($_POST['register_usuario'] ?? ''));
            $usuario_clave_1 = trim($this->limpiarCadena($_POST['register_clave1'] ?? ''));
            $usuario_clave_2 = trim($this->limpiarCadena($_POST['register_clave2'] ?? ''));
            // verificar campos obligatorios
            if($usuario_nombre=="" || $usuario=="" || $usuario_clave_1=="" || $usuario_clave_2==""){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Verificando integridad de los datos
            if($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_nombre)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El nombre no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }elseif($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_apellido)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El nombre no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }elseif($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $usuario)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El usuario no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }elseif($this->verificarDatos("^[a-zA-Z0-9$@.-]{7,100}$", $usuario_clave_1)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "La clave no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            # Verificando el usuario
            $check_usuario = Usuario::where("usuario_usuario", $usuario)->first();
            if($check_usuario){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El usuario ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Verificando si las claves son iguales
            if($usuario_clave_1!=$usuario_clave_2){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Las claves no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
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
            if($agregar_usuario){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Usuario registrado",
                    "texto" => "El usuario ".$usuario_nombre. " " . $usuario_apellido ." ha sido registrado exitosamente",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el usuario, intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }
        public function eliminarUsuarioControlador(){
        }

        public function actualizarUsuarioControlador(){
        }
    }