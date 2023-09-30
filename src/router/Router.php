<?php
require_once dirname(__DIR__) . '/util/RouterHelper.php';
require_once dirname(__DIR__) . '/controller/FamiliaController.php';
require_once dirname(__DIR__) . '/controller/ServicioController.php';
require_once dirname(__DIR__) . '/controller/FacturaController.php';
require_once dirname(__DIR__) . '/controller/UsuarioController.php';
require_once dirname(__DIR__) . '/controller/AnunciosController.php';
require_once dirname(__DIR__) . '/controller/PagoMovilController.php';
require_once dirname(__DIR__) . '/controller/NotificacionesController.php';
require_once dirname(__DIR__) . '/controller/ProductoController.php';
require_once dirname(__DIR__) . '/controller/ProveedorController.php';
require_once dirname(__DIR__) . '/controller/CompraController.php';
require_once dirname(__DIR__) . '/controller/VentaController.php';
require_once dirname(__DIR__) . '/controller/AjustesController.php';
require_once dirname(__DIR__) . '/controller/UrbanizacionController.php';

$app = new RouterHelper();

$app->post("login", UsuarioController::class, "Login");
$app->post("logout", UsuarioController::class, "logout", true);
$app->post("usuario", UsuarioController::class, "registrarUsu");
$app->get("usuario", UsuarioController::class, "getAll");
$app->get("usuario/:idUsu", UsuarioController::class, "getById");
$app->get("usuarioInactivo", UsuarioController::class, "getInactive");
$app->patch('usuario', UsuarioController::class, 'update', true);
$app->post("usuario/image", UsuarioController::class, 'update_image', true);

$app->patch("rolUsuario/:idUsu/:idRol", UsuarioController::class, "update_rol", true);

$app->post("comunidad", UrbanizacionController::class, "insert", true);
$app->get("comunidad", UrbanizacionController::class, "getAll");
$app->get("comunidad/usuario", UrbanizacionController::class, "getUserUrb", true);
$app->get("comunidad/{idUrb}", UrbanizacionController::class, "getById");

$app->post("familia", FamiliaController::class, "registrarFamilia");
$app->get("familia", FamiliaController::class, "getByToken", true);
$app->get("familias", FamiliaController::class, "getAll", true);
$app->get("familiasComunidad/:idUrb", FamiliaController::class, "getByUrb");
$app->patch("familia", FamiliaController::class, "editarJefeFamiliar", true);
$app->post("relacionFamilia", FamiliaController::class, "registrarMiembroFamiliar", true);
$app->delete("removeUserFam/:idUsu", FamiliaController::class, "eliminarMiembroFamilia", true);
$app->patch("editJefeFam", FamiliaController::class, "editarJefeFamiliar", true);

$app->post("anuncios", AnunciosController::class, "insertAnuncio", true);
$app->get("anuncios", AnunciosController::class, "getAll");

$app->get("notificaciones", NotificacionesController::class, "getNotificacionesPendientes", true);

$app->get("bancos", PagoMovilController::class, "getBancos");

$app->post("pagoMovil", PagoMovilController::class, "insertPagoMovil", true);
$app->get("pagoMovil", PagoMovilController::class, "getall", true);
$app->get("pagoMovil/:idUsu", PagoMovilController::class, "getVenta");
$app->patch("pagoMovil", PagoMovilController::class, "update", true);
$app->patch("pagoMovilVenta/:idPvm", PagoMovilController::class, "updateVenta", true);

$app->post("servicio", ServicioController::class, "insert", true);
$app->get("servicio/:idUrb", ServicioController::class, "getAll", true);
$app->get("servicioAdmin/:idUrb", ServicioController::class, "getAllAdmin", true);

$app->post("factura", FacturaController::class, "insertFactura", true);
$app->get("factura/:status", FacturaController::class, 'getFacturas', true);
$app->get("facturaAdmin/:idSer/:status", FacturaController::class, 'getFacturasAdmin', true);
$app->patch("facturaAdmin/:idFac/:status", FacturaController::class, 'updateStatus', true);

$app->post("producto", ProductoController::class, "insert", true);
$app->patch("producto/:idPro", ProductoController::class, "update", true);
$app->get("producto", ProductoController::class, "getAll");
$app->get("productoAdmin", ProductoController::class, "getAllAdmin", true);

$app->post("proveedor", ProveedorController::class, "insert", true);
$app->get("proveedor", ProveedorController::class, "getAll", true);

$app->post("compra", CompraController::class, "insert", true);
$app->get("compra", CompraController::class, "getAll", true);

$app->get("ventaUsuarios", VentaController::class, "getUsersProducts", true);
$app->post("venta", VentaController::class, "insert", true);
$app->get("venta/:type/:status", VentaController::class, "getAll", true);
$app->patch("venta/:idVen/:status", VentaController::class, "updateStatus", true);

$app->get("ajuste/:ajuste", AjustesController::class, "getAjuste");

$app->get("test", UsuarioController::class, "test");
?>