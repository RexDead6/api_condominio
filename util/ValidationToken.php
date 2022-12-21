<?php
require_once '../model/TokenAccess.php';
require_once '../util/Response.php';

$token = $_REQUEST['token'] ?? null;

if (!(new TokenAccess())->validateToken($token)){
    echo (new Response(false, "Sin Autorización", 400))->json();
    exit;
}
?>