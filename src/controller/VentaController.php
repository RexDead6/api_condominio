<?php
require_once dirname( __DIR__ ) . '/model/VentaModel.php';
require_once dirname( __DIR__ ) . '/model/PagoMovilModel.php';
require_once dirname( __DIR__ ) . '/model/ProductoModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/model/ProductoVentaModel.php';
require_once dirname( __DIR__ ) . '/model/PagoVentaModel.php';
require_once dirname( __DIR__ ) . '/util/PDFManager.php';
require_once dirname( __DIR__ ) . '/util/Formating.php';
class VentaController {

    public function getUsersProducts($token){
        $usuarios = (new UsuarioModel())->getAll();
        $usuariosProductos = [];

        foreach ($usuarios as $usuario) {
            if ($usuario->getIdUsu() == explode("|", $token)[0]){
                continue;
            }

            $pvm = (new PagoMovilModel())->where("idUsu", "=", $usuario->getIdUsu())->where("venta", "=", 1)->getFirst();
            if (!isset($pvm)) {
                continue;
            }

            $productos = (new ProductoModel())->where("idUsu","=", $usuario->getIdUsu())->getAll();
            if (count($productos) > 0) {
                $usuario->setProductos($productos);
                $usuariosProductos[] = $usuario;
            }
        }

        return (
            new Response(
            count($usuariosProductos) > 0,
            count($usuariosProductos) > 0 ? "Usuario encontrados" : "Usuario inexistentes",
            count($usuariosProductos) > 0 ? 200 : 404,
            $usuariosProductos
            )
        )->json();
    }
    
    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $pdfData = [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idVenUsu', 'monto', 'productosVenta', 'pagos']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $venUsu = (new UsuarioModel())->where("idUsu", "=", $JSON_DATA['idVenUsu'])->getFirst();
        if (!isset($venUsu)) {
            return (new Response(
                false, 
                "Usuario inexistente", 
                404
            ))->json();
        }

        $idVen = (new VentaModel())->insert([
            "idVenUsu"=>$JSON_DATA['idVenUsu'],
            "idCliUsu"=>explode("|", $token)[0],
            "montoVen"=>$JSON_DATA['monto']
        ]);

        if ($idVen == false) {
            return (new Response(
                false, 
                "No se ha podido generar su venta, intente de nuevo", 
                500
            ))->json();
        }

        $productos_pdf = [];

        foreach ($JSON_DATA['productosVenta'] as $producto) {
            $pro = (new ProductoModel())->where("idPro", "=", $producto['producto']['idPro'])->getFirst();
            if (!isset($pro)) {
                continue;
            }

            (new ProductoVentaModel())->insert([
                "idPro"=>$producto['producto']['idPro'],
                "idVen"=>$idVen,
                "cantidad"=>$producto['cantidad'],
                "costo"=>$pro->getCostoPro()
            ]);

            $pro->setExistPro($pro->getExistPro() - $producto['cantidad']);
            $pro->where("idPro", "=", $pro->getIdPro())->update();
            $productos_pdf[] = [$pro->getNomPro(), $producto['cantidad'], Formating::numberFormat($pro->getCostoPro()), Formating::numberFormat($pro->getCostoPro() * $producto['cantidad'])];
        }

        foreach ($JSON_DATA['pagos'] as $pago) {
            $pago['idVen'] = $idVen;
            (new PagoVentaModel())->insert($pago);
        }

        $usuCli = (new UsuarioModel())->where("idUsu", "=", explode("|", $token)[0])->getFirst();
        $venta = (new VentaModel())->where("idVen", "=", $idVen)->getFirst();

        $pdfManager = new PDFManager();
        $pdfManager->template("venta.template.html", [
            "ci_vendedor"=>$venUsu->getCedUsu(),
            "nombre_vendedor"=>$venUsu->getNomUsu()." ".$venUsu->getApeUsu(),
            "ci_comprador"=>$usuCli->getCedUsu(),
            "nombre_comprador"=>$usuCli->getNomUsu()." ".$usuCli->getApeUsu(),
            "n_factura"=>$idVen,
            "fecha_factura"=>$venta->getFechaVen(),
            "rows"=>$productos_pdf,
            "total"=>$JSON_DATA['monto']
        ]);
        $pdfManager->output("venta_$idVen.pdf");

        return (
            new Response(
            true,
            "Venta generada exitosamente",
            201
            )
        )->json();
    }

    public function getAll($token, $type, $status){
        $ventas = null;
        switch ($type) {
            case 'cliente':
                $ventas = (new VentaModel())->where("idCliUsu", "=", explode("|", $token)[0])->where("status", "=", $status)->orderBy("idVen")->getAll();
                break;
            case 'vendedor':
                $ventas = (new VentaModel())->where("idVenUsu", "=", explode("|", $token)[0])->where("status", "=", $status)->orderBy("idVen")->getAll();
        }
        
        if (!isset($ventas)){
            return (new Response(
                false, 
                "Tipo de usuario incorrecto", 
                400
            ))->json();
        }

        foreach ($ventas as $venta) {
            $venta->setUsuarioVenta((new UsuarioModel())->inner("roles", "idRol")->where("idUsu", "=", $venta->idVenUsu)->getFirst());
            $venta->setUsuarioCliente((new UsuarioModel())->inner("roles", "idRol")->where("idUsu", "=", $venta->idCliUsu)->getFirst());
            $venta->setPagos((new PagoVentaModel())->where("idVen", "=", $venta->getIdVen())->getAll());
            $venta->setProductos((new ProductoVentaModel())->inner("producto", "idPro")->where("idVen", "=", $venta->getIdVen())->getAll());
        }

        return (
            new Response(
            count($ventas) > 0,
            count($ventas) > 0 ? "Ventas encontradas" : "Ventas inexistentes",
            count($ventas) > 0 ? 200 : 404,
            $ventas
            )
        )->json();
    }

    public function updateStatus($token, $idVen, $status){
        $venta = (new VentaModel())->where("idVen", "=", $idVen)->getFirst();
        if (!isset($venta)){
            return (new Response(
                false, 
                "Venta inexistente", 
                404
            ))->json();
        }
        
        $venta->setStatus($status);
        $result = $venta->where("idVen", "=", $idVen)->update();
        return (
            new Response(
            $result,
            $result ? "Venta actualizada" : "No se ha podido actualizar la venta",
            $result ? 200 : 404,
            )
        )->json();
    }
}
?>