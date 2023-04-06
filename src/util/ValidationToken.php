<?php
require_once '../model/TokenAccess.php';
require_once '../util/Response.php';

$token = getallheaders()['Authorization'] ?? null;

if (!(new TokenAccess())->validateToken($token)){
    echo (new Response(false, "Sin Autorización", 401))->json();
    exit;
}
?>