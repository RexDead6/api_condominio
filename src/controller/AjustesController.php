<?php
require_once dirname( __DIR__ ) . '/model/AjustesModel.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';

class AjustesController {
    public function getAjuste($ajuste) {
        $ajuste = (new AjustesModel())->where("name", "=", $ajuste)->getFirst();
        return (new Response(
            isset($ajuste),
            isset($ajuste) ? "Ajuste encontrado" : "El ajuste solicitado no existe",
            isset($ajuste) ? 200 : 404,
            $ajuste
        ))->json();
    }

    public function editAjuste($token, $ajuste, $value) {
        $ajuste = (new AjustesModel())->where("name", "=", $ajuste)->getFirst();
        if (!isset($ajuste)) {
            return (
                new Response(
                false,
                "Ajuste no existe",
                404
                )
            )->json();
        }

        $ajuste->setValue($value);
        $success = $ajuste->where("name", "=", $ajuste)->update();
        return (new Response(
            $success,
            $success ? "Ajuste Actualizado" : "Ha ocurrido un problema en el servidor, intente de nuevo",
            $success ? 200 : 500
        ))->json();
    }
}