-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-01-2019 a las 15:39:38
-- Versión del servidor: 5.7.21
-- Versión de PHP: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `alquiler1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono`
--

DROP TABLE IF EXISTS `abono`;
CREATE TABLE IF NOT EXISTS `abono` (
  `id_abono` int(11) NOT NULL AUTO_INCREMENT,
  `id_factura` int(255) DEFAULT NULL,
  `id_compromiso` int(255) DEFAULT NULL,
  `id_cliente` int(255) DEFAULT NULL,
  `valor` bigint(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `saldo` bigint(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_abono`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abonocaja`
--

DROP TABLE IF EXISTS `abonocaja`;
CREATE TABLE IF NOT EXISTS `abonocaja` (
  `id_abonocaja` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `valor` bigint(255) DEFAULT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_abonocaja`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `base`
--

DROP TABLE IF EXISTS `base`;
CREATE TABLE IF NOT EXISTS `base` (
  `id_base` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `valor` bigint(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_base`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compromiso`
--

DROP TABLE IF EXISTS `compromiso`;
CREATE TABLE IF NOT EXISTS `compromiso` (
  `id_compromiso` int(111) NOT NULL AUTO_INCREMENT,
  `fecha_compromiso` date NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `buen_estado` varchar(100) DEFAULT NULL,
  `condiciones_entrega` text,
  `ajustes` date DEFAULT NULL,
  `id_garantia` int(111) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `cedula` bigint(111) DEFAULT NULL,
  `ajustado` int(11) DEFAULT '0',
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_compromiso`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compromiso`
--

INSERT INTO `compromiso` (`id_compromiso`, `fecha_compromiso`, `fecha_devolucion`, `estado`, `buen_estado`, `condiciones_entrega`, `ajustes`, `id_garantia`, `id_factura`, `cedula`, `ajustado`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, '2018-10-27', '2018-10-27', 'Devuelto', 'si', NULL, NULL, NULL, 1, 1234, 0, '2018-11-27', '2018-11-27', NULL),
(2, '2018-12-01', '2018-12-01', 'Anulado', NULL, NULL, NULL, NULL, 2, 1234, 0, '2018-11-27', '2018-11-27', NULL),
(3, '2018-11-27', '2018-12-01', 'Devuelto', 'no', 'Mangas dañadas', NULL, NULL, 3, 1234, 0, '2018-11-27', '2018-11-27', NULL),
(4, '2018-11-27', '2018-11-28', 'Creado', NULL, NULL, NULL, NULL, 5, 1234, 0, '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compromiso_producto`
--

DROP TABLE IF EXISTS `compromiso_producto`;
CREATE TABLE IF NOT EXISTS `compromiso_producto` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `id_producto` int(100) NOT NULL,
  `id_compromiso` int(100) NOT NULL,
  `ajustes` text,
  `estado` varchar(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compromiso_producto`
--

INSERT INTO `compromiso_producto` (`id`, `id_producto`, `id_compromiso`, `ajustes`, `estado`, `updated_at`, `created_at`, `deleted_at`) VALUES
(8, 1, 4, NULL, NULL, '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compromiso_producto_soporte`
--

DROP TABLE IF EXISTS `compromiso_producto_soporte`;
CREATE TABLE IF NOT EXISTS `compromiso_producto_soporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(255) DEFAULT NULL,
  `id_compromiso` int(255) DEFAULT NULL,
  `ajustes` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compromiso_producto_soporte`
--

INSERT INTO `compromiso_producto_soporte` (`id`, `id_producto`, `id_compromiso`, `ajustes`, `estado`, `updated_at`, `created_at`, `deleted_at`) VALUES
(10, 2, 3, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(9, 1, 3, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(5, 1, 1, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(6, 2, 1, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(7, 1, 2, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(8, 2, 2, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(11, 3, 3, NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(12, 1, 4, NULL, NULL, '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consecutivo_factura`
--

DROP TABLE IF EXISTS `consecutivo_factura`;
CREATE TABLE IF NOT EXISTS `consecutivo_factura` (
  `id_consecutivo` int(11) NOT NULL AUTO_INCREMENT,
  `numero` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id_consecutivo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `consecutivo_factura`
--

INSERT INTO `consecutivo_factura` (`id_consecutivo`, `numero`, `created_at`, `updated_at`) VALUES
(1, 119, NULL, '2018-11-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custodia`
--

DROP TABLE IF EXISTS `custodia`;
CREATE TABLE IF NOT EXISTS `custodia` (
  `id_custodia` int(10) NOT NULL AUTO_INCREMENT,
  `valor` bigint(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_custodia`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `custodia`
--

INSERT INTO `custodia` (`id_custodia`, `valor`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 12000, NULL, '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custodia_detalle`
--

DROP TABLE IF EXISTS `custodia_detalle`;
CREATE TABLE IF NOT EXISTS `custodia_detalle` (
  `id_custodia_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `valor` bigint(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_custodia_detalle`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `custodia_detalle`
--

INSERT INTO `custodia_detalle` (`id_custodia_detalle`, `valor`, `fecha`, `tipo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 12000, '2018-11-27', 'Ingreso', '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

DROP TABLE IF EXISTS `entrada`;
CREATE TABLE IF NOT EXISTS `entrada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) DEFAULT NULL,
  `valor` bigint(20) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id`, `concepto`, `valor`, `tipo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pago por daños de compromiso con factura No 116', 5996, 'Daños', '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

DROP TABLE IF EXISTS `factura`;
CREATE TABLE IF NOT EXISTS `factura` (
  `id_factura` int(100) NOT NULL AUTO_INCREMENT,
  `numero_factura` varchar(9000) DEFAULT NULL,
  `cedula` bigint(20) DEFAULT NULL,
  `valor` bigint(100) DEFAULT NULL,
  `abono` bigint(20) DEFAULT NULL,
  `saldo` bigint(100) DEFAULT NULL,
  `saldo_abonos` bigint(255) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` varchar(100) DEFAULT NULL,
  `metodo_pago_saldo` varchar(200) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_factura`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `numero_factura`, `cedula`, `valor`, `abono`, `saldo`, `saldo_abonos`, `estado`, `fecha`, `fecha_pago`, `metodo_pago`, `metodo_pago_saldo`, `tipo`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, '114', 1234, 180000, 70000, 110000, NULL, 'Pagada', NULL, '2018-11-27', 'Efectivo', 'Efectivo', NULL, '2018-11-27', '2018-11-27', NULL),
(2, '115', 1234, 180000, 10000, 170000, NULL, 'Anulada', NULL, NULL, 'Efectivo', NULL, NULL, '2018-11-27', '2018-11-27', NULL),
(3, '116', 1234, 259000, 7007, 251993, NULL, 'Pagada', NULL, '2018-11-27', 'Efectivo', 'Efectivo', NULL, '2018-11-27', '2018-11-27', NULL),
(4, '117', 1234, 80000, NULL, NULL, NULL, NULL, NULL, '2018-11-27', 'Efectivo', NULL, 'venta', '2018-11-27', '2018-11-27', NULL),
(5, '118', 1234, 100000, 60000, 40000, NULL, 'Pendiente', NULL, NULL, 'Efectivo', NULL, NULL, '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantia`
--

DROP TABLE IF EXISTS `garantia`;
CREATE TABLE IF NOT EXISTS `garantia` (
  `id_garantia` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_garantia` varchar(100) DEFAULT NULL,
  `tipo_garantia2` varchar(255) DEFAULT NULL,
  `valor` bigint(20) DEFAULT '0',
  `fecha_pago` date DEFAULT NULL,
  `estado` int(10) DEFAULT '1',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_garantia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedad`
--

DROP TABLE IF EXISTS `novedad`;
CREATE TABLE IF NOT EXISTS `novedad` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `descripcion` longtext,
  `fecha` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_producto`
--

DROP TABLE IF EXISTS `prestamo_producto`;
CREATE TABLE IF NOT EXISTS `prestamo_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(255) DEFAULT NULL,
  `id_sede` int(255) DEFAULT NULL,
  `valor` bigint(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(255) DEFAULT 'Prestado',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `prestamo_producto`
--

INSERT INTO `prestamo_producto` (`id`, `id_producto`, `id_sede`, `valor`, `fecha`, `estado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 3, 40000, '2018-10-27', 'Devuelto', '2018-11-27', '2018-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `talla` varchar(4) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `valor` bigint(20) DEFAULT NULL,
  `valor_venta` bigint(255) DEFAULT NULL,
  `linea` varchar(100) DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `id_proveedor` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `talla`, `color`, `referencia`, `estado`, `fecha_entrega`, `fecha_devolucion`, `valor`, `valor_venta`, `linea`, `foto`, `id_proveedor`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, 'Producto 1', 'M', 'Azul', 'p1', 'Comprometido', NULL, NULL, 100000, 60000, 'Sin linea', NULL, 2, '2018-11-27 13:42:53', '2018-11-25 22:20:31', NULL),
(2, 'Producto 2', 'M', 'Azul', 'P2', 'Disponible', NULL, NULL, 80000, 60000, 'Sin linea', NULL, 2, '2018-11-27 13:40:54', '2018-11-26 13:44:02', NULL),
(3, 'Producto 3', 'M', 'Azul', 'p3', 'Vendido', NULL, NULL, 79000, 80000, 'Sin linea', NULL, 2, '2018-11-27 13:37:23', '2018-11-26 21:28:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(100) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `nit` varchar(255) DEFAULT NULL,
  `cuenta` bigint(20) DEFAULT NULL,
  `banco` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `nit`, `cuenta`, `banco`, `ciudad`, `updated_at`, `created_at`, `deleted_at`) VALUES
(2, 'Alkosto', '12312', 3453453, 'BBVA', 'Popayan', '2018-08-18 03:33:52', '2018-08-18 03:33:52', NULL),
(3, 'Ninguno', NULL, NULL, NULL, NULL, '2018-08-18 05:45:58', '2018-08-18 05:45:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

DROP TABLE IF EXISTS `salida`;
CREATE TABLE IF NOT EXISTS `salida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) NOT NULL,
  `valor` bigint(20) NOT NULL,
  `nombre_persona` varchar(500) DEFAULT NULL,
  `identificacion` int(200) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

DROP TABLE IF EXISTS `sede`;
CREATE TABLE IF NOT EXISTS `sede` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sede`
--

INSERT INTO `sede` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sede principal popayan', '2018-11-07', '2018-11-08', '2018-11-08'),
(2, 'Sede editada', '2018-11-08', '2018-11-09', '2018-11-09'),
(3, 'sede2', '2018-11-08', '2018-11-08', NULL),
(4, 'Nueva sede 4', '2018-11-08', '2018-11-10', NULL),
(5, 'sede edison', '2018-11-09', '2018-11-09', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cedula` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `celular` bigint(20) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `telefono_emergencia` bigint(20) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `actividad` varchar(500) DEFAULT NULL,
  `referencia_nombre` varchar(255) DEFAULT NULL,
  `referencia_celular` bigint(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `rol` varchar(100) DEFAULT NULL,
  `rol2` varchar(255) DEFAULT NULL,
  `descuento` int(255) DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`cedula`,`email`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `cedula`, `email`, `direccion`, `sexo`, `celular`, `telefono`, `telefono_emergencia`, `correo`, `actividad`, `referencia_nombre`, `referencia_celular`, `password`, `pass`, `remember_token`, `tipo`, `rol`, `rol2`, `descuento`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, 'diego gallardo', NULL, 'diego@mail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$thvfP/o661B3b7IIKzrZ2O.A3L7.LFaUsWNOV1pRr7lK6ZD7xLhpi', NULL, 'NV3rfOgF91TMNFrs9y4jHIkbHCEcfOJflgub4dsW8QW0KXVJJiMeDgu64SFt', '', 'administrador', NULL, 0, NULL, NULL, NULL),
(25, 'Pepito Perez', 1234, 'pepo@mail.com', 'Barrio Centenario', NULL, 34342234234, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 34534534, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-13 14:13:54', '2018-09-04 21:45:02', NULL),
(26, 'Juan vendedor', 34234234, 'vendedor@mail.com', 'Barrio Centenario', 'M', NULL, NULL, 3203259689, NULL, NULL, NULL, NULL, '$2y$10$xuEI5oicBpUmT6ey2am01eDOpF2ZxaBJAjqm3Gf75/b4l4Dk41/L.', 'vendedor123', 'tBHIAwD1h2qkPO9hxfZJUxaOZVrN5R3rhXlZb60R9XzdHFn2UmnzkIlTgvCM', 'u', 'vendedor', NULL, 0, '2018-09-13 14:31:54', '2018-09-13 14:31:54', NULL),
(29, 'Edgar Diaz', 10307016, 'edgar@mail.com', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$a4knxQ0haS/4k70YqIehZeYxQ3mJ.mruzd.arPcrto3sxhIyyLOb6', 'administrador', '7tL6NZ2jKjtyRva2lFuWxWu3DxpmIhW1m6qP5FgUq80mg7EoZqEqJXyVzsPr', 'u', 'administrador', NULL, 0, '2018-11-06 23:56:22', '2018-11-06 23:56:22', NULL),
(28, 'Diego Hernán Gallardo López', 23456, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 657897, 3203259689, NULL, NULL, NULL, 'Pepito, Andrea, Maicol', 999999999, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-06 22:41:59', '2018-11-06 22:41:59', NULL),
(30, 'Administrador SuperUsuario', 999999999, 'superadmin@gmail.com', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$Y021dmjOBaaDWJ2OWJPn1uTtR3DjTv1ZS978VFmBI/CkrVUBTLXo2', 'superadmin', 'KMuJHkThljAjC66m3kj8Em1az5doOpYfVI2wfKWFtRHlxkqjbT9QkBTSQkzg', 'u', 'administrador', 'superusuario', 0, '2018-11-07 13:27:06', '2018-11-07 13:27:06', NULL),
(31, 'Monica Meneses', 42235454, NULL, 'pajonL', NULL, 33453, 453435, NULL, NULL, 'independiente', 'alguien 1, alguien 2', 320325, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-07 13:52:38', '2018-11-07 13:52:38', NULL),
(32, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'Nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:44:33', '2018-11-27 13:44:33', NULL),
(33, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'jhg', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:50:31', '2018-11-27 13:50:31', NULL),
(34, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'jljñlj', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:51:28', '2018-11-27 13:51:28', NULL),
(35, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'jljñlj', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:52:50', '2018-11-27 13:52:50', NULL),
(36, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'jljñlj', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:52:54', '2018-11-27 13:52:54', NULL),
(37, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:53:32', '2018-11-27 13:53:32', NULL),
(38, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 13:55:32', '2018-11-27 13:55:32', NULL),
(39, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 14:00:34', '2018-11-27 14:00:34', NULL),
(40, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 14:01:18', '2018-11-27 14:01:18', NULL),
(41, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 14:02:31', '2018-11-27 14:02:31', NULL),
(42, 'Diego Hernán Gallardo López', 1234, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 14:04:23', '2018-11-27 14:04:23', NULL),
(43, 'Diego Hernán Gallardo López', 12343, 'diegogallar12@gmail.com', 'Barrio Centenario', NULL, 3434534, 3203259689, NULL, NULL, 'Ninguna', 'nadie', 0, NULL, NULL, NULL, 'c', NULL, NULL, 0, '2018-11-27 14:09:17', '2018-11-27 14:09:17', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users1`
--

DROP TABLE IF EXISTS `users1`;
CREATE TABLE IF NOT EXISTS `users1` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users1`
--

INSERT INTO `users1` (`id`, `name`, `email`, `password`, `remember_token`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Diego', 'diego@mail.com', '$2y$10$thvfP/o661B3b7IIKzrZ2O.A3L7.LFaUsWNOV1pRr7lK6ZD7xLhpi', 'sHDliHttsS1YIkbgCZKi60AcYNglzsDVpI41kK89Xsz67fIPhS0NTY3qoGeu', 1, '2018-08-15 06:03:03', '2018-08-15 06:03:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

DROP TABLE IF EXISTS `venta_producto`;
CREATE TABLE IF NOT EXISTS `venta_producto` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_factura` int(255) DEFAULT NULL,
  `id_producto` int(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id`, `id_factura`, `id_producto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, 4, 3, '2018-11-27', '2018-11-27', NULL),
(11, 11, 19, '2018-11-14', '2018-11-14', NULL),
(10, 10, 20, '2018-11-14', '2018-11-14', NULL),
(9, 9, 20, '2018-11-14', '2018-11-14', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
