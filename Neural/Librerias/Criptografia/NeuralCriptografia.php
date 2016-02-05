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
	 
	class NeuralCriptografia {
		
		/**
		 * Constante de Nivel de Compresion
		 */
		const NIVEL_COMPRESION = 8;
		
		/**
		 * Constante predeterminado
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Metodo Privado
		 * COD_PROD_256($Llave = false, $Cadena = false, $Codificacion = false)
		 * 
		 * Genera el Proceso de Codificacion correspondiente
		 * @access private
		 */
		private static function COD_PROD_256($Llave = false, $Cadena = false, $Codificacion = false) {
			$tamano = mcrypt_get_iv_size($Codificacion, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($tamano, MCRYPT_RAND);
			$crypt = mcrypt_encrypt($Codificacion, $Llave, $Cadena, MCRYPT_MODE_ECB, $iv);
			return base64_encode($crypt);
		}
		
		/**
		 * Metodo Publico
		 * Codificar($Cadena = false, $App = self::PREDETERMINADO)
		 * 
		 * Genera el procedimiento de codificacion
		 * @param $Cadena: cadena de texto que desea codificar
		 * @param $App: aplicacion que tomara las claves de codificacion
		 * el parametro $App puede recibir un array con la clave personalizada y la App
		 * @example Codificar('Mi Texto', array('Mi clave', 'Mi_Aplicacion'))
		 */
		public static function Codificar($Cadena = false, $App = self::PREDETERMINADO) {
			if(is_array($App) == true) {
				return self::CodificarProcesoArray($Cadena, $App);
			}
			else {
				return self::CodificarProcesoString($Cadena, $App);
			}
		}
		
		/**
		 * Metodo Privado
		 * CodificarProcesoArray($Cadena = false, $Array = array())
		 * 
		 * Genera el proceso de codificacion utilizando una clave personalizada
		 * @access private
		 */
		private static function CodificarProcesoArray($Cadena = false, $Array = array()) {
			if(self::ValidarExistenciaApp($Array[1]) == true) {
				$Datos = self::ParametrosApp($Array[1]);
				$RIJNDAEL = self::COD_PROD_256(self::HashPersonalizado($Array[0]), $Cadena, MCRYPT_RIJNDAEL_256);
				$BLOWFISH = self::COD_PROD_256($Datos['BLOWFISH'], $RIJNDAEL, MCRYPT_BLOWFISH);
				$BLOWFISH_COMPRESS = self::Compresion($BLOWFISH);
				unset($Datos, $RIJNDAEL, $BLOWFISH, $Cadena, $Array);
				return base64_encode($BLOWFISH_COMPRESS);
			}
			else {
				throw new NeuralException('La Aplicación Ingresada No Existe en El Archivo de Configuración');
			}
		}
		
		/**
		 * Metodo Privado
		 * CodificarProcesoString($Cadena = false, $App = self::PREDETERMINADO)
		 * 
		 * Genera el proceso de codificacion de las claves configuradas en la aplicacion
		 * @access private
		 */
		private static function CodificarProcesoString($Cadena = false, $App = self::PREDETERMINADO) {
			$Datos = self::ParametrosApp($App);
			$RIJNDAEL = self::COD_PROD_256($Datos['RIJNDAEL'], $Cadena, MCRYPT_RIJNDAEL_256);
			$BLOWFISH = self::COD_PROD_256($Datos['BLOWFISH'], $RIJNDAEL, MCRYPT_BLOWFISH);
			$BLOWFISH_COMPRESS = self::Compresion($BLOWFISH);
			unset($Datos, $RIJNDAEL, $BLOWFISH, $Cadena, $App);
			return base64_encode($BLOWFISH_COMPRESS);
		}
		
		/**
		 * Metodo Privado
		 * Compresion($Cadena = false)
		 * 
		 * Genera el proceso de compresion del string para hacerlo menos extenso
		 * @access private
		 */
		private static function Compresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzcompress($Cadena, self::NIVEL_COMPRESION) : $Cadena;
		}
		
		/**
		 * Metodo Privado
		 * DECOD_PROD_256($Llave = false, $Cadena = false, $Codificacion = false)
		 * 
		 * Genera el Proceso de DeCodificacion correspondiente
		 * @access private
		 */
		private static function DECOD_PROD_256($Llave = false, $Cadena = false, $Codificacion = false) {
			$base64 = base64_decode($Cadena);
			$tamano = mcrypt_get_iv_size($Codificacion, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($tamano, MCRYPT_RAND);
			return trim(mcrypt_decrypt($Codificacion, $Llave, $base64, MCRYPT_MODE_ECB, $iv));
		}
		
		/**
		 * Metodo Publico
		 * DeCodificar($Cadena = false, $App = self::PREDETERMINADO)
		 * 
		 * Genera el procedimiento de decodificacion
		 * @param $Cadena: cadena de texto que desea decodificar
		 * @param $App: aplicacion que tomara las claves de decodificacion
		 * el parametro $App puede recibir un array con la clave personalizada y la App
		 * @example DeCodificar('Mi Texto codificado', array('Mi clave', 'Mi_Aplicacion'))
		 */
		public static function DeCodificar($Cadena = false, $App = self::PREDETERMINADO) {
			if(is_array($App) == true) {
				return self::DeCodificarProcesoArray($Cadena, $App);
			}
			else {
				return self::DeCodificarProcesoString($Cadena, $App);
			}
		}
		
		/**
		 * Metodo Privado
		 * DeCodificarProcesoArray($Cadena = false, $Array = array())
		 * 
		 * Genera el proceso de decodificacion correspondiente
		 * @access private
		 */
		private static function DeCodificarProcesoArray($Cadena = false, $Array = array()) {
			if(self::ValidarExistenciaApp($Array[1]) == true) {
				$Datos = self::ParametrosApp($Array[1]);
				$BLOWFISH_COMPRESS = base64_decode($Cadena);
				$BLOWFISH = self::DesCompresion($BLOWFISH_COMPRESS);
				$RIJNDAEL_COMPRESS = self::DECOD_PROD_256($Datos['BLOWFISH'], $BLOWFISH, MCRYPT_BLOWFISH);
				return self::DECOD_PROD_256(self::HashPersonalizado($Array[0]), $RIJNDAEL_COMPRESS, MCRYPT_RIJNDAEL_256);
			}
			else {
				throw new NeuralException('La Aplicación Ingresada No Existe en El Archivo de Configuración');
			}
		}
		
		/**
		 * Metodo Privado
		 * DeCodificarProcesoString($Cadena = false, $App = self::PREDETERMINADO)
		 * 
		 * Genera el proceso de decodificacion correspondiente
		 * @access private
		 */
		private static function DeCodificarProcesoString($Cadena = false, $App = self::PREDETERMINADO) {
			$Datos = self::ParametrosApp($App);
			$BLOWFISH_COMPRESS = base64_decode($Cadena);
			$BLOWFISH = self::DesCompresion($BLOWFISH_COMPRESS);
			$RIJNDAEL_COMPRESS = self::DECOD_PROD_256($Datos['BLOWFISH'], $BLOWFISH, MCRYPT_BLOWFISH);
			return self::DECOD_PROD_256($Datos['RIJNDAEL'], $RIJNDAEL_COMPRESS, MCRYPT_RIJNDAEL_256);
		}
		
		/**
		 * Metodo Privado
		 * DesCompresion($Cadena = false)
		 * 
		 * Genera el proceso de descompresion correspondiente
		 * @access private
		 */
		private static function DesCompresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzuncompress($Cadena) : $Cadena;
		}
		
		/**
		 * Metodo Privado
		 * HashPersonalizado($Llave = false)
		 * 
		 * Codifica la clave personalizada en un hash para su codificacion asignada
		 * @access private
		 */
		private static function HashPersonalizado($Llave = false) {
			return hash('md2', $Llave);
		}
		
		/**
		 * Metodo Privado
		 * ParametrosApp($App = self::PREDETERMINADO)
		 * 
		 * Consulta los parametros de la aplicacion
		 * @access private
		 */
		private static function ParametrosApp($App = self::PREDETERMINADO) {
			if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))) == true) {
				$Matriz = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))), true);
				if($Matriz == true) {
					return self::SeleccionarParametros($App, $Matriz);
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración de Accesos No Es Correcto');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de Accesos No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * SeleccionarParametros($App = self::PREDETERMINADO, $Matriz = false)
		 * 
		 * Regresa los parametros de codificacion del archivo de configuracion
		 * @access private
		 */
		private static function SeleccionarParametros($App = self::PREDETERMINADO, $Matriz = false) {
			if(array_key_exists($App, $Matriz) == true) {
				return $Matriz[$App]['Criptografia'];
			}
			else {
				throw new NeuralException();
			}
		}
		
		/**
		 * Metodo Privado
		 * ValidarExistenciaApp($App = self::PREDETERMINADO)
		 * 
		 * Valida la existencia de la aplicacion correspondiente
		 * @access private
		 */
		private static function ValidarExistenciaApp($App = self::PREDETERMINADO) {
			if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))) == true) {
				$Matriz = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))), true);
				if($Matriz == true) {
					if(array_key_exists($App, $Matriz) == true) {
						unset($App, $Matriz);
						return true;
					}
					else {
						unset($App, $Matriz);
						return false;
					}
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración de Accesos No Es Correcto');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de Accesos No Existe');
			}
		}
	}
