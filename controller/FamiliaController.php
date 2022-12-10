<?php
require_once '../model/FamiliaModel.php';
require_once '../model/UsuarioModel.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';

class FamiliaController{
    public function registrarFamilia(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true);
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idJefeUsu', 'descFam']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("gruposfamiliares", "idUsu", $JSON_DATA['idJefeUsu'])){
            return (new Response(
                false, 
                "Su cédula ya se encuentra registrada en una Familia", 
                400
            ))->json();
        }

        $JSON_DATA['hashFam'] = bin2hex(random_bytes(5));
        (new FamiliaModel())->insert($JSON_DATA);
        $user = (new UsuarioModel())->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->getFirst();
        $user->setStatusUsu(1);
        $user->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->update();
        return (new Response(
            true, 
            "Familia Registrada exitosamente",
            201
        ))->json();
    }
}
?>