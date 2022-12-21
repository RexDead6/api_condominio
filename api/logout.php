<?php
require_once '../controller/UsuarioController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST'){
    $controller = new UsuarioController();
    echo $controller->logout($token);
}
?>