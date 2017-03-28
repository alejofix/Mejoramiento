-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-03-2017 a las 16:51:47
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mejoramientosqlfix`
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
(1, 'CONTRASENA DE DIFICIL RECORDACION', 1, 1);

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
(1, 'INFORME MOTIVO CAMBIO DE CONTRASENA', 1, 2);

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
(1, 'PLATAFORMA INTERNET', 1, 1);

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
        '][ID: ', NEW.ID, 
        '][NOMBRE: ', NEW.NOMBRE, 
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
        '][ID: ', NEW.ID, 
        '][NOMBRE: ', NEW.NOMBRE, 
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
(1, 'CONTRASEÑA DE DIFÍCIL RECORDACIÓN', 1);

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
(1, 'HFC', 'TRIPLEPLAY', 'PRIORIDAD 1', '<br />\r\n AVISO: {{ Datos.AVISO|e }}... \r\n SÍNTOMA: {{ ubicacion.NOMBRE|e|title }} {{ Datos.AVERIA|e }} {{ Datos.INTERMITENCIA == ''SI'' ? ''y/o Intermitencia'' }} . \r\n SERV.AFECTADOS: {{Datos.AFECTACION|e}} \r\n________________________________________________________________________________________________________________ \r\nGUIÓN:\r\n{% if Datos.RAZON == ''1'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por el hurto de un dispositivo en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == ''2'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por una ruptura de fibra en nuestra red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == ''3'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ocasionada por fallas en fluido eléctrico. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == ''4'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en nuestra plataforma de {{Datos.AFECTACION|e}} . Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == ''5'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla ajena a claro. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n{% if Datos.RAZON == ''6'' %}\r\n“Nuestro equipo técnico se encuentra trabajando para atender una falla en la red. Tiempo estimado de solución 5 Horas, durante este lapso su servicio puede presentar intermitencia o ausencia del mismo.”\r\n{% endif %} \r\n________________________________________________________________________________________________________________ \r\nPROCESO DE SOPORTE: Reportar la cuenta a través de la Página de Gerencia, \r\nNO REQUIERE VALIDAR VECINOS. \r\n________________________________________________________________________________________________________________ \r\nESCENARIOS DE MARCACIÓN: --1.Primera vez que el cliente se comunica por este aviso, use la marcación DIS-FMS \r\n--2.Cliente se ha comunicado en más de una ocasión por este aviso, use la marcación  DIS-AVF.\r\n<br /><br />', 1);

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
(1, 'HURTO', 1);

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
(1, 'SECTOR', 1);

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
(1, 'HFC', 1);

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
(1, 'SIN SEÑAL', 'Sin_Señal', 1, 1);

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
(1, 'TRIPLE PLAY', 1);

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
(1, 'Motorola', 'SBG900', '2.1.18.0-NOSH', 1);

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
(1, 'Motorola', 'General', 1);

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
(1, 'OPE', 'COV', 'Confirmación De Visita', 1);

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
(1, 'DVB', 'SKYWORTH', 'C7000NX', 'DBV - SKY', 1);

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
(1, 'REGIONAL ANTIOQUIA', 1);

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
(1, 'SAFARI', 'BOG03', 1);

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
(2, 'ANTIOQUIA', 1),
(3, 'ARAUCA', 1),
(4, 'ATL?NTICO', 1),
(5, 'BOL?VAR', 1),
(6, 'BOYAC?', 1),
(7, 'CALDAS', 1),
(8, 'CAQUET?', 1),
(9, 'CASANARE', 1),
(10, 'CAUCA', 1),
(11, 'CESAR', 1),
(12, 'CHOC?', 1),
(13, 'C?RDOBA', 1),
(14, 'CUNDINAMARCA', 1),
(15, 'G?AINIA', 1),
(16, 'GUAVIARE', 1),
(17, 'HUILA', 1),
(18, 'LA GUAJIRA', 1),
(19, 'MAGDALENA', 1),
(20, 'META', 1),
(21, 'NARI?O', 1),
(22, 'NORTE DE SANTANDER', 1),
(23, 'PUTUMAYO', 1),
(24, 'QUINDIO', 1),
(25, 'RISARALDA', 1),
(26, 'SAN ANDR?S Y PROVIDENCIA', 1),
(27, 'SANTANDER', 1),
(28, 'SUCRE', 1),
(29, 'TOLIMA', 1),
(30, 'VALLE DEL CAUCA', 1),
(31, 'VAUP?S', 1),
(32, 'VICHADA', 1);

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
(1, 'LETICIA', 1, 1);

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
(1, 'UME010', 'SIN INFO', 'ARANJUEZ', 'UME', 'MEDELLIN', 'ANTIOQUIA', 5001000, 'UNIDIRECCIONAL', 'UNIDIRECCIONAL', '2. NORTE', 0, 3, 3, 'UNI', 2, '2.6 MEDELLIN-ANTIOQUIA', 262, '2.6.2 MEDELLÍN ZONA CENTRO', '26W', '2.6.2.2 DISTRITO 2', '2538', 'IN', 'INMEL INGENIERIA S.A.S', 'UNI', 'ACT');

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
(1, 'cb40dd606cfa58af70d3cef46feb91e38b9c78ba', 'Súper Root Pruebas', 'Fix', 'alejo_fix@hotmail.com', 'CLARO', 1, 1, 1, '8008881');

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
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de las Razones', AUTO_INCREMENT=86;
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
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del LOG Select';
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
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de Usuario', AUTO_INCREMENT=83;
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
