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
	
	class NeuralJQueryConstructor {
		
		/**
		 * Contenedor de Codigo
		 */
		private static $Codigo;
		
		/**
		 * Contenedor de Variable de etiquetas
		 */
		private static $Etiqueta_Script;
		
		/**
		 * Contenedor de variable de libreria JQuery
		 */
		private static $Lib_JQuery;
		
		/**
		 * Metodo Magico
		 * __toString()
		 * 
		 * Imprime el procedimiento correspondiente
		 */
		function __toString() {
			return self::Construir();
		}
		
		/**
		 * Metodo Publico
		 * Inicializar($EtiquetaScript = false, $LibJQuery = false)
		 * 
		 * Determina el valor para mostrar las etiquetas script e incluir la libreria de JQuery
		 * @param $EtiquetaScript: valor true o false para mostrar o no las etiquetas script
		 * @param $LibJQuery: valor true o false para mostrar o no incluir la libreria jquery
		 */
		public static function Inicializar($EtiquetaScript = false, $LibJQuery = false) {
			self::$Etiqueta_Script = (is_bool($EtiquetaScript) == true) ? $EtiquetaScript : false;
			self::$Lib_JQuery = (is_bool($LibJQuery) == true) ? $LibJQuery : false;
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Construir()
		 * 
		 * Genera el proceso de construir el script basico correspondiente
		 * @access private
		 */
		private static function Construir() {
			if(is_array(self::$Codigo) == true) {
				$Organizado = array_reverse(self::$Codigo);
				$Cantidad = count(self::$Codigo);
				for ($i = 0; $i < $Cantidad; $i++) {
					if(isset($Organizado[$i+1]) == true) {
						$Organizado[$i+1] = str_replace('%Plantilla%', $Organizado[$i], $Organizado[$i+1]);
					}
				}
				$Codigo[] = (self::$Lib_JQuery == false) ? '' : '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>';
				$Codigo[] = (self::$Etiqueta_Script == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = $Organizado[$Cantidad-1];
				$Codigo[] = (self::$Etiqueta_Script == true) ? '</script>' : '';
				self::$Codigo = '';
				self::$Etiqueta_Script = '';
				self::$Lib_JQuery = '';
				return implode("\n", $Codigo);
			}
		}
		
		/**
		 * Metodo Publico
		 * DocumentoListo()
		 * 
		 * Genera el inicio de $(document).ready(); para el inicio del script
		 */
		public static function DocumentoListo() {
			self::$Codigo[] = '$(document).ready(function() { %Plantilla% });';
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * AccionClick($Selector = false)
		 * 
		 * Genera la accion de click
		 * @param $Selector: se indica el selector correspondiente
		 * @example ->AccionClick("#Mi_ID")
		 */
		public static function AccionClick($Selector = false) {
			self::$Codigo[] = '$(\''.trim($Selector).'\').click(function() { %Plantilla% });';
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * AccionCargar($Selector = false, $Url = false, $ParametrosPOST = false)
		 * 
		 * Genera la carga correspondiente del resultado de una URL
		 * en el selector especificado
		 * @param $Selector: Selector de la etiqueta html que se le asignara la accion
		 * @param $Url: Direccion de la peticion
		 * @param $Funcion: valor true para construir una funcion interna 
		 * @param $Parametros: Matriz de los datos que se enviara
		 * @example array('Nombre' => 'Valor')
		 */
		public static function AccionCargar($Selector = false, $Url = false, $Funcion = false, $Parametros = false) {
			if($Selector == true AND $Url == true AND $Funcion == true AND is_bool($Funcion) == true AND $Parametros == true AND is_array($Parametros) == true) {
				self::$Codigo[] = '$(\''.trim($Selector).'\').load("'.trim($Url).'", '.json_encode($Parametros).', function() { %Plantilla% });';
			}
			elseif($Selector == true AND $Url == true AND $Funcion == true AND is_bool($Funcion) == true) {
				self::$Codigo[] = '$(\''.trim($Selector).'\').load("'.trim($Url).'", function() { %Plantilla% });';
			}
			elseif($Selector == true AND $Url == true AND $Parametros == true AND is_array($Parametros) == true) {
				self::$Codigo[] = '$(\''.trim($Selector).'\').load("'.trim($Url).'", '.json_encode($Parametros).');';
			}
			elseif($Selector == true AND $Url == true) {
				self::$Codigo[] = '$(\''.trim($Selector).'\').load("'.trim($Url).'");';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * IntervaloTiempo($TiempoSeg = false)
		 * 
		 * Genera un bucle de intervalo de tiempo
		 * @param $TiempoSeg: tiempo en segundos
		 */
		public static function AccionIntervaloTiempo($TiempoSeg = false) {
			$Tiempo = ($TiempoSeg == true AND is_numeric($TiempoSeg) == true) ? $TiempoSeg*1000 : 10000;
			self::$Codigo[] = 'setInterval(function() { %Plantilla% }, '.$Tiempo.');';
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * AccionDesaparecer($Selector = false, $Duracion = false, $Funcion = false)
		 * 
		 * Genera la accion de desaparecer un bloque
		 * @param $Selector: Selector de la etiqueta que desea que desaparecera
		 * @param $Duracion: la direcion puede ser FAST, SLOW o directamente el valor numerico por defecto 400
		 * @param $Funcion: valor true para seguir el hilo de funciones o false para finalizarlo
		 */
		public static function AccionDesaparecer($Selector = false, $Duracion = false, $Funcion = false) {
			$Tiempo = ($Duracion == true AND is_numeric($Duracion) == true) ? $Duracion*1000 : 400;
			if($Selector == true AND $Duracion == true AND $Funcion == true AND is_bool($Funcion) == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeOut("'.mb_strtolower(trim($Tiempo)).'", function() { %Plantilla% });';
			}
			elseif($Selector == true AND $Duracion == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeOut("'.trim($Tiempo).'");';
			}
			elseif($Selector == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeOut(400);';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * AccionAparecer($Selector = false, $Duracion = false, $Funcion = false)
		 * 
		 * Genera la accion de hacer visible un bloque
		 * @param $Selector: Selector de la etiqueta que desea que visible
		 * @param $Duracion: la direcion puede ser FAST, SLOW o directamente el valor numerico por defecto 400
		 * @param $Funcion: valor true para seguir el hilo de funciones o false para finalizarlo
		 */
		public static function AccionAparecer($Selector = false, $Duracion = false, $Funcion = false) {
			$Tiempo = ($Duracion == true AND is_numeric($Duracion) == true) ? $Duracion*1000 : 400;
			if($Selector == true AND $Duracion == true AND $Funcion == true AND is_bool($Funcion) == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeIn("'.mb_strtolower(trim($Tiempo)).'", function() { %Plantilla% });';
			}
			elseif($Selector == true AND $Duracion == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeIn("'.trim($Tiempo).'");';
			}
			elseif($Selector == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").fadeIn(400);';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * InsertarHTML($Selector = false, $TextoHTML = false)
		 * 
		 * Mustra el texto ingresado dentro de las etiquetas del selector correspondiente
		 * @param $Selector: Selector de la etiqueta 
		 * @param $TextoHTML: texto plano y/o texto con etiquetas html
		 */
		public static function InsertarHTML($Selector = false, $TextoHTML = false) {
			if($Selector == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").html('.trim($TextoHTML).');';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Css($Selector = false, $Propiedades = false)
		 * 
		 * Genera el cambio de propiedades CSS en el html del selector indicado
		 * @param $Selector: Selector de la etiqueta
		 * @param $Propiedades: array asociativo nombre => valor de cada de una de las propiedades
		 * @example array('font-family' => 'verdana', 'color' => 'red', 'visibility' => 'hidden')
		 */
		public static function Css($Selector = false, $Propiedades = false) {
			if($Selector == true AND $Propiedades == true AND is_array($Propiedades) == true) {
				self::$Codigo[] = '$("'.trim($Selector).'").css('.json_encode($Propiedades).')';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * MostrarAlerta($Texto = false)
		 * 
		 * Muestra una alerta correspondiente, el texto se imprimira como codigo javascript/jquery
		 * para manejo de texto se recomienda el uso de comillas dobles (")
		 * para concatenar datos con el simbolo (+)
		 * @param $Texto: texto correspondiente a mostrar
		 * @example ->MostrarAlerta('"Hola NeuralPHP Framework"')
		 */
		public static function MostrarAlerta($Texto = false) {
			self::$Codigo[] = 'alert('.trim($Texto).');';
			return new static;
		}
	}