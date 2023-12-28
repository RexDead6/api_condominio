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


-- Volcando estructura de base de datos para condominio
CREATE DATABASE IF NOT EXISTS `condominio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `condominio`;

-- Volcando estructura para tabla condominio.ajustes
CREATE TABLE IF NOT EXISTS `ajustes` (
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.ajustes: ~1 rows (aproximadamente)
INSERT INTO `ajustes` (`name`, `value`) VALUES
	('DIVISA', '29.45');

-- Volcando estructura para tabla condominio.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `idAnu` int(11) NOT NULL AUTO_INCREMENT,
  `idUrb` int(11) NOT NULL,
  `idUsu` int(11) NOT NULL,
  `descAnu` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL DEFAULT '',
  `fechaAnu` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idAnu`),
  KEY `FK_anuncios_usuarios` (`idUsu`),
  KEY `FK2_anuncios_urbanizacion` (`idUrb`),
  CONSTRAINT `FK2_anuncios_urbanizacion` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_anuncios_usuarios` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.anuncios: ~2 rows (aproximadamente)
INSERT INTO `anuncios` (`idAnu`, `idUrb`, `idUsu`, `descAnu`, `image`, `fechaAnu`) VALUES
	(45, 1, 10, 'Prueba anuncio', '', '2023-11-21 12:25:02'),
	(46, 2, 10, 'Anuncio de caña de azúcar ', '', '2023-11-21 12:25:19');

-- Volcando estructura para tabla condominio.auditoria
CREATE TABLE IF NOT EXISTS `auditoria` (
  `idAud` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idAud`),
  KEY `FK_auditoria_usuario` (`idUsu`),
  CONSTRAINT `FK_auditoria_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.auditoria: ~0 rows (aproximadamente)

-- Volcando estructura para tabla condominio.bancos
CREATE TABLE IF NOT EXISTS `bancos` (
  `idBan` int(11) NOT NULL AUTO_INCREMENT,
  `nomBan` varchar(50) NOT NULL,
  `codBan` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idBan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.bancos: ~1 rows (aproximadamente)
INSERT INTO `bancos` (`idBan`, `nomBan`, `codBan`) VALUES
	(1, 'Banco de Venezuela', '0102');

-- Volcando estructura para tabla condominio.compra
CREATE TABLE IF NOT EXISTS `compra` (
  `idCom` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) DEFAULT NULL,
  `idProv` int(11) NOT NULL,
  `monto` float NOT NULL DEFAULT 0,
  `porcentaje` float NOT NULL,
  `fecha_compra` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idCom`),
  KEY `FK1compra_proveedor` (`idProv`),
  KEY `FK2compra_usuario` (`idUsu`),
  CONSTRAINT `FK1compra_proveedor` FOREIGN KEY (`idProv`) REFERENCES `proveedor` (`idProv`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2compra_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.compra: ~1 rows (aproximadamente)
INSERT INTO `compra` (`idCom`, `idUsu`, `idProv`, `monto`, `porcentaje`, `fecha_compra`) VALUES
	(24, 49, 14, 1600, 20, '2023-07-01 14:19:04');

-- Volcando estructura para tabla condominio.compraproductos
CREATE TABLE IF NOT EXISTS `compraproductos` (
  `idPro` int(11) NOT NULL,
  `idCom` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` float NOT NULL DEFAULT 0,
  KEY `FK1productos_compra` (`idPro`),
  KEY `FK2compra_productos` (`idCom`),
  CONSTRAINT `FK1productos_compra` FOREIGN KEY (`idPro`) REFERENCES `producto` (`idPro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2compra_productos` FOREIGN KEY (`idCom`) REFERENCES `compra` (`idCom`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.compraproductos: ~1 rows (aproximadamente)
INSERT INTO `compraproductos` (`idPro`, `idCom`, `cantidad`, `costo`) VALUES
	(21, 24, 80, 20);

-- Volcando estructura para tabla condominio.factura
CREATE TABLE IF NOT EXISTS `factura` (
  `idFac` int(11) NOT NULL AUTO_INCREMENT,
  `idSer` int(11) NOT NULL,
  `idFam` int(11) NOT NULL,
  `montoFac` float NOT NULL,
  `fechapagFac` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 2,
  `meses` int(11) NOT NULL,
  PRIMARY KEY (`idFac`),
  KEY `FK_servicio_factura` (`idSer`),
  KEY `FK_servicio_familia` (`idFam`),
  CONSTRAINT `FK_servicio_factura` FOREIGN KEY (`idSer`) REFERENCES `servicios` (`idSer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_servicio_familia` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.factura: ~7 rows (aproximadamente)
INSERT INTO `factura` (`idFac`, `idSer`, `idFam`, `montoFac`, `fechapagFac`, `status`, `meses`) VALUES
	(30, 16, 31, 25, '2023-07-01 14:12:55', 1, 1),
	(31, 18, 11, 58.9, '2023-07-30 18:05:34', 1, 1),
	(32, 17, 11, 58.9, '2023-07-30 19:21:58', 1, 1),
	(33, 19, 11, 4417.5, '2023-07-30 19:23:09', 1, 1),
	(34, 18, 11, 117.8, '2023-09-30 16:23:50', 1, 2),
	(35, 18, 11, 58.9, '2023-11-05 13:37:01', 2, 4),
	(36, 21, 11, 353.4, '2023-11-19 22:14:04', 1, 1);

-- Volcando estructura para tabla condominio.familias
CREATE TABLE IF NOT EXISTS `familias` (
  `idFam` int(11) NOT NULL AUTO_INCREMENT,
  `descFam` varchar(50) NOT NULL,
  `hashFam` varchar(250) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `idUsu` int(11) NOT NULL,
  PRIMARY KEY (`idFam`),
  KEY `FK1_jefe_familia` (`idUsu`),
  CONSTRAINT `FK1_jefe_familia` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.familias: ~5 rows (aproximadamente)
INSERT INTO `familias` (`idFam`, `descFam`, `hashFam`, `direccion`, `idUsu`) VALUES
	(11, 'Albarracin', '384c0c8ce1f7cb52d5e92f02db8047e1e88a150a', 'calle A #17', 10),
	(31, 'Rangel', 'c887bdc3fe1355bfeeeb402c8476ee7abfabf450', 'Calle A #17', 49),
	(32, 'García', '7a252ba41e5d976d78111db57db0a5f42e2239ad', 'Calle "A"', 51),
	(34, 'Rangel', '8dab5abb86e227ea6e5fc52f5e8a05a22297fdea', 'calle E #17', 9),
	(35, 'Alba', '5a017f953151e4ca9433636e68d71aad8521df81', 'calle E', 52);

-- Volcando estructura para tabla condominio.familiaurbanizacion
CREATE TABLE IF NOT EXISTS `familiaurbanizacion` (
  `idFam` int(11) NOT NULL,
  `idUrb` int(11) NOT NULL,
  KEY `familiaurbanizacion_FK` (`idFam`),
  KEY `familiaurbanizacion_FK_1` (`idUrb`),
  CONSTRAINT `familiaurbanizacion_FK` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`),
  CONSTRAINT `familiaurbanizacion_FK_1` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.familiaurbanizacion: ~6 rows (aproximadamente)
INSERT INTO `familiaurbanizacion` (`idFam`, `idUrb`) VALUES
	(11, 1),
	(31, 1),
	(32, 1),
	(11, 2),
	(34, 3),
	(35, 5);

-- Volcando estructura para tabla condominio.gruposfamiliares
CREATE TABLE IF NOT EXISTS `gruposfamiliares` (
  `idUsu` int(11) NOT NULL,
  `idFam` int(11) NOT NULL,
  KEY `FK_familia_grupos` (`idFam`),
  KEY `FK_usuarios_grupos` (`idUsu`),
  CONSTRAINT `FK_familia_grupos` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_usuarios_grupos` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.gruposfamiliares: ~5 rows (aproximadamente)
INSERT INTO `gruposfamiliares` (`idUsu`, `idFam`) VALUES
	(10, 11),
	(28, 11),
	(49, 31),
	(50, 31),
	(51, 32),
	(9, 34),
	(52, 35);

-- Volcando estructura para tabla condominio.pagomovil
CREATE TABLE IF NOT EXISTS `pagomovil` (
  `idPmv` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `idBan` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `telPmv` varchar(11) NOT NULL,
  `cedPmv` varchar(11) NOT NULL,
  `venta` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idPmv`),
  KEY `FK_pagomovil_usuario` (`idUsu`),
  KEY `FK_pagomovil_banco` (`idBan`),
  CONSTRAINT `FK_pagomovil_banco` FOREIGN KEY (`idBan`) REFERENCES `bancos` (`idBan`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pagomovil_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pagomovil: ~2 rows (aproximadamente)
INSERT INTO `pagomovil` (`idPmv`, `idUsu`, `idBan`, `status`, `telPmv`, `cedPmv`, `venta`) VALUES
	(9, 10, 1, 1, '04129988086', '27863198', 1),
	(10, 49, 1, 1, '04129988083', '27863198', 1);

-- Volcando estructura para tabla condominio.pagoservicios
CREATE TABLE IF NOT EXISTS `pagoservicios` (
  `idFac` int(11) NOT NULL,
  `tipoPag` varchar(50) NOT NULL,
  `refPag` varchar(50) NOT NULL,
  `montoPag` float NOT NULL,
  `comprobantePag` varchar(50) NOT NULL DEFAULT '',
  KEY `FK_pagoservicio_factura` (`idFac`),
  CONSTRAINT `FK_pagoservicio_factura` FOREIGN KEY (`idFac`) REFERENCES `factura` (`idFac`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pagoservicios: ~4 rows (aproximadamente)
INSERT INTO `pagoservicios` (`idFac`, `tipoPag`, `refPag`, `montoPag`, `comprobantePag`) VALUES
	(30, 'Pago Movil', '546354', 25, ''),
	(31, 'Pago Movil', '5454884', 2, ''),
	(32, 'Pago Movil', '646454', 2, ''),
	(33, 'Pago Movil', '646464', 150, ''),
	(34, 'Pago Movil', '6464', 4, ''),
	(35, 'Pago Movil', '54545', 2, ''),
	(36, 'Pago Movil', '2308', 12, '');

-- Volcando estructura para tabla condominio.pagosventa
CREATE TABLE IF NOT EXISTS `pagosventa` (
  `idVen` int(11) NOT NULL,
  `tipoPag` varchar(50) NOT NULL DEFAULT '',
  `refPag` varchar(50) NOT NULL DEFAULT '',
  `monto` float NOT NULL DEFAULT 0,
  KEY `FK1_pagos_venta` (`idVen`),
  CONSTRAINT `FK1_pagos_venta` FOREIGN KEY (`idVen`) REFERENCES `venta` (`idVen`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pagosventa: ~0 rows (aproximadamente)

-- Volcando estructura para tabla condominio.producto
CREATE TABLE IF NOT EXISTS `producto` (
  `idPro` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `nomPro` varchar(50) NOT NULL,
  `divisa` tinyint(4) NOT NULL DEFAULT 0,
  `costoPro` float NOT NULL DEFAULT 0,
  `existPro` int(11) NOT NULL DEFAULT 0,
  `imgPro` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPro`),
  KEY `FK_producto_usuario` (`idUsu`),
  CONSTRAINT `FK_producto_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.producto: ~2 rows (aproximadamente)
INSERT INTO `producto` (`idPro`, `idUsu`, `nomPro`, `divisa`, `costoPro`, `existPro`, `imgPro`, `status`) VALUES
	(21, 49, 'electric drink carabao', 0, 24, 80, 'productos/21.jpg', 1),
	(22, 10, 'control de ps3', 0, 0, 0, 'productos/22.jpg', 1);

-- Volcando estructura para tabla condominio.proveedor
CREATE TABLE IF NOT EXISTS `proveedor` (
  `idProv` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `RIF` varchar(50) NOT NULL DEFAULT '',
  `nomProv` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idProv`),
  KEY `FK1proveedor_usuario` (`idUsu`),
  CONSTRAINT `FK1proveedor_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.proveedor: ~2 rows (aproximadamente)
INSERT INTO `proveedor` (`idProv`, `idUsu`, `RIF`, `nomProv`) VALUES
	(13, 10, '27863198', 'Luis Alba'),
	(14, 49, '27863199', 'luis');

-- Volcando estructura para tabla condominio.pushmessages
CREATE TABLE IF NOT EXISTS `pushmessages` (
  `idNot` int(11) NOT NULL AUTO_INCREMENT,
  `tituloNot` varchar(250) NOT NULL,
  `descNot` varchar(250) NOT NULL,
  `imgNot` varchar(250) NOT NULL,
  `statusNot` int(11) NOT NULL DEFAULT 1,
  `allNot` int(11) NOT NULL DEFAULT 0,
  `idUsu` int(11) DEFAULT NULL,
  `tipoNot` varchar(250) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idNot`),
  KEY `notificaciones_usuario` (`idUsu`),
  CONSTRAINT `notificaciones_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pushmessages: ~0 rows (aproximadamente)

-- Volcando estructura para tabla condominio.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nomRol` varchar(50) NOT NULL,
  `nivelRol` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`idRol`, `nomRol`, `nivelRol`) VALUES
	(2, 'Habitante', 2),
	(4, 'Administrador', 1);

-- Volcando estructura para tabla condominio.servicios
CREATE TABLE IF NOT EXISTS `servicios` (
  `idSer` int(11) NOT NULL AUTO_INCREMENT,
  `idPmv` int(11) NOT NULL,
  `idUrb` int(11) NOT NULL DEFAULT 1,
  `descSer` varchar(50) NOT NULL,
  `isMensualSer` int(11) NOT NULL DEFAULT 0,
  `divisa` tinyint(4) NOT NULL DEFAULT 0,
  `montoSer` float NOT NULL,
  `statusSer` int(11) NOT NULL DEFAULT 1,
  `fechaInicioServicio` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idSer`),
  KEY `FK_servicios_pagomovil` (`idPmv`),
  KEY `FK2_servicios_urbanizacion` (`idUrb`),
  CONSTRAINT `FK2_servicios_urbanizacion` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_servicios_pagomovil` FOREIGN KEY (`idPmv`) REFERENCES `pagomovil` (`idPmv`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.servicios: ~4 rows (aproximadamente)
INSERT INTO `servicios` (`idSer`, `idPmv`, `idUrb`, `descSer`, `isMensualSer`, `divisa`, `montoSer`, `statusSer`, `fechaInicioServicio`) VALUES
	(16, 9, 1, 'Pago de luz', 1, 0, 25, 1, '2023-07-01'),
	(17, 9, 1, 'Agua mensual', 1, 1, 2, 1, '2023-07-08'),
	(18, 9, 1, 'condominio mensualidad', 1, 1, 2, 1, '2023-07-19'),
	(19, 9, 1, 'pago', 0, 1, 150, 1, '2023-07-30'),
	(20, 9, 2, 'Luz solar', 1, 1, 20, 1, '2023-08-12'),
	(21, 9, 1, 'mantenimiento areas verdes', 1, 1, 12, 1, '2023-11-19');

-- Volcando estructura para tabla condominio.token_access
CREATE TABLE IF NOT EXISTS `token_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `token` varchar(250) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK1_token_user` (`idUsu`),
  CONSTRAINT `FK1_token_user` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.token_access: ~12 rows (aproximadamente)
INSERT INTO `token_access` (`id`, `idUsu`, `token`, `fecha_registro`) VALUES
	(118, 51, '51|00|2|b25a49a391911b988163555f15ccb876d2613b373d9f0ff28090a5c2e9ebdc10ffa23f1907f91f8739661da74d9851247818', '2023-07-07 10:58:11'),
	(119, 51, '51|32|3|ccdeb3535ce004d7e779ef0dea83613171dcebe69ff665580874202b4b7dc423d9d5fe71b9edaf87b63bb215faa75b4c574f', '2023-07-07 11:00:28'),
	(143, 10, '10|11|1|b2a2b7be6833aeaea5702431da26a347cf12018b8ab3a6a763a4b2421f9cb622225924323b55312b691c0af0b8ded0e6292d', '2023-07-07 19:22:56'),
	(144, 10, '10|11|1|f1a457bfbfb2818dd0d1f7c97a16820f5638b5ec7faff08c499cd47eb17fe8f125a80b54e09abf68ea8af49fa64ae96022b4', '2023-07-08 17:15:19'),
	(145, 10, '10|11|1|a241bf0796d3068ebceec750c5b26b8b9a1b366c7a29092d48e7f3da431a9fae28895fabb3bb3d784ae40d3174ab72d3251d', '2023-07-08 17:15:20'),
	(146, 10, '10|11|1|68fb7501c5a1256883c5024f363109ec7f1673a6a17eb7771625b10834ee9b03363f02ced0aa0c4446619fb5611fff98de80', '2023-08-01 19:46:09'),
	(147, 10, '10|11|2|0617920a765aecd28793b308ef5b9794bf761aec5d3b2b9ffd3f04df12650b17888e9a546712a15586c16f95eaac6165d287', '2023-08-12 12:25:11'),
	(148, 10, '10|11|2|6f4bdd0e0f748be3222dcfb1ecba6bd7d394da3d16e4d5ef9ad3a3e64720655504cb446760f634b016189034a74bbfdce197', '2023-08-16 20:29:22'),
	(152, 10, '10|11|1|6a772154a680dcaeb6502bfc3b4f927b06cc3f01791bae62a07dcb8ecae8236cf4b3307bddeae1c103f02688993e319427f5', '2023-09-06 16:20:13'),
	(153, 10, '10|11|1|1d68928b3a1c4ed809d012eae0f0dbc7c7e734382032b62634afe0635580a068d392566cc7efa581af1d95930b2aeeee7f76', '2023-09-06 16:23:01'),
	(154, 10, '10|11|1|5361e8adbb55c9ac064c9757222c3a95867ff1b9329d2066909f3e461dcd2f407ecd8460af3507656a5010715a2774089292', '2023-09-06 16:25:30'),
	(155, 10, '10|11|1|c14c21fe48f3db878a2360e5def0ec51ce0786431f53d9412153dd5117163a126fe8aeaa617a5938eb850784b36f53e84b5a', '2023-09-28 12:09:28'),
	(159, 10, '10|11|1|7b774d142f561a0e72df774b6e7bb3ec288fabedd04b09f77031b68dc9177bcaecc22b38e8ba5648674f18c46541e880cda4', '2023-10-21 18:34:34'),
	(160, 10, '10|11|1|1d2c74775541d3ada7a769f9ee655133bf2d8390f2cc93050f677fc289d5d9fd5502e0978f78f4e5a094892f3d0595f62f41', '2023-11-05 15:44:51'),
	(161, 10, '10|11|1|a1b5189f5cbb1d042e0b18d0274c2d6ce63ca5569d5bed59d82ad937faae0ab3d70d844ac9cb82627a216ac83070f9fa2c4e', '2023-11-17 15:55:59'),
	(162, 10, '10|11|1|9ca97fa9c24bc30fa8d7953de8aee310145f30febbb14ec20176d23388ff41cc965e8a610b5a594e9c597196392e8ebf32c0', '2023-11-19 20:58:15');

-- Volcando estructura para tabla condominio.urbanizacion
CREATE TABLE IF NOT EXISTS `urbanizacion` (
  `idUrb` int(11) NOT NULL AUTO_INCREMENT,
  `nomUrb` varchar(100) NOT NULL,
  `direccion` varchar(50) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idUrb`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.urbanizacion: ~5 rows (aproximadamente)
INSERT INTO `urbanizacion` (`idUrb`, `nomUrb`, `direccion`, `status`) VALUES
	(1, 'Alma mater', 'El paseo, El limon', 1),
	(2, 'Caña de azucar', 'Caña de azucar', 1),
	(3, 'alma mater II', 'el paseo el limon', 1),
	(4, 'La Candelaria ', 'al final de caña de azucar', 1),
	(5, 'Valle verde', 'El limón ', 1);

-- Volcando estructura para tabla condominio.usuarioadmin
CREATE TABLE IF NOT EXISTS `usuarioadmin` (
  `idUsu` int(11) NOT NULL,
  `idUrb` int(11) NOT NULL,
  KEY `FK1_usu_admin` (`idUsu`),
  KEY `FK2_urb_admin` (`idUrb`),
  CONSTRAINT `FK1_usu_admin` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_urb_admin` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.usuarioadmin: ~2 rows (aproximadamente)
INSERT INTO `usuarioadmin` (`idUsu`, `idUrb`) VALUES
	(10, 1);

-- Volcando estructura para tabla condominio.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsu` int(11) NOT NULL AUTO_INCREMENT,
  `idRol` int(11) NOT NULL DEFAULT 2,
  `statusUsu` int(11) NOT NULL DEFAULT 0,
  `cedUsu` varchar(10) NOT NULL,
  `nomUsu` varchar(20) NOT NULL,
  `apeUsu` varchar(20) NOT NULL,
  `generoUsu` varchar(9) NOT NULL,
  `telUsu` varchar(11) NOT NULL,
  `claveUsu` varchar(250) NOT NULL,
  `imgUsu` varchar(50) NOT NULL DEFAULT 'profile/default.png',
  `divisa` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`idUsu`),
  KEY `FK_rol_usuario` (`idRol`),
  CONSTRAINT `FK_rol_usuario` FOREIGN KEY (`idRol`) REFERENCES `roles` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.usuarios: ~7 rows (aproximadamente)
INSERT INTO `usuarios` (`idUsu`, `idRol`, `statusUsu`, `cedUsu`, `nomUsu`, `apeUsu`, `generoUsu`, `telUsu`, `claveUsu`, `imgUsu`, `divisa`) VALUES
	(9, 2, 1, '7241160', 'Elizabeth', 'Rangel', 'Femenino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png', 0),
	(10, 4, 1, '7235846', 'Luis', 'Albarracin', 'Masculino', '04123344452', '599ea529a46eb65177538638b292aa2c85f28ba4899b7a23d4e75c9757e899557948b720235ec1ca01f07745971ee67a15449ee15c4084e39e88bb7148a2431a', 'profile/10.jpg', 0),
	(28, 2, 1, '17015265', 'Karina', 'Albarracin', 'Femenino', '04129988081', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png', 0),
	(49, 2, 1, '27863198', 'luis', 'Albarracin ', 'Masculino', '04129988083', '0f74c20f293baa5ac4ed4e24755f32e06620be14cc9fddb98d2a54519e2181ce2a0adfc2668e1cd556a7008bb4bc97a0d81c9f9994ec904f7f833f4f8bf698c8', 'profile/default.png', 0),
	(50, 2, 1, '29780428', 'Francis', 'Baloa', 'Femenino', '04129988083', 'da0157fd7b3a41553383201083eed505dcc264fe3bb0817724037403d61b84f152f83ce9f5d8443e7b7f21f1521dcb8019a03ee9bd30f94498f48855ef6a372e', 'profile/default.png', 0),
	(51, 2, 1, '29857091', 'Bryan', 'Garcia', 'Masculino', '04128660645', '5c47a3fac2de87cbd17959c73cbba7d55ce38795c5f76af2238e58110d6c7f4badd192e8d5f4cae445ada73d9b46c5f2b8eeabab5529fdde117480e0d0659c7c', 'profile/default.png', 0),
	(52, 2, 1, '27863199', 'luis', 'alba', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png', 0);

-- Volcando estructura para tabla condominio.venta
CREATE TABLE IF NOT EXISTS `venta` (
  `idVen` int(11) NOT NULL AUTO_INCREMENT,
  `idVenUsu` int(11) NOT NULL,
  `idCliUsu` int(11) NOT NULL,
  `fechaVen` datetime NOT NULL DEFAULT current_timestamp(),
  `montoVen` float NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idVen`),
  KEY `FK1_usuario_venta` (`idVenUsu`),
  KEY `FK2_usuario_cliente` (`idCliUsu`),
  CONSTRAINT `FK1_usuario_venta` FOREIGN KEY (`idVenUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_usuario_cliente` FOREIGN KEY (`idCliUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.venta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla condominio.ventaproductos
CREATE TABLE IF NOT EXISTS `ventaproductos` (
  `idPro` int(11) NOT NULL,
  `idVen` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` int(11) NOT NULL,
  KEY `FK1_producto_venta` (`idPro`),
  KEY `FK2_venta_producto` (`idVen`),
  CONSTRAINT `FK1_producto_venta` FOREIGN KEY (`idPro`) REFERENCES `producto` (`idPro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_venta_producto` FOREIGN KEY (`idVen`) REFERENCES `venta` (`idVen`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.ventaproductos: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
