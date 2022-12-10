<?php
require_once '../model/FamiliaModel.php';
require_once '../model/UsuarioModel.php';
require_once '../model/GruposFamiliaresModel.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';

class FamiliaController{
    public function registrarFamilia(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true);
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idJefeUsu', 'descFam', 'direccion']
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

        $idFam = (new FamiliaModel())->insert([
            "descFam"=>$JSON_DATA['descFam'], 
            "direccion"=>$JSON_DATA['direccion'],
            "hashFam" => bin2hex(random_bytes(20))
        ]);
        $user = (new UsuarioModel())->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->getFirst();
        $user->setStatusUsu(1);
        $user->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->update();
        (new GruposFamiliaresModel())->insert([
            "idUsu" => $JSON_DATA['idJefeUsu'],
            "idFam" => $idFam
        ]);
        return (new Response(
            true, 
            "Familia Registrada exitosamente",
            201
        ))->json();
    }

    public function getById($idFam){
        $fam = (new FamiliaModel)->where("fam.idFam", "=", $idFam)->getFirst();

        if (!isset($fam)){
            return (new Response(
                false, 
                "Familia no encontrada", 
                404
            ))->json();
        }

        $fam->setUsers((new UsuarioModel())->inner("gruposfamiliares", "idUsu")->inner("roles", "idRol")->where("gru.idFam", "=", $idFam)->getAll());

        $response = new Response(
            true, 
            "Datos encontrados", 
            200, 
            $fam
        );
        return $response->json();
    }
}
?>