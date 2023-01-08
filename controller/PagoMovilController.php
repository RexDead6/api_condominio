<?php
require_once '../model/BancosModel.php';
require_once '../model/PagoMovilModel.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';
require_once '../model/TokenAccess.php';

class PagoMovilController{
    public function getBancos(){
        $bancos = (new BancosModel())->getAll();
        return (new Response(
            count($bancos) > 0,
            count($bancos) > 0 ? "Bancos encontrados" : "No hay bancos disponibles",
            count($bancos) > 0 ? 200 : 500,
            $bancos
        ))->json();
    }

    public function getAll($token){
        $pmv = (new PagoMovilModel())->where("idUsu", "=", (new TokenAccess())->where("token", "=", $token)->getFirst()->getIdUsu())->getAll();
        return (new Response(
            count($pmv) > 0,
            count($pmv) > 0 ? "Bancos encontrados" : "No hay bancos disponibles",
            count($pmv) > 0 ? 200 : 500,
            $pmv
        ))->json();
    }

    public function insertPagoMovil($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idBan', 'telPmv', 'cedPmv']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $banco = (new BancosModel())->where("idBan", "=", $JSON_DATA['idBan'])->getFirst();
        if (!isset($banco)){
            return (new Response(
                false, 
                "Banco no encontrado",
                404
            ))->json();
        }

        $JSON_DATA['idUsu'] = (new TokenAccess())->where("token", "=", $token)->getFirst()->getIdUsu();
        
        $idPmv = (new PagoMovilModel())->insert($JSON_DATA);
        return (
                    new Response(
                        $idPmv != false,
                        $idPmv != false ? "Pago Movil Registrado de forma exitosa" : "No se ha podido registrar, intente de nuevo",
                        $idPmv != false ? 201 : 500
                    )
                )->json();
    }
}
?>