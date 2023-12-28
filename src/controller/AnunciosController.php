<?php
require_once dirname( __DIR__ ) . '/model/AnunciosModel.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/util/FileManager.php';
class AnunciosController{
    public function insertAnuncio($token){
        if (!isset($_POST['descAnu'])){
            return (new Response(
                false, 
                "Datos incompletos (descAnu)", 
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

        $desc = $_POST['descAnu'];
        $idUrb = $_POST['idUrb'];
        $path_image = "";

        $idAnu = (new AnunciosModel())->insert([
            'idUsu'=> (int) explode("|", $token)[0],
            'descAnu' => $desc,
            'idUrb' => $idUrb
        ]);

        if (isset($_FILES['image'])){
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $path_image = "anuncios/{$idAnu}.{$extension}";
            $resp = FileManager::uploadFile($_FILES['image'], str_replace("\\", "/", dirname( __DIR__ ))."/assets/".$path_image);
            if (!$resp[0]){
                return (new Response(
                    false,
                    $resp[1], 
                    400
                ))->json();
            }
            $anuncio = (new AnunciosModel())->where("idAnu", "=", $idAnu)->getFirst();
            $anuncio->setImage($path_image);
            $anuncio->where("idAnu", "=", $idAnu)->update();
        }

        return (new Response(
            true, 
            "Anuncio registrado", 
            201
        ))->json();
    }

    public function getAll($idUrb){
        $anuncios = (new AnunciosModel())->inner("usuarios", "idUsu")->inner("roles", "idRol", "usuarios")->where("idUrb", "=", $idUrb)->orderBy("idAnu")->getAll();
        return (new Response(
            count($anuncios) > 0,
            count($anuncios) > 0 ? "Anuncios encontrados" : "No hay anuncios disponibles",
            count($anuncios) > 0 ? 200 : 500,
            $anuncios
        ))->json();
    }
}
?>