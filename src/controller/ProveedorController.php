<?php
require_once dirname( __DIR__ ) . '/model/ProveedorModel.php';

class ProveedorController{
    
    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['RIF', 'nomProv']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("proveedor", "RIF", $JSON_DATA['RIF'])){
            return (new Response(
                false, 
                "Su RIF ya se encuentra registrado", 
                400
            ))->json();
        }

        $JSON_DATA['idUsu'] = explode("|", $token)[0];

        $result = (new ProveedorModel())->insert($JSON_DATA);
        return (new Response(
            $result != false,
            $result != false ? "Proveedor Registrado" : "No se ha podido registrar su proveedor",
            $result != false ? 201 : 500,
        ))->json();
    }

    public function getAll($token){
        $proveedores = (new ProveedorModel())->where("idUsu", "=", explode("|", $token)[0])->getAll();
        return (new Response(
            count($proveedores) > 0,
            count($proveedores) > 0 ? "Proveedores encontrados" : "No se han encontrador proveedores",
            count($proveedores) > 0 ? 200 : 404,
            $proveedores
        ))->json();
    }
}
?>