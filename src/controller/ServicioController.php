<?php
require_once dirname( __DIR__ ) . '/model/ServicioModel.php';
require_once dirname( __DIR__ ) . '/model/PagoMovilModel.php';
require_once dirname( __DIR__ ) . '/util/Response.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/model/TokenAccess.php';
require_once dirname( __DIR__ ) . '/model/FacturaModel.php';
require_once dirname( __DIR__ ) . '/model/PagoServiciosModel.php';

class ServicioController{
    public function insert($token) {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idPmv', 'descSer', 'isMensualSer', 'montoSer', 'divisa']
        );

        if (!$validate_keys[0]) {
            return (
                new Response(
                false,
                "Datos incompletos (" . implode(", ", $validate_keys[1]) . ")",
                400
                )
            )->json();
        }

        if (((int) explode("|", $token)[2]) > 2) {
            return (
                new Response(
                false,
                "Permisos insuficientes",
                401
                )
            )->json();
        }

        $pmv = (new PagoMovilModel())->where("idPmv", "=", $JSON_DATA['idPmv'])->getFirst();
        if (!isset($pmv)) {
            return (
                new Response(
                false,
                "Pago Movil no existe",
                404
                )
            )->json();
        }

        if ((new ValidateApp())->isDuplicated("servicios", "descSer", $JSON_DATA['descSer'])) {
            return (
                new Response(
                false,
                "Servicio duplicado, intente con otra descripción",
                400
                )
            )->json();
        }

        $result = (new ServicioModel())->insert($JSON_DATA);
        return (
            new Response(
            $result != false,
            $result != false ? "Servicio registrado correctamente" : "No se ha podido registrar su servicio",
            $result != false ? 201 : 500
            )
        )->json();
    }

    public function getAll($token)
    {
        $servicios = (new ServicioModel())->inner("pagomovil", "idPmv")->inner("bancos", "idBan", "pagomovil")->orderBy("idSer")->getAll();
        $newListServicios = [];
        $idFam = explode("|", $token)[1];
        foreach ($servicios as $servicio) {
            $ultimaFac = (new FacturaModel)->where("idSer", "=", $servicio->getIdSer())->where("idFam", "=", $idFam)->where("status", "<>", 0)->getFirst();
            if ($servicio->getIsMensualSer() == 0) {
                if (isset($ultimaFac)) {
                    continue;
                }
                $servicio->setMesesPorPagar(1);
            } else {
                $lastM = ((int) date("m", strtotime($servicio->getFechaInicioServicio()))) - 1;
                $CurrentM = (int) date("m");
                if (isset($ultimaFac)) {
                    $lastM = (int) date("m", strtotime($ultimaFac->getFechapagoFac()));
                    if ($lastM === $CurrentM) {
                        continue;
                    }
                }
                /*$date2 = date("Y-m-d");
                $timeRaw = (new DateTime($date1))->diff(new DateTime($date2));
                $servicio->setMesesPorPagar(((($timeRaw->y) * 12) + ($timeRaw->m)) + 1);*/
                $servicio->setMesesPorPagar($CurrentM - $lastM);
            }
            $newListServicios[] = $servicio;
        }

        return (
            new Response(
                count($newListServicios) > 0,
                count($newListServicios) > 0 ? "Servicios encontrados" : "Usted no posee deudas",
                count($newListServicios) > 0 ? 200 : 404,
            $newListServicios
            )
        )->json();
    }

    public function getAllAdmin($token)
    {
        if (((int) explode("|", $token)[2]) > 2) {
            return (
                new Response(
                false,
                "Permisos insuficientes",
                401
                )
            )->json();
        }

        $servicios = (new ServicioModel())->inner("pagomovil", "idPmv")->inner("bancos", "idBan", "pagomovil")->orderBy("idSer")->getAll();
        return (
            new Response(
                count($servicios) > 0,
                count($servicios) > 0 ? "Servicios encontrados" : "No se han encontrado servicios",
                count($servicios) > 0 ? 200 : 404,
            $servicios
            )
        )->json();
    }


}
?>