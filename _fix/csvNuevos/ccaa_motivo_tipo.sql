-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2017 a las 09:24:37
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mejoramientosql`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccaa_motivo_tipo`
--

CREATE TABLE `ccaa_motivo_tipo` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de los tipos de Formularios',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Tipo de la Selección ',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Tipo Motivo ',
  `SERVICIO` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ccaa_motivo_tipo`
--

INSERT INTO `ccaa_motivo_tipo` (`ID`, `NOMBRE`, `ESTADO`, `SERVICIO`) VALUES
(1, 'INFORME MOTIVO CAMBIO DE CONTRASENA', 1, 2),
(2, 'INFORME MOTIVO AUSENCIA DE CANALES', 1, 4),
(3, 'ALTAS VELOCIDADES', 1, 2),
(4, 'ESCALAMIENTO CAV', 1, 4),
(5, 'VARIACION DE AUDIO ', 1, 4),
(6, 'RESET FISICO TELEVISION', 1, 4),
(7, 'CANALES SIN AUDIO ', 1, 4),
(8, 'INCUMPLIMIENTOS DE VISITA', 1, 1),
(9, 'INSATISFACCION DE VISITA', 1, 1),
(10, 'VISITA SOPORTE FALLIDO', 1, 1),
(11, 'PILOTO TELEFONIA TONO DESCONECTADO ', 1, 3),
(12, 'EQUIPOS CLARO VIDEO', 1, 4),
(13, 'ESCALAMIENTO NAGRA', 1, 4),
(14, 'SERIALES CHIP ID', 1, 4),
(15, 'CANAL NO DISPONIBLE', 1, 4),
(16, 'CANAL NO DISPONIBLE 2', 1, 4),
(17, 'INFOGRAFIA CLIENTES', 1, 1),
(18, 'ESCENARIOS SOPORTE FALLIDO', 1, 3),
(19, 'CLIENTE NO DESEA VISITA', 1, 3),
(20, 'REINICIO MODEM', 1, 2),
(21, 'AUSENCIA DE CANALES', 1, 4),
(22, 'DS', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ccaa_motivo_tipo`
--
ALTER TABLE `ccaa_motivo_tipo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_CCAA_MOTIVO_TIPO_estados1_idx` (`ESTADO`),
  ADD KEY `fk_CCAA_MOTIVO_TIPO_CCAA_SERVICIO1_idx` (`SERVICIO`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ccaa_motivo_tipo`
--
ALTER TABLE `ccaa_motivo_tipo`
  ADD CONSTRAINT `fk_CCAA_MOTIVO_TIPO_CCAA_SERVICIO1` FOREIGN KEY (`SERVICIO`) REFERENCES `ccaa_servicio` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CCAA_MOTIVO_TIPO_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
