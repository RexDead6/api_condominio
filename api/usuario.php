<?php
require_once '../controller/UsuarioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
$id = $_REQUEST['id'] ?? null;

if ($METHOD == 'GET' && !$id) GetAll();
if ($METHOD == 'GET' && $id) GetById($id);
if ($METHOD == 'POST') Insert();

function GetAll(){
    include '../util/ValidationToken.php';
    
    $controller = new UsuarioController();
    echo $controller->getAll();
}

function GetById($id){
    
}

function Insert(){
    $controller = new UsuarioController();
    echo $controller->registrarUsu();
}
?>