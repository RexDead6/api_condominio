<?php
require_once '../controller/FamiliaController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'GET'){
    $controller = new FamiliaController();
    echo $controller->getAll($token);
}
?>