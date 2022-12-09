<?php
header("Content-Type: application/json");
require_once '../controller/FamiliaController.php';
require_once '../controller/UsuarioController.php';

$METHOD = $_SERVER['REQUEST_METHOD'];
$idFam = $_REQUEST['idFam'] ?? null;

if ($METHOD == 'POST') Insert();

function Insert(){
    $json_data = json_decode(file_get_contents('php://input'));
    $familiController = new FamiliaController();
    $usuarioController = new UsuarioController();

    
}
?>