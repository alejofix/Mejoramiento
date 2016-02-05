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
	
	class ErrorUsuario {
		
		/**
		 * Carpeta de Errores
		 * @access private
		 */
		private static $FolderErrores = 'Errores';
		
		/**
		 * Constante de alias de modelo
		 * @access private
		 */
		const ALIAS_MODELO = '_Modelo';
		
		/**
		 * constante de extension php
		 * @access private
		 */
		const EXT_PHP = '.php';
		
		/**
		 * constante valor index
		 * @access private
		 */
		const INDEX = 'Index';
		
		/**
		 * constante separador web
		 * @access private
		 */
		const WEB_SEPARADOR = '/';
		
		/**
		 * Contante predeterminada
		 * @access private
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Metodo Protegido
		 * EjecutarErrorUsuario($Personalizado = false, $Sistema = false)
		 * 
		 * Ejecuta los errores correspondientes
		 * @access private
		 */
		protected function EjecutarErrorUsuario($Personalizado = false, $Sistema = false) {
			if($Personalizado['Habilitado'] == true) {
				self::EjecutarBaseControlador($Personalizado['Parametros']);
			}
			else {
				self::EjecutarErrorSistema($Sistema);
			}
		}
		
		/**
		 * Metodo Protegido
		 * 
		 * EjecutarErrorAplicacion($Parametros = array())
		 * 
		 * Ejecuta el controlador de error 404 personalizado de la matriz de accesos
		 * @access private
		 */
		protected function EjecutarErrorAplicacion($Parametros = array()) {
			if(self::ValidarExistenciaControlador($Parametros['Carpeta'], $Parametros['Error']['404']['Parametros']['Modulo'], $Parametros['Error']['404']['Parametros']['Controlador']) == true) {
				$RutaModelo = ($Parametros['Error']['404']['Parametros']['Modulo'] == true) ? implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Parametros['Carpeta'], 'App', 'Modulos', $Parametros['Error']['404']['Parametros']['Modulo'], 'Modelos')) : implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Parametros['Carpeta'], 'App', 'MVC', 'Modelos'));
				self::EjecutarControlador($RutaModelo, $Parametros['Error']['404']['Parametros']['Controlador'], $Parametros['Error']['404']['Parametros']['Metodo'], $Parametros['Error']['404']['Parametros']['Parametros']);
			}
			else {
				throw new NeuralException('El Controlador del Error Personalizado de la Aplicación No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * EjecutarBaseControlador($Parametros = array())
		 * 
		 * Ejecuta el controlador correspondiente o muestra error de no existe controlador
		 * @access private
		 */
		private function EjecutarBaseControlador($Parametros = array()) {
			if(self::ValidarExistenciaControlador($Parametros['CarpetaApp'], $Parametros['Modulo'], $Parametros['Controlador']) == true) {
				$RutaModelo = ($Parametros['Modulo'] == true) ? implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Parametros['CarpetaApp'], 'App', 'Modulos', $Parametros['Modulo'], 'Modelos')) : implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Parametros['CarpetaApp'], 'App', 'MVC', 'Modelos'));
				self::EjecutarControlador($RutaModelo, $Parametros['Controlador'], $Parametros['Metodo'], $Parametros['Parametros']);
			}
			else {
				throw new NeuralException('El Controlador del Error Personalizado No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * ValidarExistenciaControlador($Carpeta = self::PREDETERMINADO, $Modulo = false, $Controlador = self::INDEX)
		 * 
		 * Valida si existe el controlador correspondiente
		 * @access private
		 */
		private function ValidarExistenciaControlador($Carpeta = self::PREDETERMINADO, $Modulo = false, $Controlador = self::INDEX) {
			$File = ($Modulo == true) ? implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Modulos', $Modulo, 'Controladores', $Controlador.self::EXT_PHP)) : implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'MVC', 'Controladores', $Controlador.self::EXT_PHP));
			if(file_exists($File) == true) {
				require $File;
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Metodo Privado
		 * EjecutarControlador($Controller = false, $Metodo = false, $Parametro = false)
		 * 
		 * Ejecuta el controlador personalizado correspondiente
		 * @access private
		 */
		private function EjecutarControlador($RutaModelo = false, $Controller = false, $Metodo = false, $Parametro = false) {
			$Controlador = new $Controller;
			$Controlador->CargarModelo($RutaModelo, $Controller, self::ALIAS_MODELO, self::EXT_PHP);
			if(isset($Parametro) == true) {
				$Datos = self::AppOrganizarParametrosControlador($Parametro);
				eval("\$Controlador->\$Metodo(".$Datos.");");
			}
			else {
				$Controlador->$Metodo(false);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppOrganizarParametrosControlador($Parametro = false)
		 * 
		 * Organiza los parametros para cargar dentro del controlador
		 * @access private
		 */
		private function AppOrganizarParametrosControlador($Parametro = false) {
			foreach ($Parametro AS $Puntero => $Valor) {
				$Lista[] = '$Parametro['.$Puntero.']';
			}
			return implode(', ', $Lista);
		}
		
		/**
		 * Metodo Privado
		 * EjecutarErrorSistema($ErrorSistema = false)
		 * 
		 * Genera la visualizacion de la plantilla por defecto
		 * @access private
		 */
		private function EjecutarErrorSistema($ErrorSistema = false) {
			self::ValidarEjecutarTwig();
			$TwigLoader = new Twig_Loader_Filesystem(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, self::$FolderErrores)));
			$TwigEnvironment = new Twig_Environment($TwigLoader, array('charset' => 'UTF-8'));
			if(defined('APPNEURALPHPHOST') == true) {
				$TwigEnvironment->addGlobal('NeuralRutaApp', implode('/', array(__NeuralUrlRaiz__)));
				$TwigEnvironment->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'WebRoot', 'ErroresWeb')));
			}
			else {
				$TwigEnvironment->addGlobal('NeuralRutaBase', __NeuralUrlRaiz__);
				$TwigEnvironment->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'Web')));
			}
			echo $TwigEnvironment->render($ErrorSistema);
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
	}