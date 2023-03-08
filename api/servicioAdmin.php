<?php
require_once '../controller/ServicioController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
$id = $_REQUEST['id'] ?? null;

if ($METHOD == 'GET' && !$id) getAll($token);

function getAll($token){
    $controller = new ServicioController();
    echo $controller->getAllAdmin($token);
}
?>