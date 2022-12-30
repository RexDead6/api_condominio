<?php
require_once '../controller/FamiliaController.php';
include '../util/ValidationToken.php';
$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST') Insert($token);

function Insert($token){
    $controller = new FamiliaController();
    echo $controller->registrarMiembroFamiliar($token);
}
?>