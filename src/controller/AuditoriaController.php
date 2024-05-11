<?php
require_once dirname( __DIR__ ) . '/model/AuditoriaModel.php';
require_once dirname( __DIR__ ) . '/util/Response.php';

class AuditoriaController {
    public function getAll() {
        $registros = (new AuditoriaModel())->inner("usuarios", "idUsu")->getAll();

        return (new Response(
            count($registros) > 0,
            count($registros) > 0 ? "Auditoria encontrada" : "No hay registros disponibles",
            count($registros) > 0 ? 200 : 500,
            $registros
        ))->json();
    }
}
?>