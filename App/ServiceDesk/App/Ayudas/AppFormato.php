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
	
	class AppFormato {
		
		/**
		 * Contenedor de Matriz de Datos
		 */
		private static $Matriz;
		
		/**
		 * Contenedor de Reglas
		 */
		private static $Reglas;
		
		/**
		 * Metodo Publico
		 * Espacio($Opcion = false)
		 * 
		 * Elimina los espacios tanto del inicio como al final de la variable
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function Espacio($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['Trim'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['Trim'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Formato()
		 * 
		 * Genera el proceso de formato correspondiente
		 */
		private static function Formato() {
			if(is_array(self::$Matriz) == true AND is_array(self::$Reglas) == true) {
				foreach (self::$Reglas AS $Llave => $Valor) {
					if($Llave == 'Trim') {
						self::Trim($Valor);
					}
					elseif($Llave == 'Mb_Strtoupper') {
						self::Mb_Strtoupper($Valor);
					}
					elseif($Llave == 'Mb_Strtolower') {
						self::Mb_Strtolower($Valor);
					}
					elseif($Llave == 'SupSimbolos') {
						self::SupSimbolos($Valor);
					}
					elseif($Llave == 'SupSQL') {
						self::SupSQL($Valor);
					}
				}
			}
		}
		
		/**
		 * Metodo Publlico
		 * MatrizDatos($Array = false)
		 * 
		 * Asigna la matriz de datos a evaluar
		 * @param $Array: matriz POST o GET
		 */
		public static function MatrizDatos($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				self::$Matriz = $Array;
				self::Formato();
				$Datos = self::$Matriz;
				self::$Matriz = '';
				unset($Array);
				return $Datos;
			}
		}
		
		/**
		 * Metodo Publico
		 * Mayusculas($Opcion = false)
		 * 
		 * Asigna la regla de formato a mayusculas
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function Mayusculas($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['Mb_Strtoupper'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['Mb_Strtoupper'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Mb_Strtolower($Regla)
		 * 
		 * Genera el proceso de formato de minusculas segun la regla
		 */
		private static function Mb_Strtolower($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						self::$Matriz[$Valor] = mb_strtolower(self::$Matriz[$Valor]);
					}
				}
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					self::$Matriz[$Llave] = mb_strtolower($Valor);
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * Mb_Strtoupper($Regla)
		 * 
		 * Genera el proceso de formato de mayusculas
		 */
		private static function Mb_Strtoupper($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						self::$Matriz[$Valor] = mb_strtoupper(self::$Matriz[$Valor]);
					}
				}
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					self::$Matriz[$Llave] = mb_strtoupper($Valor);
				}
			}
		}
		
		/**
		 * Metoso Publico
		 * Minusculas($Opcion = false)
		 * 
		 * Asigna la regla de formato de minusculas
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function Minusculas($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['Mb_Strtolower'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['Mb_Strtolower'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * SupSQL($Regla)
		 * 
		 * Genera el proceso de eliminacion de inyeccion SQL
		 */
		private static function SupSQL($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						self::$Matriz[$Valor] = self::SupresorSQL(self::$Matriz[$Valor]);
					}
				}
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					self::$Matriz[$Llave] = self::SupresorSQL($Valor);
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * SupSimbolos($Regla)
		 * 
		 * Genera el proceso de eliminacion de simbolos
		 */
		private static function SupSimbolos($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						self::$Matriz[$Valor] = self::SupresorSimbolos(self::$Matriz[$Valor]);
					}
				}
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					self::$Matriz[$Llave] = self::SupresorSimbolos($Valor);
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * SupresorSQL($Texto = false)
		 * 
		 * Proceso individual de evaluacion y eliminacion
		 */
		private static function SupresorSQL($Texto = false) {
			$Matriz = array('SELECT', 'COPY', 'DROP', 'DUMP', '%', 'LIKE', '(', ')', 'DESCRIBE', '!', 'DELETE', '\'', 'INTO', 'ORDER BY', 'HAVING', 'WHERE', 'GROUP BY', '><', '<>','<', '>', '>=', '<=', 'BETWEEN', 'Information_schema.tables', 'Information_schema.columns', 'CONCAT', 'GROUP_CONCAT', '"', '!=');
			$Cadena = $Texto;
			foreach ($Matriz AS $Llave => $Valor) {
				$Cadena = str_ireplace($Valor, '', $Cadena);
			}
			$Cadena = addslashes($Cadena);
			unset($Llave, $Valor, $Matriz);
			return $Cadena;
		}
		
		/**
		 * Metodo Privado
		 * SupresorSimbolos($Texto = false)
		 * 
		 * Proceso individual de evaluacion y eliminacion
		 */
		private static function SupresorSimbolos($Texto = false) {
			$Matriz = array('|', '°', '¬', '!', '"', '#', '$', '%', '&', '/', '(', ')', '=', '\'', '?', '\\', '¡', '¿', '´', '¨', '*', '+', '{', '}', '[', ']', '^', '`');
			$Cadena = $Texto;
			foreach ($Matriz AS $Llave => $Valor) {
				$Cadena = str_ireplace($Valor, '', $Cadena);
			}
			$Cadena = addslashes($Cadena);
			unset($Llave, $Valor, $Matriz);
			return $Cadena;
		}
		
		/**
		 * Metodo Publico
		 * SuprimirSQL($Opcion = false)
		 * 
		 * Asigna la regla para eliminacion de Inyeccion SQL
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function SuprimirSQL($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['SupSQL'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['SupSQL'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * SuprimirSimbolos($Opcion = false)
		 * 
		 * Asigna la regla para eliminacion de Simbolos
		 * @param $Opcion: array con el nombre de las llaves a evaluar
		 * @example array('Llave a Evaluar')
		 * @param $Opcion: vacio o valor true para evaluar toda la matriz
		 */
		public static function SuprimirSimbolos($Opcion = false) {
			if($Opcion == true AND is_array($Opcion) == true) {
				self::$Reglas['SupSimbolos'] = $Opcion;
			}
			elseif(is_bool($Opcion) == true) {
				self::$Reglas['SupSimbolos'] = $Opcion;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Trim($Regla)
		 * 
		 * Genera el proceso de eliminacion de espacios
		 */
		private static function Trim($Regla) {
			if(is_array($Regla) == true) {
				foreach ($Regla AS $Llave => $Valor) {
					if(array_key_exists($Valor, self::$Matriz) == true) {
						self::$Matriz[$Valor] = trim(self::$Matriz[$Valor]);
					}
				}
			}
			elseif(is_bool($Regla) == true) {
				foreach (self::$Matriz AS $Llave => $Valor) {
					self::$Matriz[$Llave] = trim($Valor);
				}
			}
		}
	}