<?php
require_once dirname(__DIR__) . '/model/UrbanizacionModel.php';
require_once dirname(__DIR__) . '/model/UsuarioAdminModel.php';
require_once dirname(__DIR__) . '/model/UsuarioModel.php';
require_once dirname(__DIR__) . '/model/FamiliaUrbanizacion.php';
require_once dirname(__DIR__) . '/model/FamiliaModel.php';
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

        if (((int) explode("|", $token)[2]) == 1){
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

    public function edit($token, $id) {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        if (((int) explode("|", $token)[2]) == 1){
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
}
?>