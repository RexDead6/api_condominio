-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.27-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.3.0.6589
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

-- Volcando estructura para tabla condominio.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `idAnu` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `descAnu` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL DEFAULT '',
  `fechaAnu` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idAnu`),
  KEY `FK_anuncios_usuarios` (`idUsu`),
  CONSTRAINT `FK_anuncios_usuarios` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.anuncios: ~7 rows (aproximadamente)
INSERT INTO `anuncios` (`idAnu`, `idUsu`, `descAnu`, `image`, `fechaAnu`) VALUES
	(36, 10, 'Morrocoy', 'anuncios/36.jpeg', '2023-04-20 02:14:29'),
	(37, 10, 'Prueba con imagen recién tomada', 'anuncios/37.jpg', '2023-04-20 02:15:12'),
	(38, 10, 'hola mundo', '', '2023-04-20 02:15:23'),
	(39, 10, 'Edgardo', 'anuncios/39.jpg', '2023-04-20 09:49:03'),
	(40, 10, 'panas', 'anuncios/40.jpg', '2023-04-20 09:49:58'),
	(41, 10, 'entrega de proyecto tercer año', 'anuncios/41.jpg', '2023-05-04 11:36:23'),
	(42, 10, 'anuncio importante', '', '2023-05-04 11:36:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.compra: ~10 rows (aproximadamente)
INSERT INTO `compra` (`idCom`, `idUsu`, `idProv`, `monto`, `porcentaje`, `fecha_compra`) VALUES
	(11, 10, 8, 500, 10, '0000-00-00 00:00:00'),
	(12, 10, 8, 13800, 10, '0000-00-00 00:00:00'),
	(13, 10, 8, 500, 10, '2023-04-16 18:03:42'),
	(14, 10, 8, 500, 10, '2023-04-16 18:04:39'),
	(15, 10, 8, 500, 10, '2023-04-16 18:04:57'),
	(16, 10, 8, 500, 10, '2023-04-16 18:04:57'),
	(17, 28, 10, 5320, 10, '2023-04-18 23:06:18'),
	(18, 10, 9, 530, 10, '2023-04-20 00:50:09'),
	(19, 34, 11, 400, 10, '2023-04-20 09:28:59'),
	(20, 38, 12, 3000, 10, '2023-05-04 11:58:48');

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

-- Volcando datos para la tabla condominio.compraproductos: ~12 rows (aproximadamente)
INSERT INTO `compraproductos` (`idPro`, `idCom`, `cantidad`, `costo`) VALUES
	(8, 11, 10, 5.5),
	(9, 11, 30, 600),
	(10, 12, 30, 60),
	(11, 12, 20, 600),
	(8, 15, 10, 5.5),
	(9, 15, 30, 600),
	(8, 16, 10, 5.5),
	(9, 16, 30, 600),
	(13, 17, 200, 26.6),
	(12, 18, 50, 10.6),
	(14, 19, 20, 20),
	(15, 20, 30, 100);

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.factura: ~18 rows (aproximadamente)
INSERT INTO `factura` (`idFac`, `idSer`, `idFam`, `montoFac`, `fechapagFac`, `status`, `meses`) VALUES
	(12, 1, 11, 40, '2023-03-29 22:30:16', 0, 2),
	(13, 12, 11, 60.6, '2023-03-29 22:30:28', 0, 1),
	(14, 11, 11, 36.5, '2023-04-02 09:22:23', 0, 1),
	(15, 11, 11, 36.5, '2023-04-02 09:23:06', 0, 1),
	(16, 1, 11, 60, '2023-04-02 09:41:27', 0, 2),
	(17, 1, 11, 60, '2023-03-02 09:44:24', 1, 2),
	(18, 12, 11, 121.2, '2023-04-02 09:57:53', 0, 2),
	(19, 12, 11, 121.2, '2023-04-02 09:58:25', 1, 2),
	(20, 11, 11, 36.5, '2023-04-02 16:42:47', 1, 1),
	(21, 1, 11, 20, '2023-04-17 12:49:52', 1, 1),
	(22, 10, 11, 20, '2023-04-19 19:16:32', 2, 1),
	(23, 7, 11, 20, '2023-04-19 19:16:39', 2, 1),
	(24, 1, 11, 20, '2023-04-20 00:31:13', 2, 1),
	(25, 1, 11, 20, '2023-04-20 00:31:43', 1, 1),
	(26, 12, 25, 121.2, '2023-04-20 09:42:41', 0, 2),
	(27, 12, 25, 121.2, '2023-04-20 09:43:29', 1, 2),
	(28, 12, 26, 181.8, '2023-05-04 11:52:20', 0, 3),
	(29, 12, 26, 181.8, '2023-05-04 11:54:35', 1, 3);

-- Volcando estructura para tabla condominio.familias
CREATE TABLE IF NOT EXISTS `familias` (
  `idFam` int(11) NOT NULL AUTO_INCREMENT,
  `descFam` varchar(50) NOT NULL,
  `hashFam` varchar(250) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  PRIMARY KEY (`idFam`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.familias: ~7 rows (aproximadamente)
INSERT INTO `familias` (`idFam`, `descFam`, `hashFam`, `direccion`) VALUES
	(11, 'Albarracin', '384c0c8ce1f7cb52d5e92f02db8047e1e88a150a', 'calle A #17'),
	(12, 'Martinez', 'ed59511246482aae5a693cb22243bfd8ea409c25', 'calle A #17'),
	(14, 'Rangel', '4a4bbfa30dc8b904fbb040f31d7bc69d8ab639d7', 'Calle A #17'),
	(20, 'Albarracín', '93ddf399ca19a9f5227ee0a375ab3746107af75a', 'calle A #17'),
	(21, 'Albarracin', 'c21ef4c2fa2bdb7c412b4a92e7c02f0484954e07', 'calle A #17'),
	(25, 'Baloa', 'a9a048cb37e6929ee70481e62e8698704d3e08d6', 'calle e'),
	(26, 'García', '9c12fda0a5bf29e50bbb62233ed294efb10c831d', 'Calle "A"');

-- Volcando estructura para tabla condominio.gruposfamiliares
CREATE TABLE IF NOT EXISTS `gruposfamiliares` (
  `idUsu` int(11) NOT NULL,
  `idFam` int(11) NOT NULL,
  KEY `FK_familia_grupos` (`idFam`),
  KEY `FK_usuarios_grupos` (`idUsu`),
  CONSTRAINT `FK_familia_grupos` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_usuarios_grupos` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.gruposfamiliares: ~8 rows (aproximadamente)
INSERT INTO `gruposfamiliares` (`idUsu`, `idFam`) VALUES
	(10, 11),
	(9, 11),
	(28, 11),
	(32, 20),
	(33, 20),
	(36, 25),
	(38, 26),
	(37, 26);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pagomovil: ~6 rows (aproximadamente)
INSERT INTO `pagomovil` (`idPmv`, `idUsu`, `idBan`, `status`, `telPmv`, `cedPmv`, `venta`) VALUES
	(1, 10, 1, 1, '04129988083', '27863198', 0),
	(2, 10, 1, 1, '04169988083', '7241160', 0),
	(3, 10, 1, 1, '04129988083', '7235846', 1),
	(4, 10, 1, 1, '04129988083', '27863198', 0),
	(5, 28, 1, 1, '04129988082', '17015265', 1),
	(6, 34, 1, 1, '04128660645', '29857091', 1);

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

-- Volcando datos para la tabla condominio.pagoservicios: ~18 rows (aproximadamente)
INSERT INTO `pagoservicios` (`idFac`, `tipoPag`, `refPag`, `montoPag`, `comprobantePag`) VALUES
	(12, 'Pago Movil', '123', 40, ''),
	(13, 'Pago Movil', '34545', 60.6, ''),
	(14, 'Pago Movil', '5322', 36.5, ''),
	(15, 'Pago Movil', '64645', 36.5, ''),
	(16, 'Pago Movil', '12384', 60, ''),
	(17, 'Pago Movil', '12345', 60, ''),
	(18, 'Pago Movil', '2590', 121.2, ''),
	(19, 'Pago Movil', '658', 121.2, ''),
	(20, 'Pago Movil', '2385', 36.5, ''),
	(21, 'Pago Movil', '42513', 20, ''),
	(22, 'Pago Movil', '54334', 20, ''),
	(23, 'Pago Movil', '54346', 20, ''),
	(24, 'Pago Movil', '2134', 20, ''),
	(25, 'Pago Movil', '544', 20, ''),
	(26, 'Pago Movil', '4269', 121.2, ''),
	(27, 'Pago Movil', '5855', 121.2, ''),
	(28, 'Pago Movil', '0102', 181.8, ''),
	(29, 'Pago Movil', '0102', 181.8, '');

-- Volcando estructura para tabla condominio.pagosventa
CREATE TABLE IF NOT EXISTS `pagosventa` (
  `idVen` int(11) NOT NULL,
  `tipoPag` varchar(50) NOT NULL DEFAULT '',
  `refPag` varchar(50) NOT NULL DEFAULT '',
  `monto` float NOT NULL DEFAULT 0,
  KEY `FK1_pagos_venta` (`idVen`),
  CONSTRAINT `FK1_pagos_venta` FOREIGN KEY (`idVen`) REFERENCES `venta` (`idVen`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.pagosventa: ~10 rows (aproximadamente)
INSERT INTO `pagosventa` (`idVen`, `tipoPag`, `refPag`, `monto`) VALUES
	(7, 'Pago Movil', '123456', 60.6),
	(8, 'Pago Movil', '123456', 60.6),
	(9, 'Pago Movil', '12345', 1986.05),
	(10, 'Pago Movil', '12354', 146.3),
	(11, 'Pago Movil', '34346', 175.56),
	(12, 'Pago Movil', '34346', 175.56),
	(13, 'Pago Movil', '34346', 175.56),
	(29, 'Pago Movil', '666', 146.3),
	(30, 'Pago Movil', '246484', 66),
	(31, 'Pago Movil', '5465464', 726);

-- Volcando estructura para tabla condominio.producto
CREATE TABLE IF NOT EXISTS `producto` (
  `idPro` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `nomPro` varchar(50) NOT NULL,
  `costoPro` float NOT NULL DEFAULT 0,
  `existPro` int(11) NOT NULL DEFAULT 0,
  `imgPro` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPro`),
  KEY `FK_producto_usuario` (`idUsu`),
  CONSTRAINT `FK_producto_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.producto: ~8 rows (aproximadamente)
INSERT INTO `producto` (`idPro`, `idUsu`, `nomPro`, `costoPro`, `existPro`, `imgPro`, `status`) VALUES
	(8, 10, 'Carne', 6.05, 69, 'productos/8.png', 1),
	(9, 10, 'productoA', 660, 207, 'productos/9.jpeg', 1),
	(10, 10, 'prueba', 66, 29, 'productos/10.jpg', 1),
	(11, 10, 'PC gamer', 660, 19, 'productos/11.jpg', 1),
	(12, 10, 'lentes con Anti reflejo ', 11.66, 50, 'productos/12.jpg', 1),
	(13, 28, 'Energy Drink Carabao', 29.26, 162, 'productos/13.jpg', 1),
	(14, 34, 'Lentes de Sol', 22, 17, 'productos/14.jpg', 1),
	(15, 38, 'Teléfono ', 110, 30, 'productos/15.jpg', 1);

-- Volcando estructura para tabla condominio.proveedor
CREATE TABLE IF NOT EXISTS `proveedor` (
  `idProv` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `RIF` varchar(50) NOT NULL DEFAULT '',
  `nomProv` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idProv`),
  KEY `FK1proveedor_usuario` (`idUsu`),
  CONSTRAINT `FK1proveedor_usuario` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.proveedor: ~5 rows (aproximadamente)
INSERT INTO `proveedor` (`idProv`, `idUsu`, `RIF`, `nomProv`) VALUES
	(8, 10, '62165412311114', 'Inversiones2 C.A'),
	(9, 10, '27863198', 'Luis Albarracin '),
	(10, 28, '7546890', 'Aaron Romero'),
	(11, 34, '29857091', 'Bryan García '),
	(12, 38, '27861091', 'Agua');

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

-- Volcando datos para la tabla condominio.pushmessages: ~1 rows (aproximadamente)
INSERT INTO `pushmessages` (`idNot`, `tituloNot`, `descNot`, `imgNot`, `statusNot`, `allNot`, `idUsu`, `tipoNot`) VALUES
	(3, 'Test', 'Esta es una prueba', '', 1, 1, NULL, 'com.rex.condominio.activities.MainActivity');

-- Volcando estructura para tabla condominio.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nomRol` varchar(50) NOT NULL,
  `nivelRol` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`idRol`, `nomRol`, `nivelRol`) VALUES
	(1, 'Administrador', 1),
	(2, 'Inquilino', 3),
	(3, 'Jefe Familiar', 2);

-- Volcando estructura para tabla condominio.servicios
CREATE TABLE IF NOT EXISTS `servicios` (
  `idSer` int(11) NOT NULL AUTO_INCREMENT,
  `idPmv` int(11) NOT NULL,
  `descSer` varchar(50) NOT NULL,
  `isMensualSer` int(11) NOT NULL DEFAULT 0,
  `montoSer` float NOT NULL,
  `statusSer` int(11) NOT NULL DEFAULT 1,
  `fechaInicioServicio` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idSer`),
  KEY `FK_servicios_pagomovil` (`idPmv`),
  CONSTRAINT `FK_servicios_pagomovil` FOREIGN KEY (`idPmv`) REFERENCES `pagomovil` (`idPmv`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.servicios: ~5 rows (aproximadamente)
INSERT INTO `servicios` (`idSer`, `idPmv`, `descSer`, `isMensualSer`, `montoSer`, `statusSer`, `fechaInicioServicio`) VALUES
	(1, 1, 'Servicio de agua mensual', 1, 20, 1, '2023-02-25'),
	(7, 1, 'Caja clap febrero 2023', 0, 20, 1, '2023-02-25'),
	(10, 1, 'Caja clap marzo 2023', 0, 20, 1, '2023-03-29'),
	(11, 3, 'pago doctores', 0, 36.5, 1, '2023-03-29'),
	(12, 1, 'Luz', 1, 60.6, 1, '2023-03-29');

-- Volcando estructura para tabla condominio.token_access
CREATE TABLE IF NOT EXISTS `token_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsu` int(11) NOT NULL,
  `token` varchar(250) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK1_token_user` (`idUsu`),
  CONSTRAINT `FK1_token_user` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.token_access: ~11 rows (aproximadamente)
INSERT INTO `token_access` (`id`, `idUsu`, `token`, `fecha_registro`) VALUES
	(1, 10, '10|11|1|30718f4396d0027fff532702dfb81d8c03ae9a565eff353b3cb2c1d5f597c65b675e1140d7109d7d2facde1548170bcd3e40', '2023-04-15 17:13:07'),
	(16, 34, '34|00|2|9336cbf28d77568e835376ae78fb2681f39f6a01f360cc3d4d32cec69f0f15f07ee7bfc4392d7a8c2356b25eff237064d870', '2023-04-20 09:03:11'),
	(17, 34, '34|22|3|c2d7fb0d17e820c9cbb3fbabbd2e36563df26ea60000633ab56aae6d15a06583e7c77150ce95395e63628cf8b53c62cfdb33', '2023-04-20 09:04:02'),
	(19, 10, '10|11|1|aa675733c83ff5e0bb73aba6ec8124a9960f213378f3f09f288bb3b02680c308c9dab73741c925059aace159b75fa65cb04d', '2023-04-20 09:10:56'),
	(28, 38, '38|00|3|7784d4170e28ae69d161a821a25fd2133c3c74235ed4793251d3abbb2f0b634dd3114d72fc5d346dc8136c76e8631bc82e9f', '2023-05-04 11:38:43'),
	(36, 37, '37|26|3|a7d48ca26f6b674b4e5030c05e6be31d694731a3ca2176e18ff4e69f15ef14b2a5eb85f6046b87ff318d525b6552902fbc11', '2023-05-04 11:59:53'),
	(37, 10, '10|11|1|ff3cfc98847b5ee6505ffbd5c738d5812d477471052bc80a6f2617902c0a54cea2ad9041eba7f33fd6c835b8d1665a143521', '2023-05-04 12:02:53'),
	(38, 41, '41|00||cda53dd064cb6eff13b7ab7e828e08130fd51c933670b41683bded3ffbb8937d3a02765bd38f25c20484cece6677ea96642e', '2023-05-27 16:29:59'),
	(39, 42, '42|00||bcf45cc2817751b1ad95180299ddd173088eac2209d236286dc3e4252ec36df4bb43faa6da43b91f198fa78b3fc4de7e7770', '2023-05-27 16:31:20'),
	(42, 10, '10|11|1|727dc9eb5267bdc27b4f7e53d60df19faca3b156051755329a5fe8f2b4e0e8bc26d939e666973fdb0f90c3a8735cb92be290', '2023-05-27 17:41:14'),
	(43, 10, '10|11|1|a593174f520e622fa3c60ab101615805d7384033297be0f268fa2f59a65b4affbb0021daedeb687869a99146d5bad4adb3c3', '2023-05-27 18:26:12');

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
  PRIMARY KEY (`idUsu`),
  KEY `FK_rol_usuario` (`idRol`),
  CONSTRAINT `FK_rol_usuario` FOREIGN KEY (`idRol`) REFERENCES `roles` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla condominio.usuarios: ~14 rows (aproximadamente)
INSERT INTO `usuarios` (`idUsu`, `idRol`, `statusUsu`, `cedUsu`, `nomUsu`, `apeUsu`, `generoUsu`, `telUsu`, `claveUsu`, `imgUsu`) VALUES
	(9, 2, 1, '7241160', 'Elizabeth', 'Rangel', 'Femenino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(10, 1, 1, '7235846', 'Luis', 'Albarracin', 'Masculino', '04123344452', '599ea529a46eb65177538638b292aa2c85f28ba4899b7a23d4e75c9757e899557948b720235ec1ca01f07745971ee67a15449ee15c4084e39e88bb7148a2431a', 'profile/10.jpg'),
	(28, 2, 1, '17015265', 'Karina', 'Albarracin', 'Femenino', '04129988081', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(32, 2, 1, '27863198', 'luis', 'Albarracín', 'Masculino', '04129988083', '599ea529a46eb65177538638b292aa2c85f28ba4899b7a23d4e75c9757e899557948b720235ec1ca01f07745971ee67a15449ee15c4084e39e88bb7148a2431a', 'profile/default.png'),
	(33, 2, 1, '29527753', 'Francis ', 'Baloa', 'Femenino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(34, 3, 1, '8765785675', 'Bryan', 'Garcia', 'Masculino', '04128660645', '30eaf38213970e238f913da8595d74728048609cef61709080b21b769b3640ba1525727c26d37941073387947e50d8ef9b703c85abb35a7b824e7eceb972aef9', 'profile/default.png'),
	(35, 3, 1, '7786', 'Betty', 'Baloa', 'Femenino', '0416861895', '432643516c508f7bb37f729e6e7e8f695d4d377358d8a7e7e0ac8fef79f8f34cb34c3d3886286ae4e96588048514e1db7815b3b2dfa7ce442cfdb5a908bbf9f7', 'profile/default.png'),
	(36, 2, 1, '27863195', 'Enrique', 'Albarracín ', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(37, 2, 1, '27863191', 'luis', 'alba', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(38, 3, 1, '29857091', 'Bryan', 'García ', 'Masculino', '0412860645', '9f9ffb5b70212c57402c61bc562983532ffb3664793308ef68c123c9b6a2b31a3e488e368a71ec71437356d021821652325dd815cfa13ae820ed91d3542b0cae', 'profile/default.png'),
	(39, 2, 0, '27863194', 'luis', 'alba', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(40, 2, 0, '27863192', 'Luis', 'Albarracin', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(41, 2, 0, '27863197', 'Luis', 'Albarracin', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png'),
	(42, 2, 0, '27863193', 'luis', 'alba', 'Masculino', '04129988083', 'e3479b6d3dfeb60fe9e111235256738059c137dddd24b307f37afec1b7a1dd8f9c8c6c30779b1cae9970b4094af287b3e1cc03dbf1c7beb7000fc250fb11189e', 'profile/default.png');

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

-- Volcando datos para la tabla condominio.venta: ~10 rows (aproximadamente)
INSERT INTO `venta` (`idVen`, `idVenUsu`, `idCliUsu`, `fechaVen`, `montoVen`, `status`) VALUES
	(7, 28, 10, '2023-04-19 10:15:32', 200, 1),
	(8, 28, 10, '2023-04-19 10:46:09', 200, 1),
	(9, 10, 28, '2023-04-19 18:52:23', 1986.05, 3),
	(10, 28, 10, '2023-04-20 00:04:16', 146.3, 3),
	(11, 28, 10, '2023-04-20 01:28:13', 175.56, 1),
	(12, 28, 10, '2023-04-20 01:28:19', 175.56, 1),
	(13, 28, 10, '2023-04-20 01:28:50', 175.56, 1),
	(29, 28, 34, '2023-04-20 09:04:51', 146.3, 3),
	(30, 34, 10, '2023-04-20 09:30:31', 66, 3),
	(31, 10, 37, '2023-05-04 12:01:46', 726, 3);

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

-- Volcando datos para la tabla condominio.ventaproductos: ~12 rows (aproximadamente)
INSERT INTO `ventaproductos` (`idPro`, `idVen`, `cantidad`, `costo`) VALUES
	(13, 7, 5, 29),
	(13, 8, 5, 29),
	(8, 9, 1, 6),
	(9, 9, 3, 660),
	(13, 10, 5, 29),
	(13, 11, 6, 29),
	(13, 12, 6, 29),
	(13, 13, 6, 29),
	(13, 29, 5, 29),
	(14, 30, 3, 22),
	(10, 31, 1, 66),
	(11, 31, 1, 660);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
