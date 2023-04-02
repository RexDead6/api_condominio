<?php
require_once '../controller/ServicioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
include '../util/ValidationToken.php';

if ($METHOD == 'GET') {
    $idSer = $_GET['idSer'] ?? null;
    $status = $_GET['status'] ?? null;
    $controller = new ServicioController();
    echo $controller->getFacturasAdmin($token, $idSer, $status);
}

if ($METHOD == 'PATCH') {
    $idFac = $_GET['idFac'] ?? null;
    $status = $_GET['status'] ?? null;
    $controller = new ServicioController();
    echo $controller->updateStatusFactura($token, $idFac, $status);
}
?>