<?php
require_once dirname( __DIR__ ) . '/model/NotificacionesModel.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/util/Response.php';
class NotificacionesController{
    public function getNotificacionesPendientes($token){
        $not_all = (new NotificacionesModel())->where("allNot", "=", 1)->orderBy("idNot")->getFirst();
        $not_user = (new NotificacionesModel())->where("idUsu", "=", explode("|", $token)[0])->where("allNot", "=", "0")->getAll();
        return (new Response(
            true,
            "Notificaciones",
            400,
            [
                "all"=>$not_all,
                "user"=>$not_user
            ]
        ))->json();
    }
}
?>