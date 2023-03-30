<?php
require_once '../controller/ServicioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
include '../util/ValidationToken.php';

if ($METHOD == 'POST'){
    $controller = new ServicioController();
    echo $controller->insertPayService($token);
}
?>