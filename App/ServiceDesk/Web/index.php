<?php
	
	/**
	 * Activar Sistema de Host Virtual de NeuralPHP
	 * 
	 * Solo se debe indicar valor booleano
	 * @var $HostNeuralPHP
	 * @param true: activar host virtual
	 * @param false: desactivar host virtual
	 */
	$HostNeuralPHP = false;
	
	/**
	 * Aplicacion Configurada
	 * 
	 * Se debe indicar el nombre de la aplicacion configurada
	 * para mostrarla en el host virtual
	 * @var $Aplicacion
	 * @param texto ingresado como se encuentra en el archivo de configuracion de accesos
	 */
	$Aplicacion = 'Predeterminado';
	
	/**
	 * Ruta fisica del Root
	 * 
	 * Se especifica la ruta del root para la carga de las librerias de NeuralPHP
	 * @var $RutaServidorBase
	 * @param texto indicando la ruta fisica del root de la aplicacion
	 * @example 'c:/servidor/www'
	 * @example '/opt/servidor/www'
	 */
	$RutaServidorBase = dirname(dirname(dirname(__DIR__)));
	
	/**
	 * NO MODIFICAR
	 * 
	 * Sistema base de enrutamiento y carga de librerias
	 */
	if($HostNeuralPHP == true AND is_bool($HostNeuralPHP) == true) {
		$FilePath = implode(DIRECTORY_SEPARATOR, array($RutaServidorBase, 'Neural', 'Cargador.php'));
		if(file_exists($FilePath) == true) {
			define('APPNEURALPHPHOST', $Aplicacion);
			require $FilePath;
			$App = new BootstrapVirtual($Aplicacion);
			$App->AppCargar();
		}
		else {
			echo 'EL NUCLEO DE NEURALPHP FRAMEWORK NO SE ENCUENTRA VALIDAR RUTA SERVIDOR BASE';
		}
	}