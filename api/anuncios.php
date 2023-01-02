<?php
require_once '../controller/AnunciosController.php';

include '../util/ValidationToken.php';

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD == 'POST') Insert($token);
if ($METHOD == 'GET') GetAll();

function Insert($token){
    $controller = new AnuncionController();
    echo $controller->insertAnuncio($token);
}

function GetAll(){
    $controller = new AnuncionController();
    echo $controller->getAll();
}
?>