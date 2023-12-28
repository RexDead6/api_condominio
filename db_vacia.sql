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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.bancos
CREATE TABLE IF NOT EXISTS `bancos` (
  `idBan` int(11) NOT NULL AUTO_INCREMENT,
  `nomBan` varchar(50) NOT NULL,
  `codBan` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idBan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.familiaurbanizacion
CREATE TABLE IF NOT EXISTS `familiaurbanizacion` (
  `idFam` int(11) NOT NULL,
  `idUrb` int(11) NOT NULL,
  KEY `familiaurbanizacion_FK` (`idFam`),
  KEY `familiaurbanizacion_FK_1` (`idUrb`),
  CONSTRAINT `familiaurbanizacion_FK` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`),
  CONSTRAINT `familiaurbanizacion_FK_1` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.gruposfamiliares
CREATE TABLE IF NOT EXISTS `gruposfamiliares` (
  `idUsu` int(11) NOT NULL,
  `idFam` int(11) NOT NULL,
  KEY `FK_familia_grupos` (`idFam`),
  KEY `FK_usuarios_grupos` (`idUsu`),
  CONSTRAINT `FK_familia_grupos` FOREIGN KEY (`idFam`) REFERENCES `familias` (`idFam`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_usuarios_grupos` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.pagosventa
CREATE TABLE IF NOT EXISTS `pagosventa` (
  `idVen` int(11) NOT NULL,
  `tipoPag` varchar(50) NOT NULL DEFAULT '',
  `refPag` varchar(50) NOT NULL DEFAULT '',
  `monto` float NOT NULL DEFAULT 0,
  KEY `FK1_pagos_venta` (`idVen`),
  CONSTRAINT `FK1_pagos_venta` FOREIGN KEY (`idVen`) REFERENCES `venta` (`idVen`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `nomRol` varchar(50) NOT NULL,
  `nivelRol` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.urbanizacion
CREATE TABLE IF NOT EXISTS `urbanizacion` (
  `idUrb` int(11) NOT NULL AUTO_INCREMENT,
  `nomUrb` varchar(100) NOT NULL,
  `direccion` varchar(50) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idUrb`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla condominio.usuarioadmin
CREATE TABLE IF NOT EXISTS `usuarioadmin` (
  `idUsu` int(11) NOT NULL,
  `idUrb` int(11) NOT NULL,
  KEY `FK1_usu_admin` (`idUsu`),
  KEY `FK2_urb_admin` (`idUrb`),
  CONSTRAINT `FK1_usu_admin` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`idUsu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_urb_admin` FOREIGN KEY (`idUrb`) REFERENCES `urbanizacion` (`idUrb`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
