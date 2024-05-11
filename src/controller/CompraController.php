<?php 
require_once dirname( __DIR__ ) . '/model/ProductoModel.php';
require_once dirname( __DIR__ ) . '/model/CompraModel.php';
require_once dirname( __DIR__ ) . '/model/ProductosCompraModel.php';
require_once dirname( __DIR__ ) . '/util/PDFManager.php';
require_once dirname( __DIR__ ) . '/util/Formating.php';
class CompraController{

    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $pdfData = [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idProv', 'monto', 'porcentaje', 'productosCompra']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $prov = (new ProveedorModel())->where("idProv", "=", $JSON_DATA['idProv'])->getFirst();
        if (!isset($prov)) {
            return (new Response(
                false, 
                "Proveedor inexistente", 
                404
            ))->json();
        }

        $idCom = (new CompraModel())->insert(
            [
                "idUsu"=>explode("|", $token)[0],
                "idProv"=>$JSON_DATA['idProv'],
                "monto"=>$JSON_DATA['monto'],
                "porcentaje"=>$JSON_DATA['porcentaje']
            ]
        );
        
        if ($idCom == false) {
            return (new Response(
                false, 
                "No se ha podido registrar su compra, intente de nuevo",
                400
            ))->json();
        }

        $compra = (new CompraModel())->where("idCom", "=", $idCom)->getFirst();

        (new AuditoriaModel())->insert([
            "idUsu" => (int) explode("|", $token)[0],
            "Descripcion" => "Registro de Compra (Referencia: ".$idCom.")"
        ]);

        $pdfData['rif'] = $prov->getRIF();
        $pdfData['proveedor'] = $prov->getNomProv();
        $pdfData['n_factura'] = $idCom;
        $pdfData['fecha_factura'] = $compra->getFecha_compra();
        $pdfData['rows'] = [];
        $pdfData['total'] = 0;

        foreach ($JSON_DATA['productosCompra'] as $producto) {

            $pro = (new ProductoModel())->where("idPro", "=", $producto['producto']['idPro'])->getFirst();
            if (!isset($pro)){
                continue;
            }

            (new ProductosCompraModel())->insert([
                "idPro"=>$producto['producto']['idPro'],
                "idCom"=>$idCom,
                "cantidad"=>$producto['cantidad'],
                "costo"=>$producto['costo']
            ]);

            $pdfData['rows'][] = [$pro->getNomPro(), $producto['cantidad'], Formating::numberFormat($producto['costo']), Formating::numberFormat($producto['cantidad'] * $producto['costo'])];
            $pdfData['total'] += $producto['cantidad'] * $producto['costo'];

            $costo_venta = $producto['costo'] + (($JSON_DATA['porcentaje'] / 100) * $producto['costo']);
            $proModel = (new ProductoModel())->where("idPro", "=", $producto['producto']['idPro'])->getFirst();
            $proModel->setCostoPro($costo_venta);
            $proModel->setExistPro($producto['cantidad'] + $proModel->getExistPro());
            $proModel->where("idPro", "=", $producto['producto']['idPro'])->update();
        }
        $pdfData['total'] = Formating::numberFormat($pdfData['total']);

        $pdfManager = new PDFManager();
        $pdfManager->template("compra.template.html", $pdfData);
        $pdfManager->output("compra_$idCom.pdf");

        return (new Response(
            true, 
            "Compra registrada correctamente", 
            201
        ))->json();
    }

    public function getAll($token){
        $compras = (new CompraModel())->inner("proveedor", "idProv")->where("com.idUsu", "=", explode("|", $token)[0])->orderBy("idCom")->getAll();
        foreach ($compras as $compra) {
            $compra->setProductos((new ProductosCompraModel())->inner("producto", "idPro")->where("idCom", "=", $compra->getIdCom())->getAll());
        }
        return (new Response(
            count($compras) > 0,
            count($compras) > 0 ? "Compras encontradas" : "No se han encontrado compras",
            count($compras) > 0 ? 200 : 404,
            $compras
        ))->json();
    }
}
?>