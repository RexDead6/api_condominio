<?php
require_once '../controller/FamiliaController.php';
$METHOD = $_SERVER['REQUEST_METHOD'];
$idFam = $_REQUEST['idFam'] ?? null;

if ($METHOD == 'POST') Insert();
if ($METHOD == 'GET' && $idFam) GetById($idFam);

function Insert(){
    $controller = new FamiliaController();
    echo $controller->registrarFamilia();
}

function GetById($idFam){
    $controller = new FamiliaController();
    echo $controller->getById($idFam);
}
?>