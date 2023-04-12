<?php
require_once dirname( __DIR__ ) . '/model/ProductoModel.php';

class ProductoController{

    public function insert($token){
        $JSON_DATA = $_POST;
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['nomPro']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")",
                400
            ))->json();
        }

        if (!isset($_FILES['img'])) {
            return (new Response(
                false, 
                "Debe enviar una imagen del producto",
                400
            ))->json();
        }

        $idPro = (new ProductoModel())->insert(["idUsu"=>explode("|", $token)[0], "nomPro"=>$JSON_DATA['nomPro']]);
        if ($idPro == false) {
            return (new Response(
                false, 
                "No se ha podido registrar su producto, intente de nuevo",
                500
            ))->json();
        }

        $extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $path_image = "productos/{$idPro}.{$extension}";
        $resp = FileManager::uploadFile($_FILES['img'], str_replace("\\", "/", dirname( __DIR__ )) ."/assets/".$path_image);
        if (!$resp[0]){
            return (new Response(
                false,
                $resp[1], 
                400
            ))->json();
        }
        $producto = (new ProductoModel())->where("idPro", "=", $idPro)->getFirst();
        $producto->setImgPro($path_image);
        $producto->where("idPro", "=", $idPro)->update();

        return (new Response(
            true, 
            "Producto registrado", 
            201
        ))->json();
    }

    public function getAll(){
        $productos = (new ProductoModel())->where("status", "=", 1)->inner("usuarios", "idUsu")->getAll();
        return (new Response(
            count($productos) > 0,
            count($productos) > 0 ? "Productos encontrados" : "No se encontraron productos",
            count($productos) > 0 ? 200 : 500,
            $productos
        ))->json();
    }
    public function getAllAdmin($token){
        $productos = (new ProductoModel())->where("status", "=", 1)->where("idUsu", "=", explode("|", $token)[0])->getAll();
        return (new Response(
            count($productos) > 0,
            count($productos) > 0 ? "Productos encontrados" : "No se encontraron productos",
            count($productos) > 0 ? 200 : 500,
            $productos
        ))->json();
    }

    public function update($token, $idPro){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $producto = (new ProductoModel())->inner("usuarios", "idUsu")->where("idPro", "=", $idPro)->getFirst();
        if (!isset($producto)) {
            return (new Response(
                true, 
                "Producto no existe", 
                404
            ))->json();
        }

        if ($producto->getUsuario()->getIdUsu() != explode("|", $token)[0]) {
            return (new Response(
                true, 
                "Producto no pertenece a este usuario", 
                400
            ))->json();
        }

        if (isset($JSON_DATA['nomPro'])) $producto->setNomPro($JSON_DATA['nomPro']);
        if (isset($JSON_DATA['status'])) $producto->setStatus($JSON_DATA['status']);
        
        $result = $producto->update();
        return (new Response(
            $result,
            $result ? "Producto actualizado" : "No se ha podido actualizar productos",
            $result ? 200 : 500,
        ))->json();
    }
}
?>