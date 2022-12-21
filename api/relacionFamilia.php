<?php
require_once '../controller/FamiliaController.php';
$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST') Insert();

function Insert(){
    $controller = new FamiliaController();
    echo $controller->registrarMiembroFamiliar();
}
?>