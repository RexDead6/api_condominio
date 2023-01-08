<?php
require_once '../controller/PagoMovilController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST') Insert($token);
if ($METHOD == 'GET') GetAll($token);

function Insert($token){
    $controller = new PagoMovilController();
    echo $controller->insertPagoMovil($token);
}

function GetAll($token){
    $controller = new PagoMovilController();
    echo $controller->getall($token);
}
?>