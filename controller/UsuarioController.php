<?php
require_once '../model/UsuarioModel.php';
require_once '../model/FamiliaModel.php';
require_once '../model/TokenAccess.php';
require_once '../util/Crypt.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';

class UsuarioController{

    public function registrarUsu(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['cedUsu', 'nomUsu', 'apeUsu', 'generoUsu', 'telUsu',  'claveUsu']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("usuarios", "cedUsu", $JSON_DATA['cedUsu'])){
            return (new Response(
                false, 
                "Su cédula ya se encuentra registrada", 
                400
            ))->json();
        }

        $JSON_DATA['claveUsu'] = Crypt::hash($JSON_DATA['claveUsu']);

        $id_usuario = (new UsuarioModel())->insert($JSON_DATA);
        $user = (new UsuarioModel())->where("idUsu", "=", $id_usuario)->getFirst();
        $token = $id_usuario."|00|".$user->getRol()->getIdRol()."|".bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $id_usuario,
            "token" => $token
        ]);

        $data_response = [
            "token" => $token
        ];
        return (new Response(
            true, 
            "Usuario registrado exitosamente", 
            201,
            $data_response
        ))->json();
    }
    public function getAll(){
        $response = new Response(
            true, 
            "Datos encontrados", 
            200, 
            (new UsuarioModel())->inner("roles", "idRol")->getAll()
        );
        return $response->json();
    }

    public function getInactive(){
        $response = new Response(
            true, 
            "Datos encontrados", 
            200, 
            (new UsuarioModel())->inner("roles", "idRol")->where("statusUsu", "=", 0)->getAll()
        );
        return $response->json();
    }

    public function getById($value, $column = "idUsu"){
        $user = (new UsuarioModel())->inner("roles", "idRol")->where($column, "=", $value)->getFirst();
        return (new Response(
            isset($user),
            isset($user) ? "Usuario encontrado" : "Usuario inexistente",
            isset($user) ? 200 : 404,
            $user
        ))->json();
    }

    public function Login(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
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
                404
            ))->json();
        }

        if (!Crypt::verify($JSON_DATA['clave'], $user->getClaveUsu())){
            return (new Response(
                false,
                "Clave de usuario incorrecta",
                400
            ))->json();
        }

        $fam = (new FamiliaModel())->inner("gruposfamiliares", "idFam")->where("gru.idUsu", "=", $user->getIdUsu())->getFirst();
        $fam = ($fam != null) ? $fam->getIdFam() : "00";

        $token = $user->getIdUsu()."|".$fam."|".$user->getRol()->getNivelRol()."|".bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $user->getIdUsu(),
            "token" => $token
        ]);

        $data_response = [
            "token" => $token
        ];

        return (new Response(
            true,
            "Usuario autenticado exitosamente",
            200,
            $data_response
        ))->json();
    }

    public function logout($token){
        (new TokenAccess())->where("token", "=", $token)->delete();
        return (new Response(
            true,
            "Sesion cerrada",
            200
        ))->json();
    }
}
?>