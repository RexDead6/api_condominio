<?php
require_once '../controller/UsuarioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST'){
    $controller = new UsuarioController();
    echo $controller->Login();
}
?>