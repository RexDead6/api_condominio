<?php
require_once dirname( __DIR__ ) . '/util/RouterHelper.php';
require_once dirname( __DIR__ ) . '/controller/FamiliaController.php';
require_once dirname( __DIR__ ) . '/controller/ServicioController.php';
require_once dirname( __DIR__ ) . '/controller/FacturaController.php';
require_once dirname( __DIR__ ) . '/controller/UsuarioController.php';
require_once dirname( __DIR__ ) . '/controller/AnunciosController.php';
require_once dirname( __DIR__ ) . '/controller/PagoMovilController.php';
require_once dirname( __DIR__ ) . '/controller/NotificacionesController.php';

$app = new RouterHelper();

$app->post("login", UsuarioController::class, "Login");
$app->post("logout", UsuarioController::class, "logout", true);
$app->post("usuario", UsuarioController::class, "registrarUsu");
$app->get("usuario", UsuarioController::class, "getAll");
$app->get("usuario/:idUsu", UsuarioController::class, "getById");
$app->get("usuarioInactivo", UsuarioController::class, "getInactive");

$app->post("familia", FamiliaController::class, "registrarFamilia");
$app->get("familia", FamiliaController::class, "getById", true);
$app->get("familias", FamiliaController::class, "getAll", true);
$app->post("relacionFamilia", FamiliaController::class, "registrarMiembroFamiliar", true);

$app->post("anuncios", AnunciosController::class, "insertAnuncio", true);
$app->get("anuncios", AnunciosController::class, "getAll");

$app->get("notificaciones", NotificacionesController::class, "getNotificacionesPendientes", true);

$app->get("bancos", PagoMovilController::class, "getBancos");

$app->post("pagoMovil", PagoMovilController::class, "insertPagoMovil", true);
$app->get("pagoMovil", PagoMovilController::class, "getall", true);
$app->patch("pagoMovil", PagoMovilController::class, "update", true);

$app->post("servicio", ServicioController::class, "insert", true);
$app->get("servicio", ServicioController::class, "getAll", true);
$app->get("servicioAdmin", ServicioController::class, "getAllAdmin", true);

$app->post("factura", FacturaController::class, "insertFactura", true);
$app->get("factura/:status", FacturaController::class, 'getFacturas', true);
$app->get("facturaAdmin/:idSer/:status", FacturaController::class, 'getFacturasAdmin', true);
$app->patch("facturaAdmin/:idFac/:status", FacturaController::class, 'updateStatus', true);
?>