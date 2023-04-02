<?php
require_once '../model/ServicioModel.php';
require_once '../model/PagoMovilModel.php';
require_once '../util/Response.php';
require_once '../util/ValidateApp.php';
require_once '../model/TokenAccess.php';
require_once '../model/FacturaModel.php';
require_once '../model/PagoServiciosModel.php';

class ServicioController
{
    public function insert($token)
    {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idPmv', 'descSer', 'isMensualSer', 'montoSer']
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

        if (((int) explode("|", $token)[2]) != 1) {
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
        if (((int) explode("|", $token)[2]) != 1) {
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

    public function insertPayService($token)
    {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idSer', 'montoFac', 'pagos']
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

        $servicio = (new ServicioModel())->where("idSer", "=", $JSON_DATA['idSer'])->getFirst();
        if (!isset($servicio)) {
            return (
                new Response(
                false,
                "Servicio inexistente",
                404
                )
            )->json();
        }

        $MesesPorPagar = 1;
        $idFam = explode("|", $token)[1];
        $ultimaFac = (new FacturaModel)->where("idSer", "=", $servicio->getIdSer())->where("idFam", "=", (int) $idFam)->where("status", "<>", 0)->getFirst();
        if ($servicio->getIsMensualSer() == 0) {
            if (isset($ultimaFac)) {
                return (
                    new Response(
                    false,
                    "Su Servicio ya ha sido pagado",
                    400
                    )
                )->json();
            }
        } else {
            $lastM = ((int) date("m", strtotime($servicio->getFechaInicioServicio()))) - 1;
                $CurrentM = (int) date("m");
                if (isset($ultimaFac)) {
                    $lastM = (int) date("m", strtotime($ultimaFac->getFechapagoFac()));
                    if ($lastM === $CurrentM) {
                        return (
                            new Response(
                            false,
                            "Su Servicio mensual se encuentra al dia con el pago",
                            400
                            )
                        )->json();
                    }
                }
                /*$date2 = date("Y-m-d");
                $timeRaw = (new DateTime($date1))->diff(new DateTime($date2));
                $servicio->setMesesPorPagar(((($timeRaw->y) * 12) + ($timeRaw->m)) + 1);*/
                $MesesPorPagar = ($CurrentM - $lastM);
        }

        $idFac = (new FacturaModel())->insert([
            "idSer" => $JSON_DATA['idSer'],
            "idFam" => $idFam,
            "montoFac" => $JSON_DATA['montoFac'],
            "meses" => $MesesPorPagar
        ]);

        if ($idFac == false) {
            return (
                new Response(
                false,
                "No se ha podido registrar su servicio, intente de nuevo",
                500
                )
            )->json();
        }

        foreach ($JSON_DATA['pagos'] as $pago) {
            $pago['idFac'] = $idFac;
            (new PagoServiciosModel())->insert($pago);
        }

        return (
            new Response(
            true,
            "Factura generada exitosamente",
            200
            )
        )->json();
    }

    public function getFacturas($token, $status){
        $idFam = explode("|", $token)[1];
        $facturas = (new FacturaModel())->inner("servicios", "idSer")->where("idFam", "=", (int) $idFam)->where("status", "=", $status)->getAll();

        foreach ($facturas as $factura) {
            $factura->setPagos((new PagoServiciosModel())->where("idFac", "=", $factura->getIdFac())->getAll());
        }

        return (
            new Response(
                count($facturas) > 0,
                count($facturas) > 0 ? "Facturas encontradas" : "Facturas inexistentes",
                count($facturas) > 0 ? 200 : 404,
                $facturas
            )
        )->json();
    }

    public function getFacturasAdmin($token, $idSer, $status){

        if (((int) explode("|", $token)[2]) != 1) {
            return (
                new Response(
                false,
                "Permisos insuficientes",
                401
                )
            )->json();
        }

        if (!isset($idSer)){
            return (
                new Response(
                false,
                "idSer inexistente",
                400
                )
            )->json();
        }

        $facturas = (new FacturaModel())->inner("servicios", "idSer")->inner("familias", "idFam")->where("fac.idSer", "=", (int) $idSer)->where("fac.status", "=", $status)->getAll();

        foreach ($facturas as $factura) {
            $factura->setPagos((new PagoServiciosModel())->where("idFac", "=", $factura->getIdFac())->getAll());
        }

        return (
            new Response(
                count($facturas) > 0,
                count($facturas) > 0 ? "Facturas encontradas" : "Facturas inexistentes",
                count($facturas) > 0 ? 200 : 404,
                $facturas
            )
        )->json();
    }

    public function updateStatusFactura($token, $idFac, $status){
        if (((int) explode("|", $token)[2]) != 1) {
            return (
                new Response(
                false,
                "Permisos insuficientes",
                401
                )
            )->json();
        }

        if (!isset($idFac) || !isset($status)){
            return (
                new Response(
                false,
                "datos inexistentes",
                400
                )
            )->json();
        }

        $factura = (new FacturaModel())->where("idFac", "=", $idFac)->getFirst();
        if(!isset($factura)){
            return (
                new Response(
                false,
                "Factura inexistente",
                404
                )
            )->json();
        }

        if ($factura->getStatus() != 2) {
            return (
                new Response(
                false,
                "Factura ya ha sido gestionada",
                400
                )
            )->json();
        }

        $factura->setStatus($status);
        $result = $factura->where("idFac", "=", $idFac)->update();
        return (
            new Response(
                $result,
                $result ? "Factura actualizada correctamente" : "No se ha podido actualizar su Factura, intente de nuevo",
                $result ? 201:500
            )
        )->json();
    }
}
?>