<?php
require_once '../controller/ServicioController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
$id = $_REQUEST['id'] ?? null;

if ($METHOD == 'POST') insert($token);
if ($METHOD == 'GET' && !$id) getAll($token);

function insert($token){
    $controller = new ServicioController();
    echo $controller->insert($token);
}

function getAll($token){
    $controller = new ServicioController();
    echo $controller->getAll($token);
}
?>