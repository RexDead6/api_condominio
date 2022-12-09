<?php
require_once '../model/UsuarioModel.php';
require_once '../util/Crypt.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';

class UsuarioController{
    public function getAll(){
        $response = new Response(
            true, 
            "Datos encontrados", 
            200, 
            (new UsuarioModel())->inner("roles", "idRol")->getAll()
        );
        return $response->json();
    }

    public function getOne($column = "idUsu", $value){
        $model = new UsuarioModel();
    }

    public function Login(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true);
        $validate_keys = ValidateApp::keys_array_exist($JSON_DATA, ['cedula', 'clave']);
        
        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $user = (new UsuarioModel())->inner("roles", "idRol")->where("cedUsu", "=", $JSON_DATA['cedula'])->getFirst();

        if (!isset($user)){
            return (new Response(
                false,
                "Esta Cédula no se encuentra registrada",
                400
            ))->json();
        }

        if (!Crypt::verify($JSON_DATA['clave'], $user->getClaveUsu())){
            return (new Response(
                false,
                "Clave de usuario incorrecta",
                400
            ))->json();
        }

        $data_response = [
            "id" => $user->getIdUsu(),
            "Rol" => $user->getRol()
        ];

        return (new Response(
            true,
            "Usuario autenticado exitosamente",
            200,
            $data_response
        ))->json();
    }
}
?>