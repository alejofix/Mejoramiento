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
	
	class NeuralSesiones {
		
		/**
		 * Contenedor de la Aplicacion
		 * @access private
		 */
		private static $App;
		
		/**
		 * Contenedor de la contraseña de codificacion
		 * @access private
		 */
		private static $Password = null;
		
		/**
		 * Metodo Publico
		 * AsignarSession($Llave = false, $Valor = false)
		 * 
		 * Asigna la llave y el valor de la session
		 * @param $Llave: la llave o key de la matriz de session
		 * @param $Valor: valor correspondiente
		 */
		public static function AsignarSession($Llave = false, $Valor = false) {
			if($Llave == true AND is_bool($Llave) == false) {
				$_SESSION[trim($Llave)] = trim(self::FormatoCodificado($Valor));
			}
		}
		
		/**
		 * Metodo Privado
		 * Codificar($Valor)
		 * 
		 * Genera el proceso de codificación de los datos
		 * @access private
		 */
		private static function Codificar($Valor) {
			if(self::$Password == null) :
				return NeuralCriptografia::Codificar($Valor, self::$App);
			else:
				return NeuralCriptografia::Codificar($Valor, array(hash('md5', self::$Password), self::$App));
			endif;
		}
		
		/**
		 * Metodo Privado
		 * Decodificar($Valor)
		 * 
		 * Genera el proceso de decodificación de los datos
		 * @access private
		 */
		private static function Decodificar($Valor) {
			if(self::$Password == null):
				return NeuralCriptografia::DeCodificar($Valor, self::$App);
			else:
				return NeuralCriptografia::DeCodificar($Valor, array(hash('md5', self::$Password), self::$App));
			endif;
		}
		
		/**
		 * Metodo Publico
		 * Finalizar($Llave = false)
		 * 
		 * Genera la finalizacion de la session
		 * @param $Llave: llave de session que se eliminara
		 * se puede manejar de la siguiente forma
		 * @example NeuralSesiones::Finalizar(); elimina toda la session
		 * @example NeuralSesiones::Finalizar(array('Llave')); eliminara la llave correspondiente
		 */
		public static function Finalizar($Llave = false) {
			if(is_bool($Llave) == true) {
				unset($_SESSION);
                session_destroy();
			}
			elseif(is_array($Llave) == true) {
				foreach($Llave AS $Key => $Valor) {
					unset($_SESSION[trim($Valor)]);
				}
			}
			else {
				unset($_SESSION);
                session_destroy();                
			}
		}
		
		/**
		 * Metodo Privado
		 * FormatoCodificado($Valor = false)
		 * 
		 * Genera el proceso de codificación y aplica el formato correspondiente
		 * @access private
		 */
		private static function FormatoCodificado($Valor = false) {
			return self::Codificar(json_encode($Valor));
		}
		
		/**
		 * Metodo Privado
		 * FormatoDecodificado($Valor = false)
		 * 
		 * Genera el proceso de decodificación y aplica el formato correspondiente
		 * @access private
		 */
		private static function FormatoDecodificado($Valor = false) {
			return json_decode(self::Decodificar($Valor), true);
		}
		
		/**
		 * Metodo Publico
		 * Inicializar($App = false)
		 * 
		 * Genera el proceso de seleccion de la aplicacion e inicialización de la session
		 * Asigna los datos para la codificación de los datos correspondientes
		 * @param $App: seleccion de la aplicacion donde se tomaran los datos de codificacion
		 * los datos se pueden manejar de la siguiente forma
		 * @example NeuralSesiones::Inicializar(); toma de forma automatica la app actual
		 * @example NeuralSesiones::Inicializar('MiAplicacion');
		 * si se requiere manejar una contraseña personalizada se maneja
		 * una matriz con contraseña - aplicacion
		 * @example NeuralSesiones::Inicializar(array('Contraseña', 'MiAplicacion'));
		 */
		public static function Inicializar($App = false) {
			if(is_bool($App) == true) {
				$ModReWrite = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
				self::$App = $ModReWrite[0];
			}
			elseif(is_array($App) == true) {
				self::$Password = $App[0];
				self::$App = $App[1];
			}
			else {
				self::$App = $App;
			}
			session_start();
		}
		
		/**
		 * Metodo Publico
		 * ObtenerSession($Llave = false)
		 * 
		 * Genera el proceso de obtener los datos de la session
		 * @param $Llave: llave o key de la matriz de session
		 */
		public static function ObtenerSession($Llave = false) {
			if($Llave == true AND is_bool($Llave) == false) {
				if(isset($_SESSION) == true AND array_key_exists($Llave, $_SESSION) == true) {
					return self::FormatoDecodificado($_SESSION[trim($Llave)]);
				}
			}
		}
	}