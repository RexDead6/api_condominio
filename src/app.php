<?php
require_once 'router/Router.php';
require_once 'util/Response.php';
require_once 'model/TokenAccess.php';

if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

$route = $app->getCurrentRoute();
if (!isset($route)) {
    echo (new Response(
        false, 
        "Ruta no existe", 
        404
    ))->json();
    exit;
}

$params = array_slice(explode("/", substr($_SERVER["REQUEST_URI"], 1)), 1) ?? [];

if ($route['AUTH']) {
    $token = getallheaders()['Authorization'] ?? null;

    if (!(new TokenAccess())->validateToken($token)){
        echo (new Response(false, "Sin Autorización", 401))->json();
        exit;
    }

    echo (new $route['CONTROLLER']())->{$route['FUN']}($token, ...$params);
}else{
    echo (new $route['CONTROLLER']())->{$route['FUN']}(...$params);
}
?>