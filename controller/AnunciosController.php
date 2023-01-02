<?php
require_once '../model/AnunciosModel.php';
require_once '../util/ValidateApp.php';
class AnuncionController{
    public function insertAnuncio($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['descAnu']
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

        $result = (new AnunciosModel())->insert([
            'idUsu'=> (int) explode("|", $token)[0],
            'descAnu' => $JSON_DATA['descAnu']
        ]);

        return (new Response(
            $result == false ? false:true, 
            $result == false ? "No se ha podido registrar su anuncio, intente mas tarde": "Anuncio registrado", 
            $result == false ? 500:201
        ))->json();
    }

    public function getAll(){
        $anuncios = (new AnunciosModel())->inner("usuarios", "idUsu")->getAll();
        return (new Response(
            count($anuncios) > 0,
            count($anuncios) > 0 ? "Anuncios encontrados" : "No hay anuncios disponibles",
            count($anuncios) > 0 ? 200 : 500,
            $anuncios
        ))->json();
    }
}
?>