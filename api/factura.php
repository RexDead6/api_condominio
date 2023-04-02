<?php
require_once '../controller/ServicioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
include '../util/ValidationToken.php';

if ($METHOD == 'POST'){
    $controller = new ServicioController();
    echo $controller->insertPayService($token);
}

if ($METHOD == 'GET') {
    $status = $_GET['status'] ?? null;
    $controller = new ServicioController();
    echo $controller->getFacturas($token, $status);
}
?>