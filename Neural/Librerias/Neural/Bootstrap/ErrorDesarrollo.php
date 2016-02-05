<?php
	
	/**
	 * NeuralPHP Framework
	 * Marco de trabajo para aplicaciones web.
	 * 
	 * @author Zyos (Carlos Parra) <Neural.Framework@gmail.com>
	 * @copyright 2006-2014 NeuralPHP Framework
	 * @license GNU General Public License as published by the Free Software Foundation; either version 2 of the License. 
	 * @license Incluida licencia carpeta de Informacion 
	 * @see http://neuralphp.url.ph/
	 * @version 3.0
	 * 
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 */
	
	class ErrorDesarrollo extends ErrorUsuario {
		
		/**
		 * Carpeta contenedora de la plantilla
		 * @access private
		 */
		private static $FolderAlertas = 'Alertas';
		
		/**
		 * Carpeta donde se encuentran los errores
		 * @access private
		 */
		private static $FolderErrores = 'Errores';
		
		/**
		 * Matriz de parametros de la plantilla
		 * @access private
		 */
		private static $MatrizParametros = array('Plantilla', 'Titulo', 'Informacion', 'Aplicacion', 'Modulo', 'Controlador', 'Metodo');
		
		/**
		 * Matriz de variables
		 * @access private
		 */
		public $Variables = array();
		
		/**
		 * Constante de separador web
		 * @access private
		 */
		const WEB_SEPARADOR = '/';
		
		/**
		 * Metodo Protegido
		 * AsignarValor($Nombre = false, $Valor = false)
		 * 
		 * Asigna los valores que se mostraran en la plantilla
		 * @access private
		 */
		protected function AsignarValor($Nombre = false, $Valor = false) {
			$this->Variables[$Nombre] = $Valor;
		}
		
		/**
		 * Metodo Privado
		 * EjecutarPlantillaDesarrollo($Parametros)
		 * 
		 * Carga la plantilla correspondiente
		 * @access private
		 */
		private function EjecutarPlantillaDesarrollo($Parametros = array()) {
			self::ValidarEjecutarTwig();
			$TwigLoader = new Twig_Loader_Filesystem(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, self::$FolderErrores, self::$FolderAlertas)));
			$TwigEnvironment = new Twig_Environment($TwigLoader, array('charset' => 'UTF-8'));
			if(defined('APPNEURALPHPHOST') == true) {
				$TwigEnvironment->addGlobal('NeuralRutaApp', implode('/', array(__NeuralUrlRaiz__)));
				$TwigEnvironment->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'WebRoot', 'ErroresWeb')));
			}
			else {
				$TwigEnvironment->addGlobal('NeuralRutaBase', __NeuralUrlRaiz__);
				$TwigEnvironment->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'Web')));
			}
			echo $TwigEnvironment->render($Parametros['Plantilla'], array('Titulo' => $Parametros['Titulo'], 'Informacion' => $Parametros['Informacion'], 'Aplicacion' => $Parametros['Aplicacion'], 'Modulo' => $Parametros['Modulo'], 'Controlador' => $Parametros['Controlador'], 'Metodo' => $Parametros['Metodo'], 'Server' => $_SERVER, 'PHP' => array('VERSION' => phpversion(), 'MEMORY' => memory_get_usage(), 'OS' => php_uname('s'), 'MACHINE' => php_uname('m'), 'NAME_SERVER' => gethostbyaddr($_SERVER['SERVER_ADDR']))));
		}
		
		/**
		 * Metodo Protegido
		 * SeleccionEjecutar($Acceso = false, $Errores = false, $Aplicacion = false)
		 * 
		 * Valida que error mostrar al usuario
		 * @access private
		 */
		protected function SeleccionEjecutar($Acceso = false, $Errores = false, $Aplicacion = false) {
			if(self::ValidarLocalhost($Acceso[$Aplicacion]['Entorno_Desarrollo']['Localhost'], $_SERVER['REMOTE_ADDR']) == true) {
				self::EjecutarPlantillaDesarrollo(self::ValidarParametrosPlantilla($this->Variables));
			}
			elseif($Acceso[$Aplicacion]['Entorno_Desarrollo']['Externo']['Activo'] == true) {
				self::EjecutarPlantillaDesarrollo(self::ValidarParametrosPlantilla($this->Variables));
			}
			elseif($Acceso[$Aplicacion]['Error']['404']['Activo'] == true) {
				parent::EjecutarErrorAplicacion($Acceso[$Aplicacion]);
			}
			else {
				parent::EjecutarErrorUsuario($Errores['Personalizado'], $Errores['Sistema']);
			}
			unset($Acceso, $Aplicacion, $Errores);
		}
		
		/**
		 * Metodo Privado
		 * ValidarEjecutarTwig()
		 * 
		 * Valida si se encuentra incluida la libreria, de lo contrario la incluye
		 * adicional registra la aplicacion para ser Utilizada
		 * @access private
		 */
		private function ValidarEjecutarTwig() {
			if(class_exists('Twig_Autoloader') == false) {
				require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'Twig', 'Autoloader.php'));
			}
			Twig_Autoloader::register();
		}
		
		/**
		 * Metodo Privado
		 * ValidarLocalhost($Localhost = false, $Ip = false)
		 * 
		 * Valida si se encuentra activo el proceso correspondiente para observar los errores de desarrollo
		 * @access private
		 */
		private static function ValidarLocalhost($Localhost = false, $Ip = false) {
			if($Localhost['Activo'] == true) {
				if(array_key_exists($Ip, array_flip($Localhost['ip'])) == true) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		
		/**
		 * Meodo Privado
		 * ValidarParametrosPlantilla($Array = false)
		 * 
		 * Valida los parametros correspondientes y asigna valor en caso que no se encuentre
		 * @access private
		 */
		private function ValidarParametrosPlantilla($Array = false) {
			$Cantidad = count($Array);
			if($Cantidad >= 1) {
				foreach (self::$MatrizParametros AS $Llave => $Valor) {
					if(array_key_exists($Valor, $Array) == true) {
						$Data[$Valor] = $Array[$Valor];
					}
					else {
						if($Valor == 'Plantilla') {
							$Data[$Valor] = 'Base.html';
						}
						else {
							$Data[$Valor] = false;
						}
					}
				}
				return $Data;
			}
			else {
				foreach (self::$MatrizParametros AS $Llave => $Valor) {
					$Data[$Valor] = ($Valor == 'Plantilla') ? 'Base.html' : false;
				}
				return $Data;
			}
		}
	}