-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.28-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla condominio.anuncios: ~0 rows (aproximadamente)

-- Volcando datos para la tabla condominio.auditoria: ~0 rows (aproximadamente)

-- Volcando datos para la tabla condominio.bancos: ~0 rows (aproximadamente)
INSERT INTO `bancos` (`idBan`, `nomBan`, `codBan`) VALUES
	(1, 'Banco de Venezuela', '0102');

-- Volcando datos para la tabla condominio.compra: ~1 rows (aproximadamente)
INSERT INTO `compra` (`idCom`, `idUsu`, `idProv`, `monto`, `porcentaje`, `fecha_compra`) VALUES
	(24, 49, 14, 1600, 20, '2023-07-01 14:19:04');

-- Volcando datos para la tabla condominio.compraproductos: ~1 rows (aproximadamente)
INSERT INTO `compraproductos` (`idPro`, `idCom`, `cantidad`, `costo`) VALUES
	(21, 24, 80, 20);

-- Volcando datos para la tabla condominio.factura: ~0 rows (aproximadamente)
INSERT INTO `factura` (`idFac`, `idSer`, `idFam`, `montoFac`, `fechapagFac`, `status`, `meses`) VALUES
	(30, 16, 31, 25, '2023-07-01 14:12:55', 1, 1);

-- Volcando datos para la tabla condominio.familias: ~1 rows (aproximadamente)
INSERT INTO `familias` (`idFam`, `descFam`, `hashFam`, `direccion`, `idUsu`) VALUES
	(11, 'Albarracin', '384c0c8ce1f7cb52d5e92f02db8047e1e88a150a', 'calle A #17', 10),
	(31, 'Rangel', 'c887bdc3fe1355bfeeeb402c8476ee7abfabf450', 'Calle A #17', 49);

-- Volcando datos para la tabla condominio.gruposfamiliares: ~5 rows (aproximadamente)
INSERT INTO `gruposfamiliares` (`idUsu`, `idFam`) VALUES
	(10, 11),
	(9, 11),
	(28, 11),
	(49, 31),
	(50, 31);

-- Volcando datos para la tabla condominio.pagomovil: ~2 rows (aproximadamente)
INSERT INTO `pagomovil` (`idPmv`, `idUsu`, `idBan`, `status`, `telPmv`, `cedPmv`, `venta`) VALUES
	(9, 10, 1, 1, '04129988086', '27863198', 1),
	(10, 49, 1, 1, '04129988083', '27863198', 1);

-- Volcando datos para la tabla condominio.pagoservicios: ~0 rows (aproximadamente)
INSERT INTO `pagoservicios` (`idFac`, `tipoPag`, `refPag`, `montoPag`, `comprobantePag`) VALUES
	(30, 'Pago Movil', '546354', 25, '');

-- Volcando datos para la tabla condominio.pagosventa: ~0 rows (aproximadamente)

-- Volcando datos para la tabla condominio.producto: ~1 rows (aproximadamente)
INSERT INTO `producto` (`idPro`, `idUsu`, `nomPro`, `costoPro`, `existPro`, `imgPro`, `status`) VALUES
	(21, 49, 'electric drink carabao', 24, 80, 'productos/21.jpg', 1);

-- Volcando datos para la tabla condominio.proveedor: ~0 rows (aproximadamente)
INSERT INTO `proveedor` (`idProv`, `idUsu`, `RIF`, `nomProv`) VALUES
	(13, 10, '27863198', 'Luis Alba'),
	(14, 49, '27863199', 'luis');

-- Volcando datos para la tabla condominio.pushmessages: ~0 rows (aproximadamente)

-- Volcando datos para la tabla condominio.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`idRol`, `nomRol`, `nivelRol`) VALUES
	(1, 'Administrador', 1),
	(2, 'Habitante', 3);

-- Volcando datos para la tabla condominio.servicios: ~1 rows (aproximadamente)
INSERT INTO `servicios` (`idSer`, `idPmv`, `descSer`, `isMensualSer`, `montoSer`, `statusSer`, `fechaInicioServicio`) VALUES
	(16, 9, 'Pago de luz', 1, 25, 1, '2023-07-01');

-- Volcando datos para la tabla condominio.token_access: ~2 rows (aproximadamente)
INSERT INTO `token_access` (`id`, `idUsu`, `token`, `fecha_registro`) VALUES
	(104, 10, '10|11|1|aa28f58739f8961998f78e090641672d3f0531fe7db1e2e793d79a55e42cca71541d5e9bee7352da2b02982d0782eb7be7a7', '2023-07-01 14:26:29'),
	(106, 10, '10|11|1|04a22e60fcb67a4a12097a407cb44eedad9bcfa6f9d23b68e64d0f69aff4f2d76174b9ecc845dc2444f7359b0ed743103cd4', '2023-07-01 14:37:11');

-- Volcando datos para la tabla condominio.usuarios: ~5 rows (aproximadamente)
INSERT INTO `usuarios` (`idUsu`, `idRol`, `statusUsu`, `cedUsu`, `nomUsu`, `apeUsu`, `generoUsu`, `telUsu`, `claveUsu`, `imgUsu`) VALUES
	(9, 2, 1, '7241160', 'Elizabeth', 'Rangel', 'Femenino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(10, 1, 1, '7235846', 'Luis', 'Albarracin', 'Masculino', '04123344452', '599ea529a46eb65177538638b292aa2c85f28ba4899b7a23d4e75c9757e899557948b720235ec1ca01f07745971ee67a15449ee15c4084e39e88bb7148a2431a', 'profile/default.png'),
	(28, 2, 1, '17015265', 'Karina', 'Albarracin', 'Femenino', '04129988081', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(49, 2, 1, '27863198', 'luis', 'Albarracin ', 'Masculino', '04129988083', '0f74c20f293baa5ac4ed4e24755f32e06620be14cc9fddb98d2a54519e2181ce2a0adfc2668e1cd556a7008bb4bc97a0d81c9f9994ec904f7f833f4f8bf698c8', 'profile/default.png'),
	(50, 2, 1, '29780428', 'Francis', 'Baloa', 'Femenino', '04129988083', 'da0157fd7b3a41553383201083eed505dcc264fe3bb0817724037403d61b84f152f83ce9f5d8443e7b7f21f1521dcb8019a03ee9bd30f94498f48855ef6a372e', 'profile/default.png');

-- Volcando datos para la tabla condominio.venta: ~0 rows (aproximadamente)

-- Volcando datos para la tabla condominio.ventaproductos: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
