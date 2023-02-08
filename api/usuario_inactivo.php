<?php
require_once '../controller/UsuarioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'GET'){
    $controller = new UsuarioController();
    echo $controller->getInactive();
}
?>