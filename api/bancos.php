<?php
require_once '../controller/PagoMovilController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'GET'){
    $controller = new PagoMovilController();
    echo $controller->getBancos();
}
?>