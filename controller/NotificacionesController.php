<?php
require_once '../model/NotificacionesModel.php';
require_once '../util/ValidateApp.php';
require_once '../util/Response.php';
class NotificacionesController{
    public function getNotificacionesPendientes($token){
        $not_all = (new NotificacionesModel())->where("allNot", "=", 1)->orderBy("idNot")->getFirst();
        $not_user = (new NotificacionesModel())->where("idUsu", "=", explode("|", $token)[0])->where("allNot", "=", "0")->getAll();
        return (new Response(
            true,
            "Notificaciones",
            200,
            [
                "all"=>$not_all,
                "user"=>$not_user
            ]
        ))->json();
    }
}
?>