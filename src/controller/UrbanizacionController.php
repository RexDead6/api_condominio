<?php
require_once dirname(__DIR__) . '/model/UrbanizacionModel.php';
require_once dirname(__DIR__) . '/model/TokenAccess.php';
require_once dirname(__DIR__) . '/util/Response.php';
require_once dirname(__DIR__) . '/util/ValidateApp.php';

class UrbanizacionController
{
    public function insert($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['nomUrb']
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

        if ((new ValidateApp())->isDuplicated("urbanizacion", "nomUrb", $JSON_DATA["nomUrb"])) {
            return (
                new Response(
                    false,
                    "El nombre de la comunidad ya existe",
                    400
                )
            )->json();
        }

        $success = (new UrbanizacionModel())->insert($JSON_DATA);
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
        
    }
}
?>