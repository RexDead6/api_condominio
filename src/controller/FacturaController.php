<?php
require_once dirname( __DIR__ ) . '/util/Response.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/model/TokenAccess.php';
require_once dirname( __DIR__ ) . '/model/FacturaModel.php';
require_once dirname( __DIR__ ) . '/model/FamiliaModel.php';
require_once dirname( __DIR__ ) . '/model/PagoServiciosModel.php';
require_once dirname( __DIR__ ) . '/util/PDFManager.php';
require_once dirname( __DIR__ ) . '/util/Formating.php';

class FacturaController{
    public function insertFactura($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idSer', 'montoFac', 'pagos', "meses"]
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
        $fam = (new FamiliaModel())->where("idFam", "=", $idFam)->getFirst();
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
        } 

        $idFac = (new FacturaModel())->insert([
            "idSer" => $JSON_DATA['idSer'],
            "idFam" => $idFam,
            "montoFac" => $JSON_DATA['montoFac'],
            "meses" => $JSON_DATA["meses"]
        ]);

        if ($idFac == false) {
            return (
                new Response(
                false,
                "No se ha podido registrar su factura, intente de nuevo",
                500
                )
            )->json();
        }

        (new AuditoriaModel())->insert([
            "idUsu" => (int) explode("|", $token)[0],
            "Descripcion" => "Registro de Venta (Referencia: ".$idFac.")"
        ]);

        foreach ($JSON_DATA['pagos'] as $pago) {
            $pago['idFac'] = $idFac;
            (new PagoServiciosModel())->insert($pago);
        }

        $fac = (new FacturaModel())->where("idFac", "=", $idFac)->getFirst();

        $tasa = (new AjustesModel())->where("name", "=", "divisa")->getFirst()->getValue();
        $monto = $servicio->getMontoSer();

        if ($servicio->getDivisa()) {
            $monto = $servicio->getMontoSer() * ((float) $tasa);
        }

        $pdfManager = new PDFManager();
        $pdfManager->template("servicio.template.html", [
            "familia"=>$fam->getDescFam(),
            "n_factura"=>$idFac,
            "fecha_factura"=>$fac->getFechapagoFac(),
            "rows"=>[
                [
                    $servicio->getDescSer(), Formating::numberFormat($monto), $JSON_DATA["meses"], Formating::numberFormat($monto * $JSON_DATA["meses"])
                ]
            ],
            "total"=>Formating::numberFormat($monto * $JSON_DATA["meses"])
        ]);
        $pdfManager->output("servicio_$idFac.pdf");

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

    public function updateStatus($token, $idFac, $status){
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