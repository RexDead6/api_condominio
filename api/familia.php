<?php
require_once '../controller/FamiliaController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST') Insert();
if ($METHOD == 'GET') GetById($token);

function Insert(){
    $controller = new FamiliaController();
    echo $controller->registrarFamilia();
}

function GetById($idFam){
    $controller = new FamiliaController();
    echo $controller->getById($idFam);
}
?>