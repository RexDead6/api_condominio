<?php
require_once '../controller/NotificacionesController.php';
include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'GET'){
    $controller = new NotificacionesController();
    echo $controller->getNotificacionesPendientes($token);
}
?>