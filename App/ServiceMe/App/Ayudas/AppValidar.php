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
	
	class AppValidar {
		
		/**
		 * Contenedor de la matriz de datos
		 */
		private static $Matriz;
		
		/**
		 * Contenedor de la matriz de reglas
		 */
		private static $Reglas;
		
		/**
		 * Metodo Privado
		 * Empty_($Regla)
		 * 
		 * Genera la validacion si un valor de la matriz esta vacio
		 */
		private static function Empty_($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						if(empty(self::$Matriz[$Valor]) == true) {
							$Datos[] = false;
						}
					}
				}
				return (isset($Datos) == true) ? false : true;
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					if(empty($Valor) == true) {
						$Datos[] = false;
					}
				}
				return (isset($Datos) == true) ? false : true;
			}
		}
		
		/**
		 * Metodo Publico
		 * MatrizDatos($Array = false)
		 * 
		 * Asigna la matriz de datos a evaluar
		 * @param $Array: matriz POST o GET
		 */
		public static function MatrizDatos($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				self::$Matriz = $Array;
				unset($Array);
			}
			return self::Validacion();
		}
		
		/**
		 * Metodo Privado
		 * Numeric_($Regla)
		 * 
		 * Genera la validacion si un valor de la matriz es numerico
		 */
		private static function Numeric_($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						if(is_numeric(self::$Matriz[$Valor]) == false) {
							$Datos[] = false;
						}
					}
				}
				return (isset($Datos) == true) ? false : true;
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					if(is_numeric($Valor) == false) {
						$Datos[] = false;
					}
				}
				return (isset($Datos) == true) ? false : true;
			}
		}
		
		/**
		 * Metodo Publico
		 * Numero($Opcion = false)
		 * 
		 * Genera la regla para evaluar la matriz correspondiente
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function Numero($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['Numeric'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['Numeric'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Vacio($Opcion = false)
		 * 
		 * Genera la regla para evaluar la matriz correspondiente
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function Vacio($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['Empty'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['Empty'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Validacion()
		 * 
		 * Genera el proceso de validacion ejecutado en la Matriz de Datos
		 */
		private static function Validacion() {
			if(is_array(self::$Matriz) == true AND is_array(self::$Reglas) == true) {
				foreach (self::$Reglas AS $Llave => $Valor) {
					if($Llave == 'Numeric') {
						if(self::Numeric_($Valor) == false) {
							$Dato[] = false;
						}
					}
					elseif($Llave == 'Empty') {
						if(self::Empty_($Valor) == false) {
							$Dato[] = false;
						}
					}
				}
				return (isset($Dato) == true) ? false : true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Metodo Publico
		 * PeticionAjax()
		 * 
		 * Valida si se envia una peticion via ajax
		 * @return retorna true cuando se envia la cabecera, false no se envia cabecera ajax
		 */
		public static function PeticionAjax() {
			return (empty($_SERVER['HTTP_X_REQUESTED_WITH']) == false AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
		}
	}