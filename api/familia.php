<?php
require_once '../controller/FamiliaController.php';
$METHOD = $_SERVER['REQUEST_METHOD'];
$idFam = $_REQUEST['idFam'] ?? null;

if ($METHOD == 'POST') Insert();

function Insert(){
    $controller = new FamiliaController();
    echo $controller->registrarFamilia();
}
?>