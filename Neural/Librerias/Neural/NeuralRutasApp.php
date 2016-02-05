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
	
	use \Neural\WorkSpace;
	
	class NeuralRutasApp {
		
		/**
		 * Constante de Validacion de parametro http
		 */
		const HTTP = 'http://';
		
		/**
		 * Constante de Validacion de parametro https
		 */
		const HTTPS = 'https://';
		
		/**
		 * Metodo Privado
		 * Definicion($Definicion = self::HTTP)
		 * 
		 * Genera la definicion de https y http del URL
		 * @access private
		 */
		private static function Definicion($Definicion = false) {
			if($Definicion == true) {
				return ($Definicion == self::HTTPS) ? str_replace(self::HTTP, self::HTTPS, __NeuralUrlRaiz__) : str_replace(self::HTTPS, self::HTTP, __NeuralUrlRaiz__);
			}
			else {
				return __NeuralUrlRaiz__;
			}
			
		}
		
		/**
		 * Metodo Privado
		 * RutaURL($Direccion = false, $Definicion = self::HTTP)
		 * 
		 * Genera la direccion base dentro del Servidor
		 * @access private
		 * @param $Direccion: Direccion compuesta donde se accesara
		 * @param $Definicion: Define si es http o https
		 * @example NeuralRutasApp::RutaURL('Aplicacion/Controlador/Metodo/Parametros')
		 * @example NeuralRutasApp::RutaURL('Aplicacion/Controlador/Metodo/Parametros', NeuralRutasApp::HTTP)
		 */
		private static function RutaURL($Direccion = false, $Definicion = self::HTTP) {
			return implode('/', array(self::Definicion($Definicion), $Direccion));
		}
		
		/**
		 * Metodo Publico
		 * RutaUrlApp($Controlador = false, $Metodo = false, $Parametros = false, $Definicion = self::HTTP)
		 * 
		 * Genera la ruta correspondiente dentro de la aplicacion a partir del Controlador
		 * @param $Controlador: Nombre del controlador correspondiente
		 * @param $Metodo: Nombre del Metodo correspondiente
		 * @param $Parametros: array incremental con los parametros que se pasaran en el orden indicado
		 * @param $Definicion: Define si es http o https
		 */
		public static function RutaUrlApp($Controlador = false, $Metodo = false, $Parametros = false, $Definicion = false) {
			if(defined('APPNEURALPHPHOST') == true) {
				if($Controlador == true AND $Metodo == true AND $Parametros == true AND is_array($Parametros) == true) {
					return implode('/', array_merge(array(self::Definicion($Definicion), $Controlador, $Metodo), $Parametros));
				}
				elseif($Controlador == true AND $Metodo == true) {
					return implode('/', array(self::Definicion($Definicion), $Controlador, $Metodo));
				}
				elseif($Controlador == true) {
					return implode('/', array(self::Definicion($Definicion), $Controlador));
				}
				else {
					return implode('/', array(self::Definicion($Definicion)));
				}
			}
			else {
				if($Controlador == true AND $Metodo == true AND $Parametros == true AND is_array($Parametros) == true) {
					return implode('/', array_merge(array(self::Definicion($Definicion), self::SeleccionApp(), $Controlador, $Metodo), $Parametros));
				}
				elseif($Controlador == true AND $Metodo == true) {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), $Controlador, $Metodo));
				}
				elseif($Controlador == true) {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), $Controlador));
				}
				else {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp()));
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * RutaUrlAppModulo($Modulo = false, $Controlador = false, $Metodo = false, $Parametros = false, $Definicion = self::HTTP)
		 * 
		 * Genera la ruta correspondiente dentro de la aplicacion a partir del Controlador
		 * @param $Modulo: Nombre del Modulo correspondiente
		 * @param $Controlador: Nombre del controlador correspondiente
		 * @param $Metodo: Nombre del Metodo correspondiente
		 * @param $Parametros: array incremental con los parametros que se pasaran en el orden indicado
		 * @param $Definicion: Define si es http o https
		 */
		public static function RutaUrlAppModulo($Modulo = false, $Controlador = false, $Metodo = false, $Parametros = false, $Definicion = false) {
			if(defined('APPNEURALPHPHOST') == true) {
				if($Modulo == true AND $Controlador == true AND $Metodo == true AND $Parametros == true AND is_array($Parametros) == true) {
					return implode('/', array_merge(array(self::Definicion($Definicion), $Modulo, $Controlador, $Metodo), $Parametros));
				}
				elseif($Modulo == true AND $Controlador == true AND $Metodo == true) {
					return implode('/', array(self::Definicion($Definicion), $Modulo, $Controlador, $Metodo));
				}
				elseif($Modulo == true AND $Controlador == true) {
					return implode('/', array(self::Definicion($Definicion), $Modulo, $Controlador));
				}
				elseif($Modulo == true) {
					return implode('/', array(self::Definicion($Definicion), $Modulo));
				}
				else {
					return implode('/', array(self::Definicion($Definicion)));
				}
			}
			else {
				if($Modulo == true AND $Controlador == true AND $Metodo == true AND $Parametros == true AND is_array($Parametros) == true) {
					return implode('/', array_merge(array(self::Definicion($Definicion), self::SeleccionApp(), $Modulo, $Controlador, $Metodo), $Parametros));
				}
				elseif($Modulo == true AND $Controlador == true AND $Metodo == true) {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), $Modulo, $Controlador, $Metodo));
				}
				elseif($Modulo == true AND $Controlador == true) {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), $Modulo, $Controlador));
				}
				elseif($Modulo == true) {
					return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), $Modulo));
				}
				else {
					return implode('/', array(self::Definicion($Definicion)), self::SeleccionApp());
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * SeleccionApp()
		 * 
		 * Indica la aplicacion que se esta ejecutando actualmente
		 * @access private
		 */
		private static function SeleccionApp() {
			$ModReWrite = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
			return (isset($ModReWrite[0]) == true) ? $ModReWrite[0] : 'Predeterminado';
		}
		
		/**
		 * Metodo Publico
		 * WebPublico($RutArchivo = false, $Definicion = false)
		 * 
		 * Genera la ruta directa para mostrar los archivos de la carpeta publica
		 * @param $RutArchivo: ruta del archivo correspondiente
		 * @param $Definicion: Define si es http o https
		 */
		public static function WebPublico($RutArchivo = false, $Definicion = false) {
			if(defined('APPNEURALPHPHOST') == true) {
				return implode('/', array(self::Definicion($Definicion), 'WebRoot', 'Web', $RutArchivo));
			}
			else {
				return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), 'WebRoot', 'Web', $RutArchivo));
			}
		}
		
		/**
		 * Metodo Publico
		 * WebPublicoSistema($RutArchivo = false, $Definicion = false)
		 * 
		 * Genera la ruta directa para mostrar los archivos de la carpeta publica
		 * @param $RutArchivo: ruta del archivo correspondiente
		 * @param $Definicion: Define si es http o https
		 */
		public static function WebPublicoSistema($RutArchivo = false, $Definicion = false) {
			if(defined('APPNEURALPHPHOST') == true) {
				return implode('/', array(self::Definicion($Definicion), 'WebRoot', 'ErroresWeb', $RutArchivo));
			}
			else {
				return implode('/', array(self::Definicion($Definicion), self::SeleccionApp(), 'WebRoot', 'ErroresWeb', $RutArchivo));
			}
		}
	}