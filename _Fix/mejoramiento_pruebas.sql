-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2017 a las 23:47:22
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mejoramiento_pruebas`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `COMUNICACION_AVISOS_EDITAR` (IN `ID_AVISO` BIGINT(20), IN `USUARIO_INPUT` VARCHAR(255), IN `REGIONAL_INPUT` VARCHAR(50), IN `FALLA_INPUT` VARCHAR(50), IN `DETALLE_INPUT` VARCHAR(50), IN `PRIORIDAD_INPUT` INT(1), OUT `RESULTADO` INT(1))  BEGIN
	DECLARE ID_USUARIO VARCHAR(255);
    DECLARE EXISTENCIA INT(1) DEFAULT 0;
    DECLARE RESPUESTA INT(1) DEFAULT 0;
    
    SELECT ID INTO ID_USUARIO FROM usuarios WHERE USUARIO_RR = USUARIO_INPUT;
    SELECT COUNT(ID) INTO EXISTENCIA FROM comunicacion_avisos WHERE ID = ID_AVISO;
    
    IF EXISTENCIA = 1 THEN 
		UPDATE comunicacion_avisos 
		SET 
			USUARIO = ID_USUARIO,
			REGIONAL = REGIONAL_INPUT,
			FALLA = FALLA_INPUT,
			DETALLE = DETALLE_INPUT, 
			PRIORIDAD = PRIORIDAD_INPUT 
		WHERE 
			ID = ID_AVISO;
		SET RESPUESTA = 1;
	END IF;
    
    SET RESULTADO = RESPUESTA;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `COMUNICACION_AVISOS_ELIMINAR` (IN `ID_AVISO` BIGINT(20), IN `USUARIO_USER` VARCHAR(255), OUT `RESULTADO` INT(1))  BEGIN
	DECLARE ID_USUARIO BIGINT(20);
    DECLARE EXISTENCIA INT(1);
    DECLARE RESPUESTA BOOLEAN DEFAULT 0;
    
    SELECT ID INTO ID_USUARIO FROM USUARIOS WHERE USUARIO_RR = USUARIO_USER;
    SELECT COUNT(ID) INTO EXISTENCIA FROM comunicacion_avisos WHERE ID = ID_AVISO;
    
    IF EXISTENCIA = 1 
		THEN
			UPDATE 
				comunicacion_avisos 
			SET 
				USUARIO = ID_USUARIO,
				ESTADO = 6 
			WHERE 
				ID = ID_AVISO;
			SET RESPUESTA = 1;
	END IF;
    
    SET RESULTADO = RESPUESTA;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `COMUNICACION_AVISOS_FINALIZAR` (IN `ID_AVISO` BIGINT(20), IN `ID_ESTADO` INT(2), IN `USUARIO_USER` VARCHAR(255), OUT `RESULTADO` INT(1))  BEGIN
	DECLARE ID_USUARIO BIGINT(20);
    DECLARE RESPUESTA BOOLEAN DEFAULT 0;
    
	SELECT ID INTO ID_USUARIO FROM USUARIOS WHERE USUARIO_RR = USUARIO_USER;
	
	IF ID_ESTADO = 4 THEN
		UPDATE 
			comunicacion_avisos 
        SET 
			USUARIO = ID_USUARIO, 
            ESTADO = ID_ESTADO 
		WHERE 
			ID = ID_AVISO;
            
        SET RESPUESTA = 1;
    END IF;
    
    SET RESULTADO = RESPUESTA;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `COMUNICACION_AVISOS_INSERTAR` (IN `FECHA_AVISO` DATETIME, IN `USUARIO_IN` VARCHAR(255), IN `ESTADO_AVISO` INT(2), IN `N_AVISO` BIGINT(20), IN `A_REGIONAL` VARCHAR(50), IN `A_FALLA` VARCHAR(50), IN `A_DETALLE` VARCHAR(50), IN `A_PRIORIDAD` INT(1), OUT `ID_AVISO` BIGINT(20))  BEGIN
	DECLARE CANTIDAD INT(10);
    DECLARE ID_USUARIO BIGINT(20);
    
    SELECT ID INTO ID_USUARIO FROM usuarios WHERE USUARIO_RR = USUARIO_IN;
    SELECT COUNT(ID) INTO CANTIDAD FROM comunicacion_avisos WHERE ESTADO = ESTADO_AVISO AND AVISO = N_AVISO;
    IF CANTIDAD > 0 THEN
		SET ID_AVISO = 0;
    ELSE
		INSERT INTO comunicacion_avisos (FECHA, USUARIO, ESTADO, AVISO, REGIONAL, FALLA, DETALLE, PRIORIDAD)
        VALUES (FECHA_AVISO, ID_USUARIO, ESTADO_AVISO, N_AVISO, A_REGIONAL, A_FALLA, A_DETALLE, A_PRIORIDAD);
        SET ID_AVISO = LAST_INSERT_ID();
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GUIONES_REGISTRO_HFC_INSERTAR` (IN `FECHA_INPUT` DATETIME, IN `USUARIO_INPUT` VARCHAR(255), IN `TIPO_INPUT` INT(2), IN `AFECTACION_INPUT` VARCHAR(25), IN `AVISO_INPUT` VARCHAR(255), IN `UBICACION_INPUT` VARCHAR(10), IN `INTERMITENCIA_INPUT` VARCHAR(2), IN `GUION_INPUT` TEXT, IN `PRIORIDAD_INPUT` INT(2), IN `AVERIA_INPUT` VARCHAR(50), IN `RAZON_INPUT` INT(2), OUT `ID_OUTPUT` BIGINT(20))  BEGIN
	DECLARE ID_USUARIO BIGINT(20);
    DECLARE ID_REGISTRO BIGINT(20);
    
    SELECT ID INTO ID_USUARIO FROM usuarios WHERE USUARIO_RR = USUARIO_INPUT;
    
    INSERT INTO GUIONES_REGISTRO (FECHA, USUARIO, TIPO, AFECTACION, AVISO, UBICACION, AVERIA, RAZON) 
    VALUES (FECHA_INPUT, ID_USUARIO, TIPO_INPUT, AFECTACION_INPUT, AVISO_INPUT, UBICACION_INPUT, AVERIA_INPUT, RAZON_INPUT);
    
    SET ID_REGISTRO = LAST_INSERT_ID();
    
    INSERT INTO GUIONES_REGISTRO_HFC (REGISTRO, INTERMITENCIA, GUION, PRIORIDAD) 
    VALUES (ID_REGISTRO, INTERMITENCIA_INPUT, GUION_INPUT, PRIORIDAD_INPUT);
    
    SET ID_OUTPUT = ID_REGISTRO;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GUIONES_REGISTRO_MATRIZ_INSERTAR` (IN `FECHA_INPUT` DATETIME, IN `USUARIO_INPUT` VARCHAR(255), IN `TIPO_INPUT` INT(2), IN `AFECTACION_INPUT` VARCHAR(25), IN `AVISO_INPUT` VARCHAR(255), IN `UBICACION_INPUT` VARCHAR(10), IN `PRIORIDAD_INPUT` INT(2), IN `MATRIZ_INPUT` BIGINT(20), IN `DETALLE_INPUT` VARCHAR(20), IN `REFERENCIA_INPUT` VARCHAR(20), IN `INTERMITENCIA_INPUT` VARCHAR(2), IN `GUION_INPUT` TEXT, IN `AVERIA_INPUT` INT(10), IN `RAZON_INPUT` INT(2), OUT `RESPUESTA` BIGINT(20))  BEGIN
	DECLARE ID_USUARIO BIGINT(20) DEFAULT 0;
    DECLARE REGISTRO_ID BIGINT(20) DEFAULT 0;
    
    SELECT ID INTO ID_USUARIO FROM usuarios WHERE USUARIO_RR = USUARIO_INPUT;
    IF ID_USUARIO > 0 THEN
		INSERT INTO GUIONES_REGISTRO (FECHA, USUARIO, TIPO, AFECTACION, AVISO, UBICACION, AVERIA, RAZON)
        VALUES (FECHA_INPUT, ID_USUARIO, TIPO_INPUT, AFECTACION_INPUT, AVISO_INPUT, UBICACION_INPUT, AVERIA_INPUT, RAZON_INPUT);
		
        SET REGISTRO_ID = LAST_INSERT_ID();
        
        INSERT INTO GUIONES_REGISTRO_MATRIZ (REGISTRO, PRIORIDAD, MATRIZ, DETALLE, REFERENCIA, INTERMITENCIA, GUION) 
        VALUES (REGISTRO_ID, PRIORIDAD_INPUT, MATRIZ_INPUT, DETALLE_INPUT, REFERENCIA_INPUT, INTERMITENCIA_INPUT, GUION_INPUT);
        
    END IF;
    
    SET RESPUESTA = REGISTRO_ID;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GUIONES_REGISTRO_PLATAFORMA_INSERTAR` (IN `FECHA_INPUT` DATETIME, IN `USUARIO_INPUT` VARCHAR(255), IN `TIPO_INPUT` INT(2), IN `AFECTACION_INPUT` VARCHAR(25), IN `AVISO_INPUT` VARCHAR(255), IN `UBICACION_INPUT` VARCHAR(10), IN `PRIORIDAD_INPUT` INT(2), IN `SINTOMA_INPUT` VARCHAR(255), IN `NOTAS_INPUT` VARCHAR(2), IN `INFORMACION_INPUT` VARCHAR(255), IN `MARCACION_INPUT` VARCHAR(10), IN `GUION_INPUT` TEXT, OUT `RESPUESTA` BIGINT(20))  BEGIN
	DECLARE ID_USUARIO BIGINT(20) DEFAULT 0;
    DECLARE REGISTRO_ID BIGINT(20) DEFAULT 0;
        
	SELECT ID INTO ID_USUARIO FROM usuarios WHERE USUARIO_RR = USUARIO_INPUT;
    IF ID_USUARIO > 0 THEN
		
		INSERT INTO GUIONES_REGISTRO (FECHA, USUARIO, TIPO, AFECTACION, AVISO, UBICACION) 
        VALUES (FECHA_INPUT, ID_USUARIO, TIPO_INPUT, AFECTACION_INPUT, AVISO_INPUT, UBICACION_INPUT);
    
		SET REGISTRO_ID = LAST_INSERT_ID();
        
        INSERT INTO GUIONES_REGISTRO_PLATAFORMA (REGISTRO, PRIORIDAD, SINTOMA, NOTAS, INFORMACION, MARCACION, GUION) 
        VALUES (REGISTRO_ID, PRIORIDAD_INPUT, SINTOMA_INPUT, NOTAS_INPUT, INFORMACION_INPUT, MARCACION_INPUT, GUION_INPUT);
        
    END IF;
    
		SET RESPUESTA = REGISTRO_ID;
        
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccaa_motivo`
--

CREATE TABLE `ccaa_motivo` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Motivos CCAA',
  `TIPO` bigint(20) NOT NULL COMMENT 'ID del Tipo del Motivo',
  `CUENTA` bigint(20) NOT NULL COMMENT 'Cuenta Reportada',
  `RAZON` bigint(20) NOT NULL COMMENT 'Razón del Motivo por Tipo',
  `FECHA` datetime NOT NULL COMMENT 'Fecha del Reporte ',
  `REFERENCIA` varchar(100) DEFAULT NULL COMMENT 'Referencia del Motivo Tipo',
  `INFORMACION` mediumtext COMMENT 'Informacion del Motivo Tipo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccaa_motivo_razon`
--

CREATE TABLE `ccaa_motivo_razon` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de las Razones',
  `RAZON` varchar(255) NOT NULL COMMENT 'Razón',
  `ESTADO` int(2) NOT NULL COMMENT 'ID del Estado',
  `TIPO` bigint(20) NOT NULL COMMENT 'ID de tabla Tipo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ccaa_motivo_razon`
--

INSERT INTO `ccaa_motivo_razon` (`ID`, `RAZON`, `ESTADO`, `TIPO`) VALUES
(1, 'CONTRASENA DE DIFICIL RECORDACION', 1, 1),
(2, 'CONTRASENA WIFI COMPLEJA ', 1, 1),
(3, 'CLIENTE REPORTA SE ESTAN COLGANDO A SU RED WIFI ', 1, 1),
(4, 'NO LE GUSTA EL NOMBRE ACTUAL DE SU RED Y/O CLAVE', 1, 1),
(5, 'NO ES UNA CLAVE SEGURA', 1, 1),
(6, 'PANTALLA EN NEGRO', 1, 2),
(7, 'OMITE O SALTA LA FRECUENCIA', 1, 2),
(8, 'PLACA DE SIN SENAL ', 1, 2),
(9, 'SI- EL CLIENTE ACEPTO', 1, 3),
(10, 'NO- EL CLIENTE NO DESEA EL CAMBIO', 1, 3),
(11, 'NO APLICA- NO ES CLIENTE CON PLAN >= 10MEGAS O YA TIENE DOCSIS 3.0', 1, 3),
(12, 'SIN SENAL', 1, 4),
(13, 'AUSENCIA DE CANALES', 1, 4),
(14, 'VOLUMEN VARIA ENTRE PROGRAMA Y COMERCIALES ', 1, 5),
(15, 'VOLUMEN VARIA ENTRE CAMBIO DE CANALES ', 1, 5),
(16, 'VOLUMEN VARIA EN UN MISMO PROGRAMA', 1, 5),
(17, 'SIN AUDIO EN ALGUN TRACK', 1, 7),
(18, 'SIN AUDIO EN TODOS LOS TRACK', 1, 7),
(19, 'SIN AUDIO EN EL IDIOMA ALTERNATIVO ', 1, 7),
(20, 'SIN SENAL', 1, 6),
(21, 'ERROR NULL O 8888', 1, 6),
(22, 'ERRORES DE TARJETA', 1, 6),
(23, 'SOPORTE GRABACIONES', 1, 6),
(24, 'SIN AUDIO O SUBTITULOS', 1, 6),
(25, 'DECODIFICADOR EN BUCLE ', 1, 6),
(26, 'BLOQUEO DE DECODIFICADOR', 1, 6),
(27, 'PLACA CANAL NO DISPONIBLE', 1, 6),
(28, 'SIN INFORMACION EN LA GUÍA ', 1, 6),
(29, 'SIN SENAL ALGUNOS CANALES', 1, 6),
(30, 'SENAL INTERMITENTE O PIXELADA', 1, 6),
(31, 'ARREGLOS', 1, 8),
(32, 'TRASLADOS', 1, 8),
(33, 'INSTALACIONES', 1, 8),
(34, 'POSTVENTA', 1, 8),
(35, 'RECONEXION', 1, 8),
(36, 'DESCONEXION ', 1, 8),
(37, 'CONTROL REMOTO ', 1, 8),
(38, 'ARREGLOS', 1, 9),
(39, 'TRASLADOS', 1, 9),
(40, 'INSTALACIONES', 1, 9),
(41, 'POSTVENTA', 1, 9),
(42, 'RECONEXION ', 1, 9),
(43, 'DESCONEXION ', 1, 9),
(44, 'MEDICION VISITAS CLIENTE NO ESTA EN CASA', 1, 10),
(45, 'SI REINCIDENCIA', 1, 11),
(46, 'NO REINCIDENCIA ', 1, 11),
(47, 'CLIENTE REPORTA DISPOSITIVO ', 1, 12),
(48, 'FALLA EN TELEVISION', 1, 13),
(49, 'FALLA EN CLARO VIDEO ', 1, 13),
(50, 'FALLA EN TELEVISION Y CLARO VIDEO ', 1, 13),
(51, 'OTRAS FALLAS', 1, 13),
(52, 'SERIALES NO COINCIDEN', 1, 14),
(53, 'MENOS DE 15 DIAS', 1, 15),
(54, 'ENTRE 15 DIAS Y 1 MES', 1, 15),
(55, 'ENTRE 1 MES Y 2 MESES', 1, 15),
(56, 'ENTRE 2 MESES Y 3 MESES', 1, 15),
(57, 'MAS DE 3 MESES', 1, 15),
(58, 'EN TODOS LOS CANALES MENOS EL 107', 1, 16),
(59, 'ALGUNOS CANALES', 1, 16),
(60, 'CLAVE WI-FI - PORTAL MI-CLARO', 1, 17),
(61, 'INTERNET REINICIO DE CM', 1, 17),
(62, 'BLOQUEO DE PC O DISPOSITIVOS EXTERNOS CLIENTE - CON SOLUCION', 1, 17),
(63, 'INFORMACION DE FUNCIONALIDADES DE TELEFONIA', 1, 17),
(64, 'BLOQUEO DE IDENTIFICADOR DE LLAMADAS', 1, 17),
(65, 'PERSONALIZACION DEL CANAL WI-FI  ', 1, 17),
(66, 'CONFIGURACION CONTROL REMOTO', 1, 17),
(67, 'ANTES DE SALIR DEL PREDIO SE DIO CUENTA DE LA FALLA DEL SERVICIO.', 1, 18),
(68, 'UN TERCERO SE COMUNICO CON USUARIO PARA INFORMAR LA FALLA.', 1, 18),
(69, 'PARA TELEFONIA (EL CLIENTE MARCO A LA LINEA DETECTANDO FALLA).', 1, 18),
(70, 'CLIENTE MOLESTO POR REINCIDENCIA NO DESEA HACER SOPORTE.', 1, 18),
(71, 'CLIENTE NO SABE MANEJAR EQUIPOS.', 1, 18),
(72, 'CLIENTE NO TIENE DISPONIBLES LOS EQUIPOS PARA PRUEBAS.', 1, 18),
(73, 'CLIENTE TIENE FALLAS EN SUS EQUIPOS NO PUEDE REALIZAR PRUEBAS.', 1, 18),
(74, 'CLIENTE SOLO DESEA QUE LE PROGRAMEN VISITA.', 1, 18),
(75, 'CLIENTE MANIPULO CONEXIONES SIN INFORMACION SOLUCIONANDO FALLA.', 1, 18),
(76, 'INFORMA FALLAS REITERATIVAS PERO AL VALIDAR EL SERVICIO ESTA OK.', 1, 18),
(77, 'POR DISPONIBILIDAD DE TIEMPO', 1, 19),
(78, 'NO LA CREE NECESARIA', 1, 19),
(79, 'CUPOS DE VISITA LEJANOS.', 1, 19),
(80, 'YOUTUBE – PELICULAS ONLINE', 1, 20),
(81, 'NAVEGACION', 1, 20),
(82, 'JUEGOS ONLINE', 1, 20),
(83, 'DESCARGAS', 1, 20),
(84, 'VIDEO CONFERENCIAS', 1, 20),
(85, 'TODAS LAS ANTERIORES', 1, 20),
(86, 'CANALES CON PLACA RF', 1, 21),
(87, 'SALTA U OMITE CANALES', 1, 21),
(88, 'CANALES CON PANTALLA EN NEGRO', 1, 21);

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
(21, 'AUSENCIA DE CANALES', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccaa_servicio`
--

CREATE TABLE `ccaa_servicio` (
  `ID` int(2) NOT NULL COMMENT 'ID Servicios',
  `NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre de Servicio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ccaa_servicio`
--

INSERT INTO `ccaa_servicio` (`ID`, `NOMBRE`) VALUES
(1, 'TRIPLEPLAY'),
(2, 'INTERNET'),
(3, 'TELEFONIA'),
(4, 'TELEVISION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_mensaje`
--

CREATE TABLE `chat_mensaje` (
  `ID` bigint(20) NOT NULL,
  `FECHA` datetime NOT NULL,
  `ID_USUARIO` bigint(20) NOT NULL COMMENT 'ID de Usuario',
  `IP` varchar(255) NOT NULL,
  `MENSAJE` longtext NOT NULL,
  `TIPO` int(2) NOT NULL COMMENT 'ID de Tipo de Chat',
  `ID_SALA` int(3) DEFAULT NULL COMMENT 'ID de la Sala',
  `DE` bigint(20) DEFAULT NULL COMMENT 'ID de Usuario de Fuente',
  `PARA` bigint(20) DEFAULT NULL COMMENT 'ID de Usuario de Destino'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_perfil`
--

CREATE TABLE `chat_perfil` (
  `ID` int(3) NOT NULL COMMENT 'ID de  Perfil de Chat',
  `PERMISO_NOMBRE` bigint(20) NOT NULL COMMENT 'ID del permiso nombre',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Perfil'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `chat_perfil`
--

INSERT INTO `chat_perfil` (`ID`, `PERMISO_NOMBRE`, `ESTADO`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 5, 1),
(5, 6, 1),
(6, 1, 1),
(7, 2, 1),
(8, 6, 1),
(9, 1, 1),
(10, 2, 1),
(11, 3, 1),
(12, 5, 1),
(13, 6, 1),
(14, 7, 1),
(15, 1, 1),
(16, 2, 1),
(17, 3, 1),
(18, 5, 1),
(19, 6, 1),
(20, 1, 1),
(21, 2, 1),
(22, 3, 1),
(23, 4, 1),
(24, 5, 1),
(25, 6, 1),
(26, 8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_salas`
--

CREATE TABLE `chat_salas` (
  `ID` int(3) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre de la Sala',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la Sala'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `chat_salas`
--

INSERT INTO `chat_salas` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'ANALISTAS', 1),
(2, 'MEJORAMIENTO', 1),
(3, 'INVITADOS', 1),
(4, 'APOYO_PISO ', 1),
(5, 'CONTROL_CALIDAD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_salas_has_chat_perfil`
--

CREATE TABLE `chat_salas_has_chat_perfil` (
  `chat_perfil_ID` int(3) NOT NULL COMMENT 'ID del Perfil del Chat',
  `chat_salas_ID` int(3) NOT NULL COMMENT 'ID de la Sala del Chat'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `chat_salas_has_chat_perfil`
--

INSERT INTO `chat_salas_has_chat_perfil` (`chat_perfil_ID`, `chat_salas_ID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 4),
(16, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(26, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_tipo`
--

CREATE TABLE `chat_tipo` (
  `ID` int(2) NOT NULL COMMENT 'ID del Tipo de Chat',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'Nombre del Tipo de Chat'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `chat_tipo`
--

INSERT INTO `chat_tipo` (`ID`, `NOMBRE`) VALUES
(1, 'PUBLICO'),
(2, 'PRIVADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicacion_avisos`
--

CREATE TABLE `comunicacion_avisos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de Registro',
  `FECHA` datetime NOT NULL COMMENT 'Fecha',
  `USUARIO` bigint(20) NOT NULL COMMENT 'ID de Usuario',
  `ESTADO` int(2) NOT NULL COMMENT 'ID de Estado',
  `AVISO` bigint(20) NOT NULL COMMENT 'Numero del Aviso',
  `REGIONAL` varchar(50) NOT NULL COMMENT 'Regional',
  `FALLA` varchar(50) NOT NULL COMMENT 'Falla del Aviso',
  `DETALLE` varchar(50) NOT NULL COMMENT 'Detalle del Aviso',
  `PRIORIDAD` int(1) NOT NULL COMMENT 'Prioridad del Aviso'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Disparadores `comunicacion_avisos`
--
DELIMITER $$
CREATE TRIGGER `TRIGGER_COMUNICACION_AVISOS_EDITAR` AFTER UPDATE ON `comunicacion_avisos` FOR EACH ROW BEGIN
	DECLARE INFORMACION LONGTEXT;
    DECLARE NOMBRE_USER VARCHAR(100);
    DECLARE USUARIO_USER VARCHAR(10);
    DECLARE A_ESTADO VARCHAR(50);
    
    SELECT NOMBRE, USUARIO_RR INTO NOMBRE_USER, USUARIO_USER FROM usuarios WHERE ID = NEW.USUARIO;
    SELECT ESTADO INTO A_ESTADO FROM estados WHERE ID = NEW.ESTADO;
    
    SET INFORMACION = CONCAT(
		'[FECHA: ', OLD.FECHA, 
        '][USUARIO: ', USUARIO_USER, 
        '][NOMBRE: ', NOMBRE_USER, 
        '][ESTADO: ', A_ESTADO, 
        '][AVISO: ', NEW.AVISO, 
        '][REGIONAL: ', NEW.REGIONAL, 
        '][FALLA: ', NEW.FALLA, 
        '][DETALLE: ', NEW.DETALLE, 
        '][PRIORIDAD: ', NEW.PRIORIDAD, ']');
    
    IF NEW.ESTADO = 6 THEN
		INSERT INTO COMUNICACION_AVISOS_LOG (FECHA, USUARIO, TIPO, AVISO, DESCRIPCION) 
		VALUES (NOW(), NEW.USUARIO, 'ELIMINADO', OLD.AVISO, INFORMACION);
	ELSEIF NEW.ESTADO = 4 THEN 
		INSERT INTO COMUNICACION_AVISOS_LOG (FECHA, USUARIO, TIPO, AVISO, DESCRIPCION) 
		VALUES (NOW(), NEW.USUARIO, 'FINALIZADO', OLD.AVISO, INFORMACION);
    ELSE
		INSERT INTO COMUNICACION_AVISOS_LOG (FECHA, USUARIO, TIPO, AVISO, DESCRIPCION) 
		VALUES (NOW(), NEW.USUARIO, 'EDITADO', OLD.AVISO, INFORMACION);
    END IF;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRIGGER_COMUNICACION_AVISOS_INSERTAR` AFTER INSERT ON `comunicacion_avisos` FOR EACH ROW BEGIN
	DECLARE INFORMACION LONGTEXT;
    DECLARE NOMBRE_USER VARCHAR(100);
    DECLARE USUARIO_USER VARCHAR(10);
    DECLARE A_ESTADO VARCHAR(50);
    
    SELECT NOMBRE, USUARIO_RR INTO NOMBRE_USER, USUARIO_USER FROM usuarios WHERE ID = NEW.USUARIO;
    SELECT ESTADO INTO A_ESTADO FROM estados WHERE ID = NEW.ESTADO;
    
    SET INFORMACION = CONCAT(
		'[FECHA: ', NEW.FECHA, 
        '][USUARIO: ', USUARIO_USER, 
        '][NOMBRE: ', NOMBRE_USER, 
        '][ESTADO: ', A_ESTADO, 
        '][AVISO: ', NEW.AVISO, 
        '][REGIONAL: ', NEW.REGIONAL, 
        '][FALLA: ', NEW.FALLA, 
        '][DETALLE: ', NEW.DETALLE, 
        '][PRIORIDAD: ', NEW.PRIORIDAD, ']');
    
	INSERT INTO COMUNICACION_AVISOS_LOG (FECHA, USUARIO, TIPO, AVISO, DESCRIPCION) 
    VALUES (NEW.FECHA, NEW.USUARIO, 'INSERTAR', NEW.AVISO, INFORMACION);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicacion_avisos_log`
--

CREATE TABLE `comunicacion_avisos_log` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Log',
  `FECHA` datetime NOT NULL COMMENT 'Fecha del Log',
  `USUARIO` bigint(20) NOT NULL COMMENT 'ID de Usuario',
  `TIPO` varchar(45) NOT NULL COMMENT 'Tipo de Procedimiento INSERTAR, ACTUALIZAR ELIMINAR',
  `AVISO` bigint(20) NOT NULL COMMENT 'Numero de Aviso',
  `DESCRIPCION` mediumtext NOT NULL COMMENT 'Descripcion del Cambio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicacion_aviso_detalle`
--

CREATE TABLE `comunicacion_aviso_detalle` (
  `ID` int(4) NOT NULL COMMENT 'ID detalle ',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Detalle\n',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Detalle',
  `USUARIO` bigint(20) NOT NULL COMMENT 'ID usuario'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `comunicacion_aviso_detalle`
--

INSERT INTO `comunicacion_aviso_detalle` (`ID`, `NOMBRE`, `ESTADO`, `USUARIO`) VALUES
(1, 'PLATAFORMA INTERNET', 1, 1),
(2, 'RUPTURA DE FIBRA', 1, 1),
(3, 'FLUIDO ELECTRICO', 1, 1),
(4, 'HURTO', 1, 1),
(5, 'CAUSA EXOGENO', 1, 1),
(6, 'ESTANDAR', 1, 1),
(7, 'PLATAFORMA TELEVISIÓN', 1, 1),
(8, 'PLATAFORMA TELEFONIA', 1, 1),
(9, 'ALARMA OPTICA', 1, 1),
(10, 'DESENGANCHE MASIVO', 1, 1),
(11, 'FALLA INTERNET', 1, 1),
(12, 'FALLA TELEVISIÓN', 1, 1),
(13, 'FALLA TELEFONIA', 1, 1);

--
-- Disparadores `comunicacion_aviso_detalle`
--
DELIMITER $$
CREATE TRIGGER `TRIGGER_COMUNICACION_DETALLE_EDITAR` AFTER UPDATE ON `comunicacion_aviso_detalle` FOR EACH ROW BEGIN
	DECLARE INFO TEXT;
    DECLARE FECHA_ACTUAL DATETIME;
    DECLARE RR_USUARIO VARCHAR(255);
    DECLARE USUARIO_NOMBRE VARCHAR(100);
    DECLARE NOMBRE_ESTADO VARCHAR(50);
    
    SELECT NOMBRE, USUARIO_RR INTO USUARIO_NOMBRE, RR_USUARIO FROM usuarios WHERE ID = NEW.USUARIO;
    SELECT ESTADO INTO NOMBRE_ESTADO FROM estados WHERE ID = NEW.ESTADO;
    
    SET FECHA_ACTUAL = NOW();
    SET INFO = CONCAT(
		'[FECHA: ', FECHA_ACTUAL, 
        '][TABLA: ', 'COMUNICACION_AVISO_DETALLE',
        '][TIPO: ', 'EDITAR',
        '][USUARIO: ', RR_USUARIO, 
        '][NOMBRE USUARIO: ', USUARIO_NOMBRE, 
        '][ID: ', NEW.ID, '][NOMBRE: ', NEW.NOMBRE, 
        '][ESTADO: ', NOMBRE_ESTADO, ']'
    );
	
    IF NEW.ESTADO = 6
		THEN
			INSERT INTO LOG_TABLA_SELECT (FECHA, TABLA, TIPO, DESCRIPCION, USUARIO)
			VALUE (NOW(), 'COMUNICACION_AVISO_DETALLE', 'ELIMINADO', INFO, NEW.USUARIO);
	ELSE
		INSERT INTO LOG_TABLA_SELECT (FECHA, TABLA, TIPO, DESCRIPCION, USUARIO)
		VALUE (NOW(), 'COMUNICACION_AVISO_DETALLE', 'EDITADO', INFO, NEW.USUARIO);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRIGGER_COMUNICACION_DETALLE_INSERTAR` AFTER INSERT ON `comunicacion_aviso_detalle` FOR EACH ROW BEGIN
	DECLARE INFO TEXT;
    DECLARE FECHA_ACTUAL DATETIME;
    DECLARE RR_USUARIO VARCHAR(255);
    DECLARE USUARIO_NOMBRE VARCHAR(100);
    DECLARE NOMBRE_ESTADO VARCHAR(50);
    
    SELECT NOMBRE, USUARIO_RR INTO USUARIO_NOMBRE, RR_USUARIO FROM usuarios WHERE ID = NEW.USUARIO;
    SELECT ESTADO INTO NOMBRE_ESTADO FROM estados WHERE ID = NEW.ESTADO;
    
    SET FECHA_ACTUAL = NOW();
    SET INFO = CONCAT(
		'[FECHA: ', FECHA_ACTUAL, 
        '][TABLA: ', 'COMUNICACION_AVISO_DETALLE',
        '][TIPO: ', 'INSERTAR',
        '][USUARIO: ', RR_USUARIO, 
        '][NOMBRE USUARIO: ', USUARIO_NOMBRE, 
        '][ID: ', NEW.ID, '][NOMBRE: ', NEW.NOMBRE, 
        '][ESTADO: ', NOMBRE_ESTADO, ']'
    );
	INSERT INTO LOG_TABLA_SELECT (FECHA, TABLA, TIPO, DESCRIPCION, USUARIO) VALUES (FECHA_ACTUAL, 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', INFO, NEW.USUARIO);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `ID` int(2) NOT NULL COMMENT 'ID del Estado',
  `ESTADO` varchar(50) NOT NULL COMMENT 'Texto del Estado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`ID`, `ESTADO`) VALUES
(1, 'ACTIVO'),
(2, 'INACTIVO'),
(3, 'PENDIENTE'),
(4, 'FINALIZADO'),
(5, 'GUARDADO'),
(6, 'ELIMINADO'),
(7, 'ASIGNADO'),
(8, 'PROCESO'),
(9, 'PAUSADO'),
(10, 'EMERGENCIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formularios_log`
--

CREATE TABLE `formularios_log` (
  `ID` int(2) NOT NULL COMMENT 'ID del formulario Dato',
  `FORMULARIO` varchar(100) NOT NULL COMMENT 'Formulario que envia el dato ',
  `CUENTA` bigint(20) NOT NULL COMMENT 'Cuenta cliente afectado',
  `RAZON` varchar(100) NOT NULL COMMENT 'razón de la consulta'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_contrasena`
--

CREATE TABLE `formulario_contrasena` (
  `ID` int(2) NOT NULL COMMENT 'ID lista motivos Cambio de Contraseña',
  `MOTIVO` varchar(100) NOT NULL COMMENT 'Motivo cambio de Contraseña',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado Formulario Cambio de Contraseña'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `formulario_contrasena`
--

INSERT INTO `formulario_contrasena` (`ID`, `MOTIVO`, `ESTADO`) VALUES
(1, 'CONTRASEÑA DE DIFÍCIL RECORDACIÓN', 1),
(2, 'CONTRASEÑA WIFI COMPLEJA', 1),
(3, 'CLIENTE REPORTA QUE SE ESTAN COLGANDO A SU RED WIFI', 1),
(4, 'NO LE GUSTA EL NOMBRE ACTUAL DE SU RED Y/O CLAVE', 1),
(5, 'NO ES UNA CLAVE SEGURA ', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_plantilla`
--

CREATE TABLE `guiones_plantilla` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de la plantilla',
  `GRUPO` varchar(200) NOT NULL COMMENT 'Grupo al que pertence la plantilla',
  `SUB` varchar(200) NOT NULL COMMENT 'Sub Grupo de la plantilla',
  `NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre o titulo de la plantilla',
  `PLANTILLA` mediumtext COMMENT 'Plantilla correspondiente',
  `ESTADO` int(2) NOT NULL COMMENT 'ID del estado de la plantilla'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_plantilla`
--

INSERT INTO `guiones_plantilla` (`ID`, `GRUPO`, `SUB`, `NOMBRE`, `PLANTILLA`, `ESTADO`) VALUES
(1, 'HFC', 'TRIPLEPLAY', 'PRIORIDAD 1', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ ubicacion.NOMBRE|e|title }} {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN:\r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(2, 'HFC', 'TRIPLEPLAY', 'PRIORIDAD 2', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ ubicacion.NOMBRE|e|title }} {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(3, 'HFC', 'TRIPLEPLAY', 'PRIORIDAD 3', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ ubicacion.NOMBRE|e|title }} {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino, para clientes con @ y/o TF validar los modem por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… Para clientes con solo Televisión validar el vecino telefónicamente, \r\nsi ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(4, 'HFC', 'TRIPLEPLAY', 'PRIORIDAD 4', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ ubicacion.NOMBRE|e|title }} {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino, para clientes con @ y/o TF validar los modem por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… Para clientes con solo Televisión validar el vecino telefónicamente, \r\nsi ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(5, 'HFC', 'TVBIDIRECCIONAL', 'PRIORIDAD 1', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(6, 'HFC', 'TVBIDIRECCIONAL', 'PRIORIDAD 2', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(7, 'HFC', 'TVBIDIRECCIONAL', 'PRIORIDAD 3', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino, para clientes con @ y/o TF validar los modem por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… Para clientes con solo Televisión validar el vecino telefónicamente, \r\nsi ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(8, 'HFC', 'TVBIDIRECCIONAL', 'PRIORIDAD 4', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino, para clientes con @ y/o TF validar los modem por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… Para clientes con solo Televisión validar el vecino telefónicamente, \r\nsi ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(9, 'HFC', 'TVUNIDIRECCIONAL', 'PRIORIDAD 1', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(10, 'HFC', 'TVUNIDIRECCIONAL', 'PRIORIDAD 2', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(11, 'HFC', 'TVUNIDIRECCIONAL', 'PRIORIDAD 3', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(12, 'HFC', 'TVUNIDIRECCIONAL', 'PRIORIDAD 4', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1),
(13, 'HFC', 'INTYTEL', 'PRIORIDAD 1', '{% if Datos.AVERIA == \'Sin_Niveles\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Ruido\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />		\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Potencia\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desfasados\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} ', 1);
INSERT INTO `guiones_plantilla` (`ID`, `GRUPO`, `SUB`, `NOMBRE`, `PLANTILLA`, `ESTADO`) VALUES
(14, 'HFC', 'INTYTEL', 'PRIORIDAD 2', '{% if Datos.AVERIA == \'Sin_Niveles\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}. \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 8 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Ruido\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />		\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Potencia\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desfasados\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} ', 1),
(15, 'HFC', 'INTYTEL', 'PRIORIDAD 3', '{% if Datos.AVERIA == \'Sin_Niveles\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}. \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… si ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Ruido\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS.   \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />		\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Potencia\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desfasados\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} ', 1),
(16, 'HFC', 'INTYTEL', 'PRIORIDAD 4', '{% if Datos.AVERIA == \'Sin_Niveles\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}. \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 17 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino por Diagnosticador y garantizar que el {{ ubicacion.NOMBRE|e|title }} \r\ncorresponda al reportado… si ambos presentan la misma falla reportar las cuentas. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Ruido\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS.   \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />		\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desf_Potencia\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS.  \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} {% if Datos.AVERIA == \'Niveles_Desfasados\' %}\r\n<br />\r\n AVISO: {{ Datos.AVISO|e }}. \r\n SÍNTOMA:  Cable_Módem {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }} en {{ ubicacion.NOMBRE|e|title }}.  \r\n SERV.AFECTADOS: 3_Play \r\n________________________________________________________________________________________________________________ \r\nRecuerde: <br />{{ Datos.AVERIA|e }} generan Intermitencia, Pixelación, Ausencia de Señal, Lentitud y problemas de Calidad de Voz  \r\n________________________________________________________________________________________________________________ \r\nGUIÓN: \r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'5\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 98 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación SOP-MRU \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />\r\n{% endif %} ', 1),
(17, 'MATRIZ', 'TRIPLEPLAY', 'PRIORIDAD 1', 'MATRIZ: {{Datos.MATRIZ|e}} {% if Datos.DETALLE == TRUE %}({{Datos.DETALLE|e}} {{Datos.REFERENCIA|e}}){% endif %}  AVISO: {{Datos.AVISO|e}} <br /> SÍNTOMA: {{Datos.AVERIA|e}} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }}...  SERV.AFECTADOS: {{Datos.AFECTACION|e}} \n________________________________________________________________________________________________________________ \nGUIÓN:\n{% if Datos.RAZON == \'1\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n{% if Datos.RAZON == \'2\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n{% if Datos.RAZON == \'3\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n{% if Datos.RAZON == \'4\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n{% if Datos.RAZON == \'12\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n{% if Datos.RAZON == \'6\' %}\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\n{% endif %} \n________________________________________________________________________________________________________________ \nPROCESO DE SOPORTE: “NO REQUIERE VALIDAR VECINOS.”  \nReportar la cuenta a través de la Página de Gerencia (opción de reportar edificio) – agregar notas. \n________________________________________________________________________________________________________________\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FED \n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\n<br /><br />', 1),
(18, 'MATRIZ', 'TRIPLEPLAY', 'PRIORIDAD 2', 'MATRIZ: {{Datos.MATRIZ|e}} {% if Datos.DETALLE == TRUE %}({{Datos.DETALLE|e}} {{Datos.REFERENCIA|e}}){% endif %}  AVISO: {{Datos.AVISO|e}} <br /> SÍNTOMA: {{Datos.AVERIA|e}} {{ Datos.INTERMITENCIA == \'SI\' ? \'y/o Intermitencia\' }}...  SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN:\r\n{% if Datos.RAZON == \'1\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'2\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'3\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'4\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'12\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == \'6\' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 12 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Validar 1 vecino cercano, para clientes con @ y/o TF validar los modem por Diagnosticador \r\ny garantizar que el nodo corresponda al reportado… Para clientes con solo Televisión validar el vecino telefónicamente, \r\nsi ambos presentan la misma falla reportar las cuentas a través de la Página de Gerencia (opción de reportar edificio) – agregar notas.\r\n________________________________________________________________________________________________________________\r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FED \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_razon_averia`
--

CREATE TABLE `guiones_razon_averia` (
  `ID` int(2) NOT NULL COMMENT ' ID guión razon averia ',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre Razón Avería',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado RAZON AVERIA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_razon_averia`
--

INSERT INTO `guiones_razon_averia` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'HURTO', 1),
(2, 'RUPTURA DE FIBRA', 1),
(3, 'FLUIDO ELÉCTRICO', 1),
(4, 'PLATAFORMA', 1),
(5, 'CAUSA EXÓGENO', 1),
(6, 'ESTÁNDAR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro`
--

CREATE TABLE `guiones_registro` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del registro ',
  `FECHA` datetime NOT NULL COMMENT 'Fecha del registro del guión',
  `USUARIO` bigint(20) NOT NULL COMMENT 'ID de usuario',
  `TIPO` int(2) NOT NULL COMMENT 'ID Tipo de Guion ',
  `AFECTACION` varchar(25) DEFAULT NULL COMMENT 'Servicio afectado ',
  `AVISO` varchar(255) NOT NULL COMMENT 'Numero o ref del Aviso ',
  `UBICACION` varchar(10) NOT NULL COMMENT 'Ubicacion Tecnica Sector o Nodo',
  `AVERIA` varchar(50) NOT NULL COMMENT 'Averia del dispositivo cablemodem desengachado, etc',
  `RAZON` int(2) NOT NULL COMMENT 'ID de Razón Avería'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_hfc`
--

CREATE TABLE `guiones_registro_hfc` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Registro HFC',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID del registro',
  `INTERMITENCIA` varchar(2) NOT NULL DEFAULT 'NO' COMMENT 'intermitencia del servicio ',
  `GUION` text NOT NULL COMMENT 'Guion Creado',
  `PRIORIDAD` int(2) NOT NULL COMMENT 'ID de la prioridad'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_matriz`
--

CREATE TABLE `guiones_registro_matriz` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del registro de Matriz',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID del registro',
  `PRIORIDAD` int(2) NOT NULL COMMENT 'ID de la prioridad',
  `MATRIZ` bigint(20) NOT NULL COMMENT 'Numero de la matriz',
  `DETALLE` varchar(20) DEFAULT NULL COMMENT 'Detalle de la matriz\nse es casa -  interior - bloque ...',
  `REFERENCIA` varchar(20) DEFAULT NULL COMMENT 'Referencia de la matriz',
  `INTERMITENCIA` varchar(2) DEFAULT 'NO' COMMENT 'Intermitencia del servicio',
  `GUION` text NOT NULL COMMENT 'Guion generado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_plaforma_soporte`
--

CREATE TABLE `guiones_registro_plaforma_soporte` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de los procesos de soporte',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'Registro del Guion y su soporte',
  `SOPORTE` varchar(255) NOT NULL COMMENT 'Proceso de soporte'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_plataforma`
--

CREATE TABLE `guiones_registro_plataforma` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del registro de plataforma',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID del registro',
  `PRIORIDAD` int(2) NOT NULL COMMENT 'ID de la Prioridad',
  `SINTOMA` varchar(255) NOT NULL COMMENT 'sintoma del aviso',
  `NOTAS` varchar(2) NOT NULL DEFAULT 'NO' COMMENT 'Agregar notas de gerencia',
  `INFORMACION` varchar(255) DEFAULT NULL COMMENT 'Informacion importante si lo amerita',
  `MARCACION` varchar(10) DEFAULT NULL COMMENT 'Marcacion del Guion',
  `GUION` text NOT NULL COMMENT 'guion del Aviso'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_plataforma_equipos`
--

CREATE TABLE `guiones_registro_plataforma_equipos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del registro de equipos en los guiones',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID del registro',
  `TIPO` varchar(20) NOT NULL COMMENT 'Tipo de equipo registrado',
  `NOMBRE` varchar(255) NOT NULL COMMENT 'Nombre del equipo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_ubicacion`
--

CREATE TABLE `guiones_registro_ubicacion` (
  `ID` int(2) NOT NULL COMMENT 'Id de registro de Ubicación',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre de la Ubicación',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de Ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_registro_ubicacion`
--

INSERT INTO `guiones_registro_ubicacion` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'SECTOR', 1),
(2, 'NODO', 1),
(3, 'CIUDAD', 2),
(4, 'REGIONAL', 2),
(5, 'NACIONAL', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_ubicacion_ciudad`
--

CREATE TABLE `guiones_registro_ubicacion_ciudad` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Guiones Registro Ciudad',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID de Tabla  Guiones Registro',
  `NODO` bigint(20) NOT NULL COMMENT 'ID tabla Radiografia de Nodos'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_ubicacion_nodo`
--

CREATE TABLE `guiones_registro_ubicacion_nodo` (
  `ID` bigint(20) NOT NULL COMMENT 'ID registro nodo',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID de Tabla  Guiones Registro',
  `NODO` bigint(20) NOT NULL COMMENT 'ID tabla Radiografia de Nodos'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_registro_ubicacion_regional`
--

CREATE TABLE `guiones_registro_ubicacion_regional` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Guiones registro ubicación Regional',
  `REGISTRO` bigint(20) NOT NULL COMMENT 'ID de Tabla  Guiones Registro',
  `REGIONAL` int(3) NOT NULL COMMENT 'ID con la tabla de lista_Regionales'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_tipo`
--

CREATE TABLE `guiones_tipo` (
  `ID` int(2) NOT NULL COMMENT 'ID del tipo de guion\n',
  `NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre del guion',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del tipo Aviso'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_tipo`
--

INSERT INTO `guiones_tipo` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'HFC', 1),
(2, 'MATRIZ', 1),
(3, 'PLATAFORMA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_tipo_afectacion`
--

CREATE TABLE `guiones_tipo_afectacion` (
  `ID` int(10) NOT NULL COMMENT 'ID del guion tipo de afectacon ',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'nombre del tipo de afectacion',
  `GUION` varchar(50) NOT NULL COMMENT 'Nombre que aparece en el Guion ',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del tipo de afectacion',
  `SERVICIO` int(10) NOT NULL COMMENT 'ID de  tipo servicio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_tipo_afectacion`
--

INSERT INTO `guiones_tipo_afectacion` (`ID`, `NOMBRE`, `GUION`, `ESTADO`, `SERVICIO`) VALUES
(1, 'SIN SEÑAL', 'Sin_Señal', 1, 1),
(2, 'SIN SEÑAL', 'Sin_Señal', 1, 2),
(3, 'SEÑAL DEFICIENTE', 'Señal_Deficiente', 1, 2),
(4, 'SEÑAL PIXELADA', 'Señal_Pixelada', 1, 2),
(5, 'PIXELADA PLATAFORMA', 'Pixelada(Plataforma)', 1, 2),
(6, 'SIN SEÑAL', 'Sin_Señal', 1, 3),
(7, 'SEÑAL DEFICIENTE', 'Señal_Deficiente', 1, 3),
(8, 'SEÑAL PIXELADA', 'Señal_Pixelada', 1, 3),
(9, 'SIN NIVELES', 'Sin_Niveles', 1, 4),
(10, 'DESFASE RUIDO', 'Niveles_Desf_Ruido', 1, 4),
(11, 'DESFASE POTENCIA', 'Niveles_Desf_Potencia', 1, 4),
(12, 'DESFASE NIVELES', 'Niveles_Desfasados', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guiones_tipo_servicio`
--

CREATE TABLE `guiones_tipo_servicio` (
  `ID` int(10) NOT NULL COMMENT 'ID del tipo de servicio ',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'Nombre del tipo de Servicio',
  `ESTADO` int(2) NOT NULL COMMENT 'ID del estado del Tipo de Servicio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `guiones_tipo_servicio`
--

INSERT INTO `guiones_tipo_servicio` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'TRIPLE PLAY', 1),
(2, 'TELEVISION BIDIRECCIONAL', 1),
(3, 'TELEVISION UNIDIRECCIONAL', 1),
(4, 'INTERNET Y TELEFONIA ', 1),
(5, 'INTERNET', 1),
(6, 'TELEVISION', 1),
(7, 'TELEFONIA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_cablemodem`
--

CREATE TABLE `lista_cablemodem` (
  `ID` int(3) NOT NULL COMMENT 'ID de Cablemodem',
  `MARCA` varchar(100) NOT NULL COMMENT 'Marca del Cablemodem',
  `REFERENCIA` varchar(250) NOT NULL COMMENT 'Referencia del Cablemodem',
  `FIRMWARE` varchar(100) NOT NULL COMMENT 'Firmware del Cablemodem',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Cablemodem'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_cablemodem`
--

INSERT INTO `lista_cablemodem` (`ID`, `MARCA`, `REFERENCIA`, `FIRMWARE`, `ESTADO`) VALUES
(1, 'Motorola', 'SBG900', '2.1.18.0-NOSH', 1),
(2, 'Motorola', 'SBG940', '2.1.18.0-NOSH', 1),
(3, 'Motorola', 'SVG1202', '05-741-LTSH', 1),
(4, 'Motorola', 'SBG901\r\n', '03-501-NOSH ', 1),
(5, 'ARRIS', '862', '7.5.125B', 1),
(6, 'ARRIS', '862', '7.5.125C', 1),
(7, 'Thomson', 'DCW725', 'ST5A.31.13', 1),
(8, 'Thomson', 'DWG849', 'STC0.01.16', 1),
(9, 'Technicolor', 'TC7110', 'STD3.31.19', 1),
(10, 'Technicolor', 'TC7300', 'STF3.31.02', 1),
(11, 'Ubee', '2608', '5,117,1007', 1),
(12, 'Ubee', '2100', '6.28.4003', 1),
(13, 'Ubee', '2110', '6.36.1005', 1),
(14, 'Cisco', 'DPC2420', '120921as', 1),
(15, 'Cisco', 'DPC2420R2', '140106as-v6', 1),
(16, 'Cisco', 'DPC2425', '120921as', 1),
(17, 'Cisco', 'DPC2434-X', '110128as', 1),
(18, 'Cisco', 'DPC3925', '131025a', 1),
(19, 'Cisco', 'DPC3925', '140829a', 1),
(20, 'Coship', 'Claro Video', 'N9085I', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_cargo`
--

CREATE TABLE `lista_cargo` (
  `ID` int(2) NOT NULL,
  `NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre del Cargo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_cargo`
--

INSERT INTO `lista_cargo` (`ID`, `NOMBRE`) VALUES
(1, 'MEJORAMIENTO '),
(2, 'CAUSA_RAIZ'),
(3, 'SERVICE_DESK'),
(4, 'APOYO_EXPERTO'),
(5, 'LIDER'),
(6, 'TORRE'),
(7, 'EXPERTO_PISO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_decodificadores`
--

CREATE TABLE `lista_decodificadores` (
  `ID` int(3) NOT NULL COMMENT 'ID Decodificador',
  `MARCA` varchar(100) NOT NULL COMMENT 'Marca del Decodificador',
  `REFERENCIA` varchar(250) NOT NULL COMMENT 'Referencia del Decodificador',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Cablemodem'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_decodificadores`
--

INSERT INTO `lista_decodificadores` (`ID`, `MARCA`, `REFERENCIA`, `ESTADO`) VALUES
(1, 'Motorola', 'General', 1),
(2, 'Motorola', 'General_HD', 1),
(3, 'Motorola', 'General_SD', 1),
(4, 'Motorola', 'General_PVR', 1),
(5, 'Motorola', 'DC1', 1),
(6, 'Motorola', 'DC7', 1),
(7, 'Motorola', 'DC8', 1),
(8, 'Motorola', 'DCI', 1),
(9, 'Motorola', 'DCV', 1),
(10, 'Motorola', 'D34', 1),
(11, 'Motorola', 'DCX', 1),
(12, 'Motorola', 'DX2', 1),
(13, 'Motorola', 'DX4', 1),
(14, 'Motorola', 'DX5', 1),
(15, 'Motorola', 'DX7', 1),
(16, 'Motorola', 'Arris_X25', 1),
(17, 'DVB', 'General', 1),
(18, 'DVB', 'General_SD', 1),
(19, 'DVB', 'General_HD', 1),
(20, 'DVB', 'General_PVR', 1),
(21, 'DVB_Huawei', 'SD_HLC ', 1),
(22, 'DVB_Huawei', 'SD_WHI ', 1),
(23, 'DVB_Huawei', 'HD_HHD', 1),
(24, 'DVB_Huawei', 'PVR_HPV', 1),
(25, 'DVB_Skyworth', 'SD_SKY', 1),
(26, 'DVB_Coship', 'SD_CSH', 1),
(27, 'DVB_Coship', 'HD_CHD', 1),
(28, 'DTH', 'General', 1),
(29, 'DTH_ARION', 'ARI', 1),
(30, 'DTH_ARION', 'AF-5012S', 1),
(31, 'DTH_ARION', 'ARD-2810HP', 1),
(32, 'DTH_ARION', 'AF-5210VHD', 1),
(33, 'DVB', 'Jiuzhou', 1),
(34, 'DVB', 'Technicolor_HD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_marcaciones`
--

CREATE TABLE `lista_marcaciones` (
  `ID` int(3) NOT NULL COMMENT 'ID Lista de Marcaciones',
  `RAZON` varchar(3) NOT NULL COMMENT 'Razón de la Marcación',
  `MARCACION` varchar(3) NOT NULL COMMENT 'Marcación',
  `DETALLE` longtext NOT NULL COMMENT 'Detalle Marcación Servicio ',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la Marcación'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_marcaciones`
--

INSERT INTO `lista_marcaciones` (`ID`, `RAZON`, `MARCACION`, `DETALLE`, `ESTADO`) VALUES
(1, 'OPE', 'COV', 'Confirmación De Visita', 1),
(2, 'OPE', 'PVM', 'Programación de Mantenimiento falla mas de un servicio ', 1),
(3, 'OPE', 'REA', 'Re-Agendamiento O Cancelación De Visita   ', 1),
(4, 'OPE', 'FTV', 'Incumplimiento De Visita de mantenimiento ', 1),
(5, 'OPE', 'IPI', 'Insatisfacción Con La Instalación', 1),
(6, 'OPE', 'IPM', 'Insatisfacción Con El Mantenimiento ', 1),
(7, 'OPE', 'FII', 'Incumplimiento Fecha Instalación ', 1),
(8, 'OPE', 'DOT', 'Daños A Terceros', 1),
(9, 'OPE', 'MTT', 'Mala Atención Del Técnico ', 1),
(10, 'OPE', 'FGV', 'Fraude Generado Por Visita De Un Técnico ', 1),
(11, 'OPE', 'ATX', 'Accidentes Causados Por Telmex', 1),
(12, 'OPE', 'RDT', 'Reporte De Delitos', 1),
(13, 'SAC', 'RAE', 'Replanteamientos de acometida', 1),
(14, 'SAC', 'TRA', 'Petición de Traslado', 1),
(15, 'SAC', 'STR', 'Seguimiento Traslado', 1),
(16, 'SOP', 'CVD', 'Soporte Claro Video', 1),
(17, 'SOP', 'PCL', 'Información  portal  Mi Claro', 1),
(18, 'SOP', 'ICV', 'Cliente sin Ingreso a Claro video', 1),
(19, 'SOP', 'PCI', 'Problema con Ingreso a mi Claro Portal', 1),
(20, 'SOP', 'DIE', 'Soporte PC DIENS', 1),
(21, 'SAC', 'SED', 'Soporte Equipo DIENS', 1),
(22, 'SOP', 'CAA', 'Soporte correo archivos Adjuntos', 1),
(23, 'SOP', 'CEI', 'Información errores comunes del Correo', 1),
(24, 'SOP', 'PDD', 'Sin internet problema con dhcp y dns', 1),
(25, 'SOP', 'ITC', 'Información tamaño correo', 1),
(26, 'DIS', 'FCI', 'Fallas con servicio de correo', 1),
(27, 'SOP', 'ZWF', 'Sin conexión a zonas WIFI', 1),
(28, 'SOP', 'MCC', 'Solicitud modificar la contraseña de correo', 1),
(29, 'SOP', 'CRN', 'Cliente no puede recibir correos', 1),
(30, 'SOP', 'COR', 'Solicitud  crear cuenta de correo', 1),
(31, 'SOP', 'CCO', 'Soporte configuración correo', 1),
(32, 'SOP', 'ENC', 'Cliente no puede enviar correos', 1),
(33, 'SOP', 'AIP', 'Solicitud asignación IP fija', 1),
(34, 'DIS', 'APW', 'Problema accesos pagina WEB', 1),
(35, 'DIS', 'LNM', 'Lentitud niveles modem', 1),
(36, 'DIS', 'WWF', 'Sin  internet WIFI  - Fallas potencia inalámbrica perdida de paquetes', 1),
(37, 'SOP', 'PIC', 'Configuración de IP Fija', 1),
(38, 'SOP', 'EDA', 'Sin internet error de aprovisionamiento', 1),
(39, 'SOP', 'ICW', 'Información Confirmación Clave WIFI', 1),
(40, 'SOP', 'SSI', 'sin internet WIFI desconfigurado SSID', 1),
(41, 'DIS', 'FMM', 'Sin Internet  Fallas MTA y/o Modem', 1),
(42, 'SOP', 'CMI', 'Solicitudes  (CMI avanzadas)', 1),
(43, 'SOP', 'IDI', 'Soporte sin internet  falla dispositivos', 1),
(44, 'SOP', 'WRC', 'Internet wifi reinicio de cm', 1),
(45, 'SOP', 'WEP', 'Solicitud Personalización Clave WIFI -WPA - SSID', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_referencia_decos_rr`
--

CREATE TABLE `lista_referencia_decos_rr` (
  `ID` int(3) NOT NULL COMMENT 'ID Referencia DecosRR',
  `PLATAFORMA` varchar(100) NOT NULL COMMENT 'Plataforma del Decodificador',
  `MARCA` varchar(100) NOT NULL COMMENT 'Marca del Decodificador',
  `MODELO` varchar(250) NOT NULL COMMENT 'Modelo del Decodificador',
  `REFERENCIA` varchar(50) NOT NULL COMMENT 'Referencia RR Decodificador',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la Referencia'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_referencia_decos_rr`
--

INSERT INTO `lista_referencia_decos_rr` (`ID`, `PLATAFORMA`, `MARCA`, `MODELO`, `REFERENCIA`, `ESTADO`) VALUES
(1, 'DVB', 'SKYWORTH', 'C7000NX', 'DBV - SKY', 1),
(2, 'DVB', 'COSHIP', 'N5266C', 'DBV - CSH', 1),
(3, 'DVB', 'COSHIP', 'N5266C - L', 'DBV - CSH', 1),
(4, 'DVB', 'COSHIP', 'N8760C', 'DBV - CHD', 1),
(6, 'DVB', 'HUAWEI', 'DC211', 'DBV - WHI', 1),
(7, 'DVB', 'HUAWEI', 'DC217', 'DBV - HLC', 1),
(8, 'DVB', 'HUAWEI', 'DC352', 'DBV - HHD', 1),
(9, 'DVB', 'HUAWEI', 'DC562', 'DBV - HPV', 1),
(10, 'DVB', 'TECHNICOLOR', 'DCI420CLC', 'DBV - TCN', 1),
(11, 'DVB', 'JIUZHOU', 'DTC1736', 'DBV - JZU', 1),
(12, 'ATSC', 'MOTOROLA', 'DCT700', 'DDG - DC7', 1),
(13, 'ATSC', 'MOTOROLA', 'DCT700', 'DDG - DCV', 1),
(14, 'ATSC', 'MOTOROLA', 'DCT700', 'DDG - DC1', 1),
(15, 'ATSC', 'MOTOROLA', 'DCT3416', 'DDG - D34', 1),
(16, 'ATSC', 'MOTOROLA', 'DCX3200', 'DDG - DCX', 1),
(17, 'ATSC', 'MOTOROLA', 'DCX3400', 'DDG - DX4', 1),
(18, 'ATSC', 'MOTOROLA', 'DCX700', 'DDG - DX7', 1),
(19, 'ATSC', 'MOTOROLA', 'DCX3510', 'DDG - DX5', 1),
(20, 'ATSC', 'MOTOROLA', 'DCX3210', 'DDG - DX2', 1),
(21, 'ATSC', 'MOTOROLA', 'DCX525', 'DDG - X25', 1),
(22, 'ATSC', 'MOTOROLA', 'DCT1800', 'DDG - DC8', 1),
(23, 'CLARO VIDEO', 'COSHIP', 'N9085I', 'DLA - OTT', 1),
(24, 'DTH', 'ARION', 'AF-5012S', 'DTH - ARI', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_regionales`
--

CREATE TABLE `lista_regionales` (
  `ID` int(3) NOT NULL COMMENT 'Id lista Regionales',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre de la Regional',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la Regional'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_regionales`
--

INSERT INTO `lista_regionales` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'REGIONAL ANTIOQUIA', 1),
(2, 'REGIONAL BOGOTA', 1),
(3, 'REGIONAL EJE CAFETERO', 1),
(4, 'REGIONAL CENTRO', 1),
(5, 'REGIONAL NORTE', 1),
(6, 'REGIONAL OCCIDENTE', 1),
(7, 'REGIONAL ORIENTE', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_softswich`
--

CREATE TABLE `lista_softswich` (
  `ID` int(3) NOT NULL COMMENT 'ID Softswich',
  `MARCA` varchar(100) NOT NULL COMMENT 'Marca del Softswich',
  `REFERENCIA` varchar(250) NOT NULL COMMENT 'Referencia del Softswich',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Softswich'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_softswich`
--

INSERT INTO `lista_softswich` (`ID`, `MARCA`, `REFERENCIA`, `ESTADO`) VALUES
(1, 'SAFARI', 'BOG03', 1),
(2, 'SAFARI', 'BOG05', 1),
(3, 'SAFARI', 'BOG06', 1),
(4, 'SAFARI', 'BQA01', 1),
(5, 'SAFARI', 'BUC01', 1),
(6, 'SAFARI', 'CAL01', 1),
(7, 'SAFARI', 'MED01', 1),
(8, 'SAFARI', 'PER01', 1),
(9, 'SAFARI', 'TANDEM', 1),
(10, 'IMS', 'BOG07', 1),
(11, 'HIQ', 'TRIARA', 1),
(12, 'HIQ', 'ORTEZAL', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_tabla_select`
--

CREATE TABLE `log_tabla_select` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del LOG Select',
  `FECHA` datetime NOT NULL COMMENT 'Fecha del LOG Select',
  `TABLA` varchar(100) NOT NULL COMMENT 'Nombre de la Tabla',
  `TIPO` varchar(45) NOT NULL COMMENT 'Tipo de Proceso',
  `DESCRIPCION` text NOT NULL COMMENT 'Descripcion del Cambio',
  `USUARIO` bigint(20) NOT NULL COMMENT 'ID de usuario'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `log_tabla_select`
--

INSERT INTO `log_tabla_select` (`ID`, `FECHA`, `TABLA`, `TIPO`, `DESCRIPCION`, `USUARIO`) VALUES
(1, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 1][NOMBRE: PLATAFORMA INTERNET][ESTADO: ACTIVO]', 1),
(2, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 2][NOMBRE: RUPTURA DE FIBRA][ESTADO: ACTIVO]', 1),
(3, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 3][NOMBRE: FLUIDO ELECTRICO][ESTADO: ACTIVO]', 1),
(4, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 4][NOMBRE: HURTO][ESTADO: ACTIVO]', 1),
(5, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 5][NOMBRE: CAUSA EXOGENO][ESTADO: ACTIVO]', 1),
(6, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 6][NOMBRE: ESTANDAR][ESTADO: ACTIVO]', 1),
(7, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 7][NOMBRE: PLATAFORMA TELEVISIÓN][ESTADO: ACTIVO]', 1),
(8, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 8][NOMBRE: PLATAFORMA TELEFONIA][ESTADO: ACTIVO]', 1),
(9, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 9][NOMBRE: ALARMA OPTICA][ESTADO: ACTIVO]', 1),
(10, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 10][NOMBRE: DESENGANCHE MASIVO][ESTADO: ACTIVO]', 1),
(11, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 11][NOMBRE: FALLA INTERNET][ESTADO: ACTIVO]', 1),
(12, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 12][NOMBRE: FALLA TELEVISIÓN][ESTADO: ACTIVO]', 1),
(13, '2017-04-17 15:45:57', 'COMUNICACION_AVISO_DETALLE', 'INSERTAR', '[FECHA: 2017-04-17 15:45:57][TABLA: COMUNICACION_AVISO_DETALLE][TIPO: INSERTAR][USUARIO: Fix][NOMBRE USUARIO: Súper Root][ID: 13][NOMBRE: FALLA TELEFONIA][ESTADO: ACTIVO]', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_base`
--

CREATE TABLE `permisos_base` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Tipo de Permiso',
  `LECTURA` int(1) NOT NULL DEFAULT '0' COMMENT 'Permiso de Lectura',
  `ESCRITURA` int(1) NOT NULL DEFAULT '0' COMMENT 'Permiso de Escritura',
  `ELIMINAR` int(1) NOT NULL DEFAULT '0' COMMENT 'Permiso de Eliminacion',
  `ACTUALIZAR` int(1) NOT NULL DEFAULT '0' COMMENT 'Permiso de Actualizacion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos_base`
--

INSERT INTO `permisos_base` (`ID`, `LECTURA`, `ESCRITURA`, `ELIMINAR`, `ACTUALIZAR`) VALUES
(1, 0, 0, 0, 0),
(2, 1, 0, 0, 0),
(3, 1, 1, 0, 0),
(4, 1, 1, 1, 0),
(5, 1, 1, 1, 1),
(6, 0, 0, 0, 1),
(7, 0, 0, 1, 1),
(8, 0, 1, 1, 1),
(9, 1, 0, 0, 1),
(10, 0, 1, 1, 0),
(11, 1, 0, 1, 0),
(12, 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_modulos`
--

CREATE TABLE `permisos_modulos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Modulo',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'Nombre del Modulo',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Modulo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos_modulos`
--

INSERT INTO `permisos_modulos` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'Chat', 1),
(2, 'Datos_Usuario', 1),
(3, 'HFC', 1),
(4, 'Index', 1),
(5, 'Inicio', 1),
(6, 'Matriz', 1),
(7, 'Plataforma', 1),
(8, 'Usuarios', 1),
(9, 'Dropdown', 1),
(10, 'Mantenimientos', 1),
(11, 'Mejoramiento', 1),
(12, 'Comunicacion', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_nombre`
--

CREATE TABLE `permisos_nombre` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de Nombre del Permiso',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Permiso',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Nombre del Permiso'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos_nombre`
--

INSERT INTO `permisos_nombre` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'SUPER_USUARIO', 1),
(2, 'ANALISTA_MEJORAMIENTO', 1),
(3, 'ANALISTA_EXPERTO', 1),
(4, 'CONTROL_CALIDAD', 1),
(5, 'LIDER', 1),
(6, 'ADMINISTRADOR', 1),
(7, 'INVITADO_COMUNICACION', 1),
(8, 'EXPERTO_PISO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_seleccion`
--

CREATE TABLE `permisos_seleccion` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Permiso de Seleccion',
  `NOMBRE_ID` bigint(20) NOT NULL COMMENT 'ID del Nombre del Permiso',
  `MODULOS_ID` bigint(20) NOT NULL COMMENT 'ID de Modulo',
  `BASE_ID` bigint(20) NOT NULL COMMENT 'ID de Base de Permiso'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos_seleccion`
--

INSERT INTO `permisos_seleccion` (`ID`, `NOMBRE_ID`, `MODULOS_ID`, `BASE_ID`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 5),
(3, 1, 3, 5),
(4, 1, 4, 5),
(5, 1, 5, 5),
(6, 1, 6, 5),
(7, 1, 7, 5),
(8, 1, 8, 5),
(9, 1, 9, 5),
(10, 1, 10, 5),
(11, 1, 11, 5),
(12, 1, 12, 5),
(13, 2, 1, 5),
(14, 2, 2, 5),
(15, 2, 3, 5),
(16, 2, 4, 5),
(17, 2, 5, 5),
(18, 2, 6, 5),
(19, 2, 7, 5),
(20, 2, 8, 5),
(21, 2, 9, 5),
(22, 2, 10, 5),
(23, 2, 11, 5),
(24, 2, 12, 5),
(25, 3, 1, 5),
(26, 3, 2, 5),
(27, 3, 3, 5),
(28, 3, 4, 5),
(29, 3, 5, 5),
(30, 3, 6, 5),
(31, 3, 7, 5),
(32, 3, 8, 5),
(33, 3, 9, 5),
(34, 3, 10, 5),
(35, 3, 12, 5),
(36, 4, 1, 5),
(37, 4, 2, 5),
(38, 4, 3, 5),
(39, 4, 4, 5),
(40, 4, 5, 5),
(41, 4, 6, 5),
(42, 4, 7, 5),
(43, 4, 8, 5),
(44, 4, 9, 2),
(45, 4, 12, 2),
(46, 5, 1, 5),
(47, 5, 2, 5),
(48, 5, 3, 5),
(49, 5, 4, 5),
(50, 5, 5, 5),
(51, 5, 6, 5),
(52, 5, 7, 5),
(53, 5, 8, 5),
(54, 5, 9, 5),
(55, 5, 10, 5),
(56, 5, 11, 5),
(57, 5, 12, 5),
(58, 6, 1, 5),
(59, 6, 2, 5),
(60, 6, 3, 5),
(61, 6, 4, 5),
(62, 6, 5, 5),
(63, 6, 6, 5),
(64, 6, 7, 5),
(65, 6, 8, 5),
(66, 6, 9, 5),
(67, 6, 10, 5),
(68, 6, 11, 5),
(69, 6, 12, 5),
(70, 7, 1, 5),
(71, 7, 4, 2),
(72, 7, 5, 2),
(73, 7, 12, 2),
(74, 8, 1, 5),
(75, 8, 4, 2),
(76, 8, 5, 2),
(77, 8, 12, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridades`
--

CREATE TABLE `prioridades` (
  `ID` int(2) NOT NULL COMMENT 'ID de la prioridad',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre de la Prioridad',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la prioridad'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `prioridades`
--

INSERT INTO `prioridades` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, '1 - ALTA', 1),
(2, '2 - MEDIA - ALTA', 1),
(3, '3 - MEDIA - BAJA', 1),
(4, '4 - BAJA', 1),
(5, '5 - SOLICITUDES', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Proyecto',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Proyecto',
  `FECHA` datetime NOT NULL COMMENT 'Fecha Creación Proyecto\n',
  `FECHA_INICIO` datetime NOT NULL COMMENT 'Inicio Proyecto',
  `FECHA_FIN` datetime NOT NULL COMMENT 'Fin Proyecto',
  `DESCRIPCION` mediumtext NOT NULL COMMENT 'Descripción General ',
  `TIPO` int(2) NOT NULL COMMENT 'Periódico (1) o Aislado (0)',
  `HORAS` int(10) NOT NULL COMMENT 'Horas Definidas Inic.',
  `USUARIO_APERTURA` bigint(20) NOT NULL COMMENT 'Creador de Proyecto ',
  `USUARIO_CIERRE` bigint(20) NOT NULL COMMENT 'Finalizador',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Proyecto'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_fases`
--

CREATE TABLE `proyectos_fases` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de las Fases',
  `ID_PROYECTOS` bigint(20) NOT NULL COMMENT 'ID del Proyecto',
  `USUARIO_ASIGNADO` bigint(20) NOT NULL COMMENT 'ID Usuario Asignado a Proyecto',
  `ID_OBJETIVOS` bigint(20) NOT NULL COMMENT 'ID del Objetivo',
  `FECHA_INICIO` datetime NOT NULL COMMENT 'Inicio de la Fase',
  `FECHA_FIN` datetime NOT NULL COMMENT 'Fin de la Fase',
  `COMENTARIO` mediumtext NOT NULL COMMENT 'Comentario de la Fase',
  `HORAS` int(10) NOT NULL COMMENT 'Horas Fase Asigandas'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_objetivos`
--

CREATE TABLE `proyectos_objetivos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de los Objetivos',
  `ID_PROYECTOS` bigint(20) NOT NULL,
  `FECHA_CREACION` datetime NOT NULL COMMENT 'Creación del Obj.',
  `OBEJTIVO` mediumtext NOT NULL COMMENT 'Descripción del Objetivo',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Objetivo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_usuario_asigando`
--

CREATE TABLE `proyectos_usuario_asigando` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Usuarios Asignados al Proyecto',
  `ID_PROYECTOS` bigint(20) NOT NULL COMMENT 'ID del Proyecto',
  `USUARIO` bigint(20) NOT NULL COMMENT 'Usuario Asigando',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado Usu. Asignado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_dias_festivos`
--

CREATE TABLE `proyecto_dias_festivos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID días festivos',
  `FECHA` datetime NOT NULL COMMENT 'Día Festivo',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de Día Festivo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_escenario`
--

CREATE TABLE `proyecto_escenario` (
  `ID` int(3) NOT NULL COMMENT 'ID Lista escenarios Log',
  `NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre del Escenario'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proyecto_escenario`
--

INSERT INTO `proyecto_escenario` (`ID`, `NOMBRE`) VALUES
(1, 'MODIFICACIÓN HORAS PROYECTO '),
(2, 'AUMENTO HORAS FASE'),
(3, 'CAMBIO NOMBRE PROYECTO '),
(4, 'AGREGO OBJETIVO'),
(5, 'MODIFICO OBJETIVO '),
(6, 'ELIMINO OBJETIVO ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_log`
--

CREATE TABLE `proyecto_log` (
  `ID` bigint(20) NOT NULL COMMENT 'ID Proyecto_ Log',
  `ID_PROYECTO` bigint(20) NOT NULL COMMENT 'ID del Proyecto',
  `ID_ESCENARIO` int(3) NOT NULL COMMENT 'ID Proyecto_Escenario',
  `VALOR_INICIAL` varchar(255) NOT NULL COMMENT 'Valor Inicial Escenario',
  `VALOR FINAL` varchar(255) NOT NULL COMMENT 'Valor Final Escenario',
  `USUARIO` bigint(20) NOT NULL COMMENT 'Usuario Log',
  `FECHA` datetime NOT NULL COMMENT 'Fecha Log Proyecto'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radiografia_departamentos`
--

CREATE TABLE `radiografia_departamentos` (
  `ID` int(3) NOT NULL COMMENT 'ID Radiografia Dptos.',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Dpto.',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Departamento'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `radiografia_departamentos`
--

INSERT INTO `radiografia_departamentos` (`ID`, `NOMBRE`, `ESTADO`) VALUES
(1, 'AMAZONAS', 1),
(2, 'ANTIOQUIA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radiografia_mensajes`
--

CREATE TABLE `radiografia_mensajes` (
  `ID` bigint(20) NOT NULL COMMENT 'ID radiografia de mensajes',
  `NODO` varchar(100) NOT NULL COMMENT 'Nombre del Nodo',
  `MATRIZ` bigint(20) DEFAULT '0' COMMENT 'Numero de Matriz',
  `CIUDAD` int(3) DEFAULT NULL COMMENT 'Ciudad del Nodo relacion ID Radiografia Municipios',
  `DIVISION` bigint(20) DEFAULT NULL COMMENT 'Division de RADIOGRAFIA NODOS',
  `MERNSAJE` text NOT NULL COMMENT 'Mensaje del Nodo ',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de Mensaje Nodo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radiografia_municipios`
--

CREATE TABLE `radiografia_municipios` (
  `ID` int(3) NOT NULL COMMENT 'ID radiografia de Munic y Ciudades',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Municip.Ciudad',
  `DEPARTAMENTO` int(3) NOT NULL COMMENT 'ID del Departamento',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Municipio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `radiografia_municipios`
--

INSERT INTO `radiografia_municipios` (`ID`, `NOMBRE`, `DEPARTAMENTO`, `ESTADO`) VALUES
(1, 'LETICIA', 1, 1),
(2, 'PUERTO NARI?O', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radiografia_nodos`
--

CREATE TABLE `radiografia_nodos` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de Nodo',
  `NODO` varchar(75) NOT NULL COMMENT 'Nodo',
  `CMTS` varchar(75) NOT NULL COMMENT 'CMTS',
  `NOMBRE_NODO` varchar(120) NOT NULL COMMENT 'Nombre del Nodo',
  `COMUNIDAD` varchar(5) NOT NULL COMMENT 'Comunidad',
  `NOMBRE_COMUNIDAD` varchar(120) NOT NULL COMMENT 'Nombre Comunidad',
  `DEPARTAMENTO` varchar(120) NOT NULL COMMENT 'Departamento',
  `DANE` bigint(20) NOT NULL DEFAULT '0',
  `ESTATUS` varchar(120) NOT NULL DEFAULT 'NO DEFINIDO' COMMENT 'Estatus',
  `RED` varchar(75) NOT NULL COMMENT 'Red Tipo Uni - Bi',
  `DIVISION` varchar(75) NOT NULL COMMENT 'División',
  `HHPP` bigint(20) NOT NULL DEFAULT '0' COMMENT 'HHPP cantidades',
  `HOGARES` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Hogares cantidades',
  `SERVICIOS` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Servicios cantidades',
  `TIPOLOGIA` varchar(75) NOT NULL COMMENT 'Tipologia',
  `ID_DIVISION` int(10) NOT NULL DEFAULT '0' COMMENT 'Id de la Division',
  `AREA` varchar(120) NOT NULL COMMENT 'Area',
  `ID_ZONA` int(10) NOT NULL DEFAULT '0' COMMENT 'Id Zona',
  `ZONA` varchar(120) NOT NULL COMMENT 'Nombre Zona',
  `ID_DISTRITO` varchar(75) NOT NULL DEFAULT '0' COMMENT 'ID del distrito',
  `DISTRITO` varchar(120) NOT NULL COMMENT 'Nombre del Distrito',
  `ID_GESTION` varchar(75) NOT NULL DEFAULT '0' COMMENT 'ID Gestión',
  `CODIGO_ALIADO` varchar(10) NOT NULL COMMENT 'Codigo Aliado',
  `ALIADO` varchar(120) NOT NULL COMMENT 'Nombre del Aliado',
  `TIPOLOGIA_RED` varchar(10) NOT NULL COMMENT 'Tipologia RED',
  `ESTADO_NODO` varchar(10) NOT NULL COMMENT 'Estado Nodo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `radiografia_nodos`
--

INSERT INTO `radiografia_nodos` (`ID`, `NODO`, `CMTS`, `NOMBRE_NODO`, `COMUNIDAD`, `NOMBRE_COMUNIDAD`, `DEPARTAMENTO`, `DANE`, `ESTATUS`, `RED`, `DIVISION`, `HHPP`, `HOGARES`, `SERVICIOS`, `TIPOLOGIA`, `ID_DIVISION`, `AREA`, `ID_ZONA`, `ZONA`, `ID_DISTRITO`, `DISTRITO`, `ID_GESTION`, `CODIGO_ALIADO`, `ALIADO`, `TIPOLOGIA_RED`, `ESTADO_NODO`) VALUES
(1, 'ADCOBO', 'ADYASENCIA', 'AD COMBITA BOYACA', 'COM', 'COMBITA', 'BOYACA', 15204000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 162, '1.6.2 CUNDINAMARCA', '132', '1.6.2.1 ESPINAL', '1312', 'CL', 'CLARO', 'BID', 'NAC'),
(2, 'ADCOTO', 'ADYASENCIA', 'AD COELLO TOLIMA', 'CEL', 'COELLO', 'TOLIMA', 73200000, 'ADYASENCIA', 'ADYASENCIA', '3. OCCIDENTE', 0, 0, 0, 'BID', 3, '3.3 OCCIDENTE CAFETERO', 331, '3.3.1 IBAGUE-HONDA', '399', '3.9.9.9 DISTRITO INACTIVO', '3999', 'CL', 'CLARO', 'BID', 'NAC'),
(3, 'ADNICU', 'ADYASENCIA', 'AD NILO CUNDIN', 'NIL', 'NILO', 'CUNDINAMARCA', 25488000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 162, '1.6.2 CUNDINAMARCA', '199', '1.9.9.9 DISTRITO INACTIVO', '1999', 'CL', 'CLARO', 'BID', 'NAC'),
(4, 'ADPABO', 'ADYASENCIA', 'AD PAIPA BOYACA', 'PAI', 'PAIPA', 'BOYACA', 15516000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 162, '1.6.2 CUNDINAMARCA', '132', '1.6.2.1 ESPINAL', '1312', 'CL', 'CLARO', 'BID', 'NAC'),
(5, 'ADRICU', 'ADYASENCIA', 'AD RICAURTE CUNDIN', 'RIT', 'RICAURTE', 'CUNDINAMARCA', 25612000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 162, '1.6.2 CUNDINAMARCA', '03D', '1.6.2.3 GIRARDOT', '1312', 'CL', 'CLARO', 'BID', 'NAC'),
(6, 'ADSICU', 'ADYASENCIA', 'AD SIBATE CUNDIN', 'SBT', 'SIBATE', 'CUNDINAMARCA', 25740000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.1 BOGOTÁ OCCIDENTE', 111, '1.1.1 SOACHA', '14X', '1.1.1.4 SIBATE', '1999', 'TC', 'TELCOS INGENIERIA S.A.', 'BID', 'NAC'),
(7, 'ADTIBO', 'ADYASENCIA', 'AD TIBASOSA BOYAC', 'TIB', 'TIBASOSA', 'BOYACA', 15806000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.2 BOGOTÁ NORTE', 125, '1.2.5 BOYACA', '199', '1.9.9.9 DISTRITO INACTIVO', '1999', 'CL', 'CLARO', 'BID', 'NAC'),
(8, 'ADTICU', 'ADYASENCIA', 'AD TIBACUY CUNDIN', 'TBC', 'TIBACUY', 'CUNDINAMARCA', 25805000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 162, '1.6.2 CUNDINAMARCA', '199', '1.9.9.9 DISTRITO INACTIVO', '1999', 'CL', 'CLARO', 'BID', 'NAC'),
(9, 'ADTUBO', 'ADYASENCIA', 'AD TUTA BOYACA', 'TUT', 'TUTA', 'BOYACA', 15837000, 'ADYASENCIA', 'ADYASENCIA', '1. CENTRO', 0, 0, 0, 'BID', 1, '1.6 BOGOTÁ CENTRAL', 163, '1.6.3 CHAPINERO', '20B', '1.6.3.3 GUAVIARE', '1999', 'CL', 'CLARO', 'BID', 'NAC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radiografia_nodos_pendiente`
--

CREATE TABLE `radiografia_nodos_pendiente` (
  `ID` bigint(20) NOT NULL COMMENT 'ID del Registro',
  `NODO` bigint(20) NOT NULL COMMENT 'ID del Nodo dentro de la radigrafia de nodos para actualizar',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado de la Actualizacion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` bigint(20) NOT NULL COMMENT 'ID de Usuario',
  `PASSWORD` varchar(255) NOT NULL COMMENT 'Contraseña del Usuario',
  `NOMBRE` varchar(100) NOT NULL COMMENT 'Nombre del Usuario',
  `USUARIO_RR` varchar(255) DEFAULT NULL COMMENT 'Usuario RR',
  `CORREO` varchar(150) NOT NULL COMMENT 'Correo del Usuario',
  `EMPRESA` varchar(100) NOT NULL COMMENT 'Empresa del Usuario',
  `CARGO` int(2) NOT NULL COMMENT 'ID Cargo del Usuario',
  `ESTADO` int(2) NOT NULL COMMENT 'Estado del Usuario',
  `PERMISO` bigint(20) NOT NULL COMMENT 'ID del Permiso del Usuario',
  `CEDULA` varchar(100) NOT NULL COMMENT 'no. Cedula'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `PASSWORD`, `NOMBRE`, `USUARIO_RR`, `CORREO`, `EMPRESA`, `CARGO`, `ESTADO`, `PERMISO`, `CEDULA`) VALUES
(1, 'cb40dd606cfa58af70d3cef46feb91e38b9c78ba', 'Súper Root', 'Fix', 'alejo_fix@hotmail.com', 'CLARO', 1, 1, 1, '8008881'),
(2, 'cb40dd606cfa58af70d3cef46feb91e38b9c78ba', 'Alejandro Montenegro', 'JMONTENEGR', 'jorge.montenegro.t@claro.com.co', 'CLARO', 1, 1, 2, '79696444');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ccaa_motivo`
--
ALTER TABLE `ccaa_motivo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_CCAA_MOTIVO_CCAA_MOTIVO_TIPO1_idx` (`TIPO`),
  ADD KEY `fk_CCAA_MOTIVO_CCAA_MOTIVO_RAZON1_idx` (`RAZON`);

--
-- Indices de la tabla `ccaa_motivo_razon`
--
ALTER TABLE `ccaa_motivo_razon`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_CCAA_MOTIVO_RAZON_estados1_idx` (`ESTADO`),
  ADD KEY `fk_CCAA_MOTIVO_RAZON_CCAA_MOTIVO_TIPO1_idx` (`TIPO`);

--
-- Indices de la tabla `ccaa_motivo_tipo`
--
ALTER TABLE `ccaa_motivo_tipo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_CCAA_MOTIVO_TIPO_estados1_idx` (`ESTADO`),
  ADD KEY `fk_CCAA_MOTIVO_TIPO_CCAA_SERVICIO1_idx` (`SERVICIO`);

--
-- Indices de la tabla `ccaa_servicio`
--
ALTER TABLE `ccaa_servicio`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `chat_mensaje`
--
ALTER TABLE `chat_mensaje`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_chat_mensaje_usuarios1_idx` (`ID_USUARIO`),
  ADD KEY `fk_chat_mensaje_chat_tipo1_idx` (`TIPO`),
  ADD KEY `fk_chat_mensaje_chat_salas1_idx` (`ID_SALA`),
  ADD KEY `fk_chat_mensaje_usuarios2_idx` (`DE`),
  ADD KEY `fk_chat_mensaje_usuarios3_idx` (`PARA`);

--
-- Indices de la tabla `chat_perfil`
--
ALTER TABLE `chat_perfil`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_chat_perfil_estados1_idx` (`ESTADO`),
  ADD KEY `fk_chat_perfil_permisos_nombre1_idx` (`PERMISO_NOMBRE`);

--
-- Indices de la tabla `chat_salas`
--
ALTER TABLE `chat_salas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_chat_salas_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `chat_salas_has_chat_perfil`
--
ALTER TABLE `chat_salas_has_chat_perfil`
  ADD PRIMARY KEY (`chat_perfil_ID`,`chat_salas_ID`),
  ADD KEY `fk_chat_salas_has_chat_perfil_chat_perfil1_idx` (`chat_perfil_ID`),
  ADD KEY `fk_chat_salas_has_chat_perfil_chat_salas1_idx` (`chat_salas_ID`);

--
-- Indices de la tabla `chat_tipo`
--
ALTER TABLE `chat_tipo`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `comunicacion_avisos`
--
ALTER TABLE `comunicacion_avisos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_comunicacion_usuarios1_idx` (`USUARIO`),
  ADD KEY `fk_comunicacion_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `comunicacion_avisos_log`
--
ALTER TABLE `comunicacion_avisos_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_COMUNICACION_AVISOS_LOG_usuarios1_idx` (`USUARIO`);

--
-- Indices de la tabla `comunicacion_aviso_detalle`
--
ALTER TABLE `comunicacion_aviso_detalle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_COMUNICACION_AVISO_DETALLE_estados1_idx` (`ESTADO`),
  ADD KEY `fk_COMUNICACION_AVISO_DETALLE_usuarios1_idx` (`USUARIO`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `formularios_log`
--
ALTER TABLE `formularios_log`
  ADD KEY `fk_FORMULARIOS_LOG_FORMULARIO_CONTRASENA1_idx` (`ID`);

--
-- Indices de la tabla `formulario_contrasena`
--
ALTER TABLE `formulario_contrasena`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_FORMULARIO_CONTRASENA_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `guiones_plantilla`
--
ALTER TABLE `guiones_plantilla`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_PLANTILLA_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `guiones_razon_averia`
--
ALTER TABLE `guiones_razon_averia`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_RAZON_AVERIA_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `guiones_registro`
--
ALTER TABLE `guiones_registro`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_usuarios1_idx` (`USUARIO`),
  ADD KEY `fk_GUIONES_REGISTRO_GUIONES_TIPO1_idx` (`TIPO`),
  ADD KEY `fk_GUIONES_REGISTRO_GUIONES_RAZON_AVERIA1_idx` (`RAZON`);

--
-- Indices de la tabla `guiones_registro_hfc`
--
ALTER TABLE `guiones_registro_hfc`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_HFC_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_HFC_PRIORIDADES1_idx` (`PRIORIDAD`);

--
-- Indices de la tabla `guiones_registro_matriz`
--
ALTER TABLE `guiones_registro_matriz`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_MATRIZ_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_MATRIZ_PRIORIDADES1_idx` (`PRIORIDAD`);

--
-- Indices de la tabla `guiones_registro_plaforma_soporte`
--
ALTER TABLE `guiones_registro_plaforma_soporte`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTROL_PLAFORMA_SOPORTE_GUIONES_REGISTRO1_idx` (`REGISTRO`);

--
-- Indices de la tabla `guiones_registro_plataforma`
--
ALTER TABLE `guiones_registro_plataforma`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_PLATAFORMA_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_PLATAFORMA_PRIORIDADES1_idx` (`PRIORIDAD`);

--
-- Indices de la tabla `guiones_registro_plataforma_equipos`
--
ALTER TABLE `guiones_registro_plataforma_equipos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_PLATAFORMA_EQUIPOS_GUIONES_REGISTRO1_idx` (`REGISTRO`);

--
-- Indices de la tabla `guiones_registro_ubicacion`
--
ALTER TABLE `guiones_registro_ubicacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_UBICACION_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `guiones_registro_ubicacion_ciudad`
--
ALTER TABLE `guiones_registro_ubicacion_ciudad`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_CIUDAD_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_CIUDAD_RADIOGRAFIA_NODOS1_idx` (`NODO`);

--
-- Indices de la tabla `guiones_registro_ubicacion_nodo`
--
ALTER TABLE `guiones_registro_ubicacion_nodo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_UBICACION_NODO_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_UBICACION_NODO_RADIOGRAFIA_NODOS1_idx` (`NODO`);

--
-- Indices de la tabla `guiones_registro_ubicacion_regional`
--
ALTER TABLE `guiones_registro_ubicacion_regional`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_REGISTRO_UBICACION_REGIONAL_GUIONES_REGISTRO1_idx` (`REGISTRO`),
  ADD KEY `fk_GUIONES_REGISTRO_UBICACION_REGIONAL_LISTA_REGIONALES1_idx` (`REGIONAL`);

--
-- Indices de la tabla `guiones_tipo`
--
ALTER TABLE `guiones_tipo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_TIPO_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `guiones_tipo_afectacion`
--
ALTER TABLE `guiones_tipo_afectacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_TIPO_AFECTACION_estados1_idx` (`ESTADO`),
  ADD KEY `fk_GUIONES_TIPO_AFECTACION_GUIONES_TIPO_SERVICIO1_idx` (`SERVICIO`);

--
-- Indices de la tabla `guiones_tipo_servicio`
--
ALTER TABLE `guiones_tipo_servicio`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_GUIONES_TIPO_SERVICIO_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_cablemodem`
--
ALTER TABLE `lista_cablemodem`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_lista_cablemodem_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_cargo`
--
ALTER TABLE `lista_cargo`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `lista_decodificadores`
--
ALTER TABLE `lista_decodificadores`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_lista_decodificadores_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_marcaciones`
--
ALTER TABLE `lista_marcaciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_lista_marcaciones_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_referencia_decos_rr`
--
ALTER TABLE `lista_referencia_decos_rr`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_LISTA_REFRR_DECOS_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_regionales`
--
ALTER TABLE `lista_regionales`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_LISTA_REGIONALES_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `lista_softswich`
--
ALTER TABLE `lista_softswich`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_lista_decodificadores_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `log_tabla_select`
--
ALTER TABLE `log_tabla_select`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_LOG_TABLA_SELECT_usuarios1_idx` (`USUARIO`);

--
-- Indices de la tabla `permisos_base`
--
ALTER TABLE `permisos_base`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PERMISOS_MODULOS_ESTADOS1_idx` (`ESTADO`);

--
-- Indices de la tabla `permisos_nombre`
--
ALTER TABLE `permisos_nombre`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PERMISOS_NOMBRE_ESTADOS1_idx` (`ESTADO`);

--
-- Indices de la tabla `permisos_seleccion`
--
ALTER TABLE `permisos_seleccion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PERMISOS_SELECCION_PERMISOS_NOMBRE1_idx` (`NOMBRE_ID`),
  ADD KEY `fk_PERMISOS_SELECCION_PERMISOS_MODULOS1_idx` (`MODULOS_ID`),
  ADD KEY `fk_PERMISOS_SELECCION_PERMISOS_BASE1_idx` (`BASE_ID`);

--
-- Indices de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PRIORIDADES_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_LISTA_PROYECTOS_estados1_idx` (`ESTADO`),
  ADD KEY `fk_PROYECTOS_usuarios1_idx` (`USUARIO_APERTURA`),
  ADD KEY `fk_PROYECTOS_usuarios2_idx` (`USUARIO_CIERRE`);

--
-- Indices de la tabla `proyectos_fases`
--
ALTER TABLE `proyectos_fases`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PROYECTOS_FASES_PROYECTOS1_idx` (`ID_PROYECTOS`),
  ADD KEY `fk_PROYECTOS_FASES_PROYECTOS_USUARIO_ASIGANDO1_idx` (`USUARIO_ASIGNADO`),
  ADD KEY `fk_PROYECTOS_FASES_PROYECTOS_OBJETIVOS1_idx` (`ID_OBJETIVOS`);

--
-- Indices de la tabla `proyectos_objetivos`
--
ALTER TABLE `proyectos_objetivos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PROYECTOS_OBJETIVOS_PROYECTOS1_idx` (`ID_PROYECTOS`),
  ADD KEY `fk_PROYECTOS_OBJETIVOS_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `proyectos_usuario_asigando`
--
ALTER TABLE `proyectos_usuario_asigando`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PROYECTOS_USUARIO_ASIGANDO_PROYECTOS1_idx` (`ID_PROYECTOS`),
  ADD KEY `fk_PROYECTOS_USUARIO_ASIGANDO_usuarios1_idx` (`USUARIO`),
  ADD KEY `fk_PROYECTOS_USUARIO_ASIGANDO_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `proyecto_dias_festivos`
--
ALTER TABLE `proyecto_dias_festivos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PROYECTO_DIAS_FESTIVOS_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `proyecto_escenario`
--
ALTER TABLE `proyecto_escenario`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `proyecto_log`
--
ALTER TABLE `proyecto_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_PROYECTO_LOG_PROYECTOS1_idx` (`ID_PROYECTO`),
  ADD KEY `fk_PROYECTO_LOG_PROYECTO_ESCENARIO1_idx` (`ID_ESCENARIO`),
  ADD KEY `fk_PROYECTO_LOG_usuarios1_idx` (`USUARIO`);

--
-- Indices de la tabla `radiografia_departamentos`
--
ALTER TABLE `radiografia_departamentos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_RADIOGRAFIA_DEPARTAMENTOS_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `radiografia_mensajes`
--
ALTER TABLE `radiografia_mensajes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_RADIOGRAFIA_MENSAJES_RADIOGRAFIA_MUNICIPIOS1_idx` (`CIUDAD`),
  ADD KEY `fk_RADIOGRAFIA_MENSAJES_RADIOGRAFIA_NODOS1_idx` (`DIVISION`),
  ADD KEY `fk_RADIOGRAFIA_MENSAJES_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `radiografia_municipios`
--
ALTER TABLE `radiografia_municipios`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_RADIOGRAFIA_MUNICIPIOS_estados1_idx` (`ESTADO`),
  ADD KEY `fk_RADIOGRAFIA_MUNICIPIOS_RADIOGRAFIA_DEPARTAMENTOS1_idx` (`DEPARTAMENTO`);

--
-- Indices de la tabla `radiografia_nodos`
--
ALTER TABLE `radiografia_nodos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `radiografia_nodos_pendiente`
--
ALTER TABLE `radiografia_nodos_pendiente`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_RADIOGRAFIA_NODOS_PENDIENTE_RADIOGRAFIA_NODOS1_idx` (`NODO`),
  ADD KEY `fk_RADIOGRAFIA_NODOS_PENDIENTE_estados1_idx` (`ESTADO`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_USUARIOS_ESTADOS_idx` (`ESTADO`),
  ADD KEY `fk_USUARIOS_PERMISOS_NOMBRE1_idx` (`PERMISO`),
  ADD KEY `fk_usuarios_lista_cargo1_idx` (`CARGO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ccaa_motivo`
--
ALTER TABLE `ccaa_motivo`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Motivos CCAA';
--
-- AUTO_INCREMENT de la tabla `ccaa_motivo_razon`
--
ALTER TABLE `ccaa_motivo_razon`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de las Razones', AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT de la tabla `chat_mensaje`
--
ALTER TABLE `chat_mensaje`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `chat_perfil`
--
ALTER TABLE `chat_perfil`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID de  Perfil de Chat', AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `chat_salas`
--
ALTER TABLE `chat_salas`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `chat_tipo`
--
ALTER TABLE `chat_tipo`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT COMMENT 'ID del Tipo de Chat', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `comunicacion_avisos`
--
ALTER TABLE `comunicacion_avisos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de Registro';
--
-- AUTO_INCREMENT de la tabla `comunicacion_avisos_log`
--
ALTER TABLE `comunicacion_avisos_log`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Log';
--
-- AUTO_INCREMENT de la tabla `comunicacion_aviso_detalle`
--
ALTER TABLE `comunicacion_aviso_detalle`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID detalle ', AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT COMMENT 'ID del Estado', AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `guiones_plantilla`
--
ALTER TABLE `guiones_plantilla`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de la plantilla', AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `guiones_registro`
--
ALTER TABLE `guiones_registro`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro ';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_hfc`
--
ALTER TABLE `guiones_registro_hfc`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Registro HFC';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_matriz`
--
ALTER TABLE `guiones_registro_matriz`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro de Matriz';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_plaforma_soporte`
--
ALTER TABLE `guiones_registro_plaforma_soporte`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de los procesos de soporte';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_plataforma`
--
ALTER TABLE `guiones_registro_plataforma`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro de plataforma';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_plataforma_equipos`
--
ALTER TABLE `guiones_registro_plataforma_equipos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro de equipos en los guiones';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_ubicacion`
--
ALTER TABLE `guiones_registro_ubicacion`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Id de registro de Ubicación', AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `guiones_registro_ubicacion_ciudad`
--
ALTER TABLE `guiones_registro_ubicacion_ciudad`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Guiones Registro Ciudad';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_ubicacion_nodo`
--
ALTER TABLE `guiones_registro_ubicacion_nodo`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID registro nodo';
--
-- AUTO_INCREMENT de la tabla `guiones_registro_ubicacion_regional`
--
ALTER TABLE `guiones_registro_ubicacion_regional`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Guiones registro ubicación Regional';
--
-- AUTO_INCREMENT de la tabla `lista_cablemodem`
--
ALTER TABLE `lista_cablemodem`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID de Cablemodem', AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `lista_decodificadores`
--
ALTER TABLE `lista_decodificadores`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Decodificador', AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT de la tabla `lista_marcaciones`
--
ALTER TABLE `lista_marcaciones`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Lista de Marcaciones', AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT de la tabla `lista_referencia_decos_rr`
--
ALTER TABLE `lista_referencia_decos_rr`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Referencia DecosRR', AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `lista_regionales`
--
ALTER TABLE `lista_regionales`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Id lista Regionales', AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `lista_softswich`
--
ALTER TABLE `lista_softswich`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Softswich', AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `log_tabla_select`
--
ALTER TABLE `log_tabla_select`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del LOG Select', AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `permisos_base`
--
ALTER TABLE `permisos_base`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Tipo de Permiso', AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Modulo', AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `permisos_nombre`
--
ALTER TABLE `permisos_nombre`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de Nombre del Permiso', AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `permisos_seleccion`
--
ALTER TABLE `permisos_seleccion`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Permiso de Seleccion', AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Proyecto';
--
-- AUTO_INCREMENT de la tabla `proyectos_fases`
--
ALTER TABLE `proyectos_fases`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de las Fases';
--
-- AUTO_INCREMENT de la tabla `proyectos_objetivos`
--
ALTER TABLE `proyectos_objetivos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de los Objetivos';
--
-- AUTO_INCREMENT de la tabla `proyectos_usuario_asigando`
--
ALTER TABLE `proyectos_usuario_asigando`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Usuarios Asignados al Proyecto';
--
-- AUTO_INCREMENT de la tabla `proyecto_dias_festivos`
--
ALTER TABLE `proyecto_dias_festivos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID días festivos';
--
-- AUTO_INCREMENT de la tabla `proyecto_escenario`
--
ALTER TABLE `proyecto_escenario`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Lista escenarios Log', AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `radiografia_departamentos`
--
ALTER TABLE `radiografia_departamentos`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID Radiografia Dptos.', AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `radiografia_mensajes`
--
ALTER TABLE `radiografia_mensajes`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID radiografia de mensajes';
--
-- AUTO_INCREMENT de la tabla `radiografia_municipios`
--
ALTER TABLE `radiografia_municipios`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID radiografia de Munic y Ciudades', AUTO_INCREMENT=1103;
--
-- AUTO_INCREMENT de la tabla `radiografia_nodos`
--
ALTER TABLE `radiografia_nodos`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de Nodo', AUTO_INCREMENT=14668;
--
-- AUTO_INCREMENT de la tabla `radiografia_nodos_pendiente`
--
ALTER TABLE `radiografia_nodos_pendiente`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del Registro';
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de Usuario', AUTO_INCREMENT=88;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ccaa_motivo`
--
ALTER TABLE `ccaa_motivo`
  ADD CONSTRAINT `fk_CCAA_MOTIVO_CCAA_MOTIVO_RAZON1` FOREIGN KEY (`RAZON`) REFERENCES `ccaa_motivo_razon` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CCAA_MOTIVO_CCAA_MOTIVO_TIPO1` FOREIGN KEY (`TIPO`) REFERENCES `ccaa_motivo_tipo` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ccaa_motivo_razon`
--
ALTER TABLE `ccaa_motivo_razon`
  ADD CONSTRAINT `fk_CCAA_MOTIVO_RAZON_CCAA_MOTIVO_TIPO1` FOREIGN KEY (`TIPO`) REFERENCES `ccaa_motivo_tipo` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CCAA_MOTIVO_RAZON_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ccaa_motivo_tipo`
--
ALTER TABLE `ccaa_motivo_tipo`
  ADD CONSTRAINT `fk_CCAA_MOTIVO_TIPO_CCAA_SERVICIO1` FOREIGN KEY (`SERVICIO`) REFERENCES `ccaa_servicio` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CCAA_MOTIVO_TIPO_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `chat_mensaje`
--
ALTER TABLE `chat_mensaje`
  ADD CONSTRAINT `fk_chat_mensaje_chat_salas1` FOREIGN KEY (`ID_SALA`) REFERENCES `chat_salas` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_mensaje_chat_tipo1` FOREIGN KEY (`TIPO`) REFERENCES `chat_tipo` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_mensaje_usuarios1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_mensaje_usuarios2` FOREIGN KEY (`DE`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_mensaje_usuarios3` FOREIGN KEY (`PARA`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `chat_perfil`
--
ALTER TABLE `chat_perfil`
  ADD CONSTRAINT `fk_chat_perfil_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_perfil_permisos_nombre1` FOREIGN KEY (`PERMISO_NOMBRE`) REFERENCES `permisos_nombre` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `chat_salas`
--
ALTER TABLE `chat_salas`
  ADD CONSTRAINT `fk_chat_salas_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `chat_salas_has_chat_perfil`
--
ALTER TABLE `chat_salas_has_chat_perfil`
  ADD CONSTRAINT `fk_chat_salas_has_chat_perfil_chat_perfil1` FOREIGN KEY (`chat_perfil_ID`) REFERENCES `chat_perfil` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_salas_has_chat_perfil_chat_salas1` FOREIGN KEY (`chat_salas_ID`) REFERENCES `chat_salas` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comunicacion_avisos`
--
ALTER TABLE `comunicacion_avisos`
  ADD CONSTRAINT `fk_comunicacion_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comunicacion_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comunicacion_avisos_log`
--
ALTER TABLE `comunicacion_avisos_log`
  ADD CONSTRAINT `fk_COMUNICACION_AVISOS_LOG_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comunicacion_aviso_detalle`
--
ALTER TABLE `comunicacion_aviso_detalle`
  ADD CONSTRAINT `fk_COMUNICACION_AVISO_DETALLE_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_COMUNICACION_AVISO_DETALLE_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `formularios_log`
--
ALTER TABLE `formularios_log`
  ADD CONSTRAINT `fk_FORMULARIOS_LOG_FORMULARIO_CONTRASENA1` FOREIGN KEY (`ID`) REFERENCES `formulario_contrasena` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `formulario_contrasena`
--
ALTER TABLE `formulario_contrasena`
  ADD CONSTRAINT `fk_FORMULARIO_CONTRASENA_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_plantilla`
--
ALTER TABLE `guiones_plantilla`
  ADD CONSTRAINT `fk_GUIONES_PLANTILLA_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_razon_averia`
--
ALTER TABLE `guiones_razon_averia`
  ADD CONSTRAINT `fk_GUIONES_RAZON_AVERIA_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro`
--
ALTER TABLE `guiones_registro`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_GUIONES_RAZON_AVERIA1` FOREIGN KEY (`RAZON`) REFERENCES `guiones_razon_averia` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_GUIONES_TIPO1` FOREIGN KEY (`TIPO`) REFERENCES `guiones_tipo` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_hfc`
--
ALTER TABLE `guiones_registro_hfc`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_HFC_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_HFC_PRIORIDADES1` FOREIGN KEY (`PRIORIDAD`) REFERENCES `prioridades` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_matriz`
--
ALTER TABLE `guiones_registro_matriz`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_MATRIZ_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_MATRIZ_PRIORIDADES1` FOREIGN KEY (`PRIORIDAD`) REFERENCES `prioridades` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_plaforma_soporte`
--
ALTER TABLE `guiones_registro_plaforma_soporte`
  ADD CONSTRAINT `fk_GUIONES_REGISTROL_PLAFORMA_SOPORTE_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_plataforma`
--
ALTER TABLE `guiones_registro_plataforma`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_PLATAFORMA_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_PLATAFORMA_PRIORIDADES1` FOREIGN KEY (`PRIORIDAD`) REFERENCES `prioridades` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_plataforma_equipos`
--
ALTER TABLE `guiones_registro_plataforma_equipos`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_PLATAFORMA_EQUIPOS_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_ubicacion`
--
ALTER TABLE `guiones_registro_ubicacion`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_UBICACION_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_ubicacion_ciudad`
--
ALTER TABLE `guiones_registro_ubicacion_ciudad`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_CIUDAD_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_CIUDAD_RADIOGRAFIA_NODOS1` FOREIGN KEY (`NODO`) REFERENCES `radiografia_nodos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_ubicacion_nodo`
--
ALTER TABLE `guiones_registro_ubicacion_nodo`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_UBICACION_NODO_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_UBICACION_NODO_RADIOGRAFIA_NODOS1` FOREIGN KEY (`NODO`) REFERENCES `radiografia_nodos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_registro_ubicacion_regional`
--
ALTER TABLE `guiones_registro_ubicacion_regional`
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_UBICACION_REGIONAL_GUIONES_REGISTRO1` FOREIGN KEY (`REGISTRO`) REFERENCES `guiones_registro` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_REGISTRO_UBICACION_REGIONAL_LISTA_REGIONALES1` FOREIGN KEY (`REGIONAL`) REFERENCES `lista_regionales` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_tipo`
--
ALTER TABLE `guiones_tipo`
  ADD CONSTRAINT `fk_GUIONES_TIPO_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_tipo_afectacion`
--
ALTER TABLE `guiones_tipo_afectacion`
  ADD CONSTRAINT `fk_GUIONES_TIPO_AFECTACION_GUIONES_TIPO_SERVICIO1` FOREIGN KEY (`SERVICIO`) REFERENCES `guiones_tipo_servicio` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GUIONES_TIPO_AFECTACION_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guiones_tipo_servicio`
--
ALTER TABLE `guiones_tipo_servicio`
  ADD CONSTRAINT `fk_GUIONES_TIPO_SERVICIO_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_cablemodem`
--
ALTER TABLE `lista_cablemodem`
  ADD CONSTRAINT `fk_lista_cablemodem_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_decodificadores`
--
ALTER TABLE `lista_decodificadores`
  ADD CONSTRAINT `fk_lista_decodificadores_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_marcaciones`
--
ALTER TABLE `lista_marcaciones`
  ADD CONSTRAINT `fk_lista_marcaciones_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_referencia_decos_rr`
--
ALTER TABLE `lista_referencia_decos_rr`
  ADD CONSTRAINT `fk_LISTA_REFRR_DECOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_regionales`
--
ALTER TABLE `lista_regionales`
  ADD CONSTRAINT `fk_LISTA_REGIONALES_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_softswich`
--
ALTER TABLE `lista_softswich`
  ADD CONSTRAINT `fk_lista_softswich_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `log_tabla_select`
--
ALTER TABLE `log_tabla_select`
  ADD CONSTRAINT `fk_LOG_TABLA_SELECT_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  ADD CONSTRAINT `fk_PERMISOS_MODULOS_ESTADOS1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos_nombre`
--
ALTER TABLE `permisos_nombre`
  ADD CONSTRAINT `fk_PERMISOS_NOMBRE_ESTADOS1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos_seleccion`
--
ALTER TABLE `permisos_seleccion`
  ADD CONSTRAINT `fk_PERMISOS_SELECCION_PERMISOS_BASE1` FOREIGN KEY (`BASE_ID`) REFERENCES `permisos_base` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PERMISOS_SELECCION_PERMISOS_MODULOS1` FOREIGN KEY (`MODULOS_ID`) REFERENCES `permisos_modulos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PERMISOS_SELECCION_PERMISOS_NOMBRE1` FOREIGN KEY (`NOMBRE_ID`) REFERENCES `permisos_nombre` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prioridades`
--
ALTER TABLE `prioridades`
  ADD CONSTRAINT `fk_PRIORIDADES_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `fk_LISTA_PROYECTOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_usuarios1` FOREIGN KEY (`USUARIO_APERTURA`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_usuarios2` FOREIGN KEY (`USUARIO_CIERRE`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyectos_fases`
--
ALTER TABLE `proyectos_fases`
  ADD CONSTRAINT `fk_PROYECTOS_FASES_PROYECTOS1` FOREIGN KEY (`ID_PROYECTOS`) REFERENCES `proyectos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_FASES_PROYECTOS_OBJETIVOS1` FOREIGN KEY (`ID_OBJETIVOS`) REFERENCES `proyectos_objetivos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_FASES_PROYECTOS_USUARIO_ASIGANDO1` FOREIGN KEY (`USUARIO_ASIGNADO`) REFERENCES `proyectos_usuario_asigando` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyectos_objetivos`
--
ALTER TABLE `proyectos_objetivos`
  ADD CONSTRAINT `fk_PROYECTOS_OBJETIVOS_PROYECTOS1` FOREIGN KEY (`ID_PROYECTOS`) REFERENCES `proyectos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_OBJETIVOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyectos_usuario_asigando`
--
ALTER TABLE `proyectos_usuario_asigando`
  ADD CONSTRAINT `fk_PROYECTOS_USUARIO_ASIGANDO_PROYECTOS1` FOREIGN KEY (`ID_PROYECTOS`) REFERENCES `proyectos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_USUARIO_ASIGANDO_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTOS_USUARIO_ASIGANDO_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyecto_dias_festivos`
--
ALTER TABLE `proyecto_dias_festivos`
  ADD CONSTRAINT `fk_PROYECTO_DIAS_FESTIVOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyecto_log`
--
ALTER TABLE `proyecto_log`
  ADD CONSTRAINT `fk_PROYECTO_LOG_PROYECTOS1` FOREIGN KEY (`ID_PROYECTO`) REFERENCES `proyectos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTO_LOG_PROYECTO_ESCENARIO1` FOREIGN KEY (`ID_ESCENARIO`) REFERENCES `proyecto_escenario` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PROYECTO_LOG_usuarios1` FOREIGN KEY (`USUARIO`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `radiografia_departamentos`
--
ALTER TABLE `radiografia_departamentos`
  ADD CONSTRAINT `fk_RADIOGRAFIA_DEPARTAMENTOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `radiografia_mensajes`
--
ALTER TABLE `radiografia_mensajes`
  ADD CONSTRAINT `fk_RADIOGRAFIA_MENSAJES_RADIOGRAFIA_MUNICIPIOS1` FOREIGN KEY (`CIUDAD`) REFERENCES `radiografia_municipios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RADIOGRAFIA_MENSAJES_RADIOGRAFIA_NODOS1` FOREIGN KEY (`DIVISION`) REFERENCES `radiografia_nodos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RADIOGRAFIA_MENSAJES_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `radiografia_municipios`
--
ALTER TABLE `radiografia_municipios`
  ADD CONSTRAINT `fk_RADIOGRAFIA_MUNICIPIOS_RADIOGRAFIA_DEPARTAMENTOS1` FOREIGN KEY (`DEPARTAMENTO`) REFERENCES `radiografia_departamentos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RADIOGRAFIA_MUNICIPIOS_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `radiografia_nodos_pendiente`
--
ALTER TABLE `radiografia_nodos_pendiente`
  ADD CONSTRAINT `fk_RADIOGRAFIA_NODOS_PENDIENTE_RADIOGRAFIA_NODOS1` FOREIGN KEY (`NODO`) REFERENCES `radiografia_nodos` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RADIOGRAFIA_NODOS_PENDIENTE_estados1` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_USUARIOS_ESTADOS` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USUARIOS_PERMISOS_NOMBRE1` FOREIGN KEY (`PERMISO`) REFERENCES `permisos_nombre` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_lista_cargo1` FOREIGN KEY (`CARGO`) REFERENCES `lista_cargo` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
