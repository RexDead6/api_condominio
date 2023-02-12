<?php
require_once '../model/ServicioModel.php';
require_once '../model/PagoMovilModel.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';
require_once '../model/TokenAccess.php';

class ServicioController{
    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idPmv', 'descSer', 'isMensualSer', 'montoSer']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        if (((int) explode("|", $token)[2]) != 1){
            return (new Response(
                false, 
                "Permisos insuficientes", 
                401
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("servicios", "descSer", $JSON_DATA['descSer'])){
            return (new Response(
                false, 
                "Servicio duplicado, intente con otra descripción", 
                400
            ))->json();
        }

        $result = (new ServicioModel())->insert($JSON_DATA);
        return (new Response(
            $result != false,
            $result != false ? "Servicio registrado correctamente" : "No se ha podido registrar su servicio",
            $result != false ? 201 : 500
        ))->json();
    }

    public function getAll(){
        $servicios = (new ServicioModel())->inner("pagomovil", "idPmv")->inner("bancos", "idBan", "pagomovil")->getAll();
        return (new Response(
            true,
            "Servicios encontrados",
            200,
            $servicios
        ))->json();
    }
}
?>