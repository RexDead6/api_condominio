<?php
require_once dirname(__DIR__) . '/model/StatiticHomeModel.php';
require_once dirname(__DIR__) . '/model/MontoPorMesModel.php';
require_once dirname(__DIR__) . '/model/AnunciosModel.php';
require_once dirname(__DIR__) . '/model/ServicioModel.php';
require_once dirname(__DIR__) . '/model/FacturaModel.php';
require_once dirname(__DIR__) . '/model/TokenAccess.php';
require_once dirname(__DIR__) . '/util/Response.php';
require_once dirname(__DIR__) . '/util/ValidateApp.php';

class UrbanizacionController
{
    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['nomUrb', 'direccion']
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

        if (((int) explode("|", $token)[2]) != 1){
            return (new Response(
                false, 
                "Permisos insuficientes", 
                401
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("urbanizacion", "nomUrb", $JSON_DATA["nomUrb"])) {
            return (
                new Response(
                    false,
                    "El nombre de la comunidad ya existe",
                    400
                )
            )->json();
        }

        $success = (new UrbanizacionModel())->insert(["nomUrb"=>$JSON_DATA['nomUrb'], "direccion"=>$JSON_DATA['direccion']]);
        return (
            new Response(
                $success != false,
                $success != false ? "Comunuidad registrada exitosamente" : "Ha ocurrido un error con el servidor, intente de nuevo",
                $success != false ? 201 : 500
            )
        )->json();
    }

    public function getAll() {
        $comunidades = (new UrbanizacionModel())->getAll();
        
        foreach($comunidades as $comunidad) {
            $count = count((new FamiliaUrbanizacionModel())->where("idUrb", "=", $comunidad->getIdUrb())->getAll());
            $comunidad->setTotalFamilias($count);
        }

        return (new Response(
            count($comunidades) > 0,
            count($comunidades) > 0 ? "Comunidades encontradas" : "No existen comunidades registradas",
            count($comunidades) > 0 ? 200 : 404,
            $comunidades
        ))->json();
    }

    public function getById($id) {
        $comunidad = (new UrbanizacionModel())->where("idUrb", "=", $id)->getFirst();
        return (new Response(
            isset($comunidad),
            isset($comunidad) ? "Comunidad encontrada" : "Su comunidad no existe",
            isset($comunidad) ? 200 : 404,
            $comunidad
        ))->json();
    }

    public function getStatitic($token, $idUrb){
        $static = new StatiticHomeModel();
        $static->setUltimosAnuncios((new AnunciosModel())->inner("usuarios", "idUsu")->inner("roles", "idRol", "usuarios")->orderBy("idAnu")->where("idUrb", "=", $idUrb)->limit(3)->getAll());

        $servicios = (new ServicioModel())->inner("pagomovil", "idPmv")->inner("bancos", "idBan", "pagomovil")->where("idUrb", "=", $idUrb)->orderBy("idSer")->getAll();
        $newListServicios = [];
        $idFam = explode("|", $token)[1];
        foreach ($servicios as $servicio) {
            $ultimaFac = (new FacturaModel)->where("idSer", "=", $servicio->getIdSer())->where("idFam", "=", $idFam)->where("status", "<>", 0)->orderBy("idFac")->getFirst();
            if ($servicio->getIsMensualSer() == 0) {
                if (isset($ultimaFac)) {
                    continue;
                }
                $servicio->setMesesPorPagar(1);
            } else {
                $lastM = ((int) date("m", strtotime($servicio->getFechaInicioServicio()))) - 1;
                $CurrentM = (int) date("m");
                $date1 = date("Y-m-d", strtotime($servicio->getFechaInicioServicio()));
                if (isset($ultimaFac)) {
                    $lastM = (int) date("m", strtotime($ultimaFac->getFechapagoFac()));
                    $date1 = date("Y-m-d", strtotime("+".$ultimaFac->getMeses()." month", strtotime($ultimaFac->getFechapagoFac())));
                    if ($lastM === $CurrentM) {
                        continue;
                    }
                }
                
                $date2 = date("Y-m-d");
                $timeRaw = (new DateTime($date1))->diff(new DateTime($date2));
                $servicio->setMesesPorPagar(((($timeRaw->y) * 12) + ($timeRaw->m)) + 1);
            }
            $newListServicios[] = $servicio;
        }
        $static->setServiciosPorPagar($newListServicios);

        $currentM = 0;
        $currentY = 0;
        $monto = 0;
        $facturas = (new FacturaModel())->where("idFam", "=", $idFam)->orderBy("fechapagFac")->getAll();
        $gastosMeses = [];
        
        $gastosTemp = [];
        foreach ($facturas as $factura) {
            $currentM = date("m", strtotime($factura->getFechapagoFac()));
            $currentY = date("Y", strtotime($factura->getFechapagoFac()));
            if (array_key_exists($currentM."/".$currentY, $gastosTemp)) {
                $gastosTemp[$currentM."/".$currentY] = $factura->getMontoFac() + $gastosTemp[$currentM."/".$currentY];
            } else {
                $gastosTemp[$currentM."/".$currentY] = $factura->getMontoFac();
            }
        }

        foreach ($gastosTemp as $key => $value) {
            $mes = new MontoPorMesModel();
            $date = explode("/", $key);
            $mes->setMes($date[0]);
            $mes->setYear($date[1]);
            $mes->setMonto($value);
            $gastosMeses[] = $mes;
        }

        $static->setPagosMeses($gastosMeses);

        return (new Response(
            true,
            "Datos encontrados",
            200,
            $static
        ))->json();
    }

    public function edit($token, $id) {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        if (((int) explode("|", $token)[2]) != 1){
            return (new Response(
                false, 
                "Permisos insuficientes",
                401
            ))->json();
        }
    }

    public function getUserUrb($token){
        $grupos = (new FamiliaUrbanizacionModel())->where("idFam", "=", explode('|', $token)[1])->getAll();

        $comunidades = [];

        foreach ($grupos as $grupo) {
            $comunidad = (new UrbanizacionModel())->where("idUrb", "=", $grupo->getIdUrb())->getFirst();
            $result = (new UsuarioAdminModel())->where('idUsu', '=', explode('|', $token)[0])->where('idUrb', '=', $grupo->getIdUrb())->getFirst();
            $comunidad->setIsAdmin(isset($result));
            $comunidades[] = $comunidad;
        }

        return (new Response(
            count($comunidades) > 0, 
            count($comunidades) > 0 ? "Comunidades encontradas" : "Su usuario no pertenece a ninguna comunidad", 
            count($comunidades) > 0 ? 200 : 404,
            $comunidades
        ))->json();
    }

    public function disableUrb($token, $idUrb) {

    }

    public function postuseradmin($token, $idUsu, $idUrb){
        if (((int) explode("|", $token)[2]) != 1) {
            return (new Response(
                false, 
                "Permisos insuficientes", 
                401
            ))->json();
        }
        
        $usuario = (new UsuarioModel())->where("idUsu", "=", $idUsu)->getFirst();
        if(!isset($usuario)){
            return(new Response(
                false,
                "Usuario no existe",
                404
            ))->json();
        }

        $urbanizacion = (new UrbanizacionModel())->where("idUrb", "=", $idUrb)->getFirst();
        if(!isset($urbanizacion)){
            return (new Response(
                false,
                "Urbanizacion no existe",
                404
            ))->json();
        }

        $gruposfamiliares = (new GruposFamiliaresModel())->where("idUsu", "=", $idUsu)->getFirst();
        $urba = (new FamiliaUrbanizacionModel())->where("idFam", "=", $gruposfamiliares->getIdFam())->getAll();
        if (count($urba) <= 0){
            return (new Response(
                false,
                "Su usuario no pertenece a esta comunidad",
                400
            ))->json();
        }
    }
}
?>