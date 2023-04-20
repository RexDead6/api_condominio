<?php
require_once dirname( __DIR__ ) . '/model/BancosModel.php';
require_once dirname( __DIR__ ) . '/model/PagoMovilModel.php';
require_once dirname( __DIR__ ) . '/util/Response.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/model/TokenAccess.php';

class PagoMovilController{
    public function getBancos(){
        $bancos = (new BancosModel())->getAll();
        return (new Response(
            count($bancos) > 0,
            count($bancos) > 0 ? "Bancos encontrados" : "No hay bancos disponibles",
            count($bancos) > 0 ? 200 : 404,
            $bancos
        ))->json();
    }

    public function getAll($token){
        $pmv = (new PagoMovilModel())->inner("bancos", "idBan")->where("idUsu", "=", (new TokenAccess())->where("token", "=", $token)->getFirst()->getIdUsu())->orderBy("idPmv")->getAll();
        return (new Response(
            count($pmv) > 0,
            count($pmv) > 0 ? "Bancos encontrados" : "No hay bancos disponibles",
            count($pmv) > 0 ? 200 : 404,
            $pmv
        ))->json();
    }

    public function getVenta($idUsu){
        $pmv = (new PagoMovilModel())->inner("bancos", "idBan")->where("idUsu", "=", $idUsu)->where("venta", "=", 1)->getFirst();
        return (new Response(
            isset($pmv),
            isset($pmv) ? "Pago Movil encontrado" : "No hay pago movil disponible",
            isset($pmv) ? 200 : 404,
            $pmv
        ))->json();
    }

    public function updateVenta($token, $idPmv){
        $pmv = (new PagoMovilModel())->where("idUsu", "=", explode("|", $token)[0])->where("venta", "=", 1)->getFirst();
        if (isset($pmv)){
            $pmv->setVenta(0);
            $pmv->where("idPmv", "=", $pmv->getIdPmv())->update();
        }

        $pmv = (new PagoMovilModel())->where("idPmv", "=", $idPmv)->getFirst();
        $pmv->setVenta(1);
        $resp = $pmv->where("idPmv", "=", $pmv->getIdPmv())->update();

        return (new Response(
            $resp,
            $resp ? "Pago Movil Actualizado" : "No ha podido actializar el pago movil",
            $resp ? 201 : 500
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

    public function update($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];

        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idPvm']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $usuario = (new UsuarioModel())->where("idUsu", "=", explode("|", $token)[0])->getFirst();
        if (!isset($usuario)){
            return (new Response(
                false, 
                "Usuario no existe", 
                404
            ))->json();
        }

        $pmv = (new PagoMovilModel())->where("idPmv", "=", $JSON_DATA['idPvm'])->getFirst();
        if (!isset($pvm)){
            return (new Response(
                false, 
                "Pago Movil no existe", 
                404
            ))->json();
        }

        if (isset($JSON_DATA['idBan'])){
            $banco = (new BancosModel())->where("idBan", "=", $JSON_DATA['idBan'])->getFirst();
            if (!isset($banco)){
                return (new Response(
                    false, 
                    "Banco no encontrado",
                    404
                ))->json();
            }
            $pmv->idBan = $JSON_DATA['idBan'];
        }

        if (isset($JSON_DATA['status'])) $pmv->setStatus($JSON_DATA['status']);
        if (isset($JSON_DATA['telPmv'])) $pvm->setTelPmv($JSON_DATA['telPmv']);
        if (isset($JSON_DATA['cedPmv'])) $pvm->setCedPmv($JSON_DATA['cedPmv']);

        $pmv->where("idPmv", "=", $JSON_DATA['idPvm'])->update();
    }
}
?>