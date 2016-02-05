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
	
	class NeuralJQueryAjaxConstructor {
		
		private static $Codigo;
		private static $Etiqueta_Script = false;
		private static $Lib_JQuery = false;
		
		/**
		 * Metodo Magico
		 */
		function __toString() {
			return self::Construir();
		}
		
		/**
		 * Metodo Publico
		 * AntesEnvio($Funcion = false)
		 * 
		 * Asigna una funcion para que se ejecute antes de ser enviada la solicitud ajax y mientras espera
		 * respuesta se ejecuta respectivamente
		 * @param $Funcion: la funcion que se muestre
		 */
		public static function AntesEnvio($Funcion = false) {
			if($Funcion == true AND is_bool($Funcion) == false) {
				self::$Codigo[] = 'beforeSend: function() { '.$Funcion.' }';
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * Construir()
		 * 
		 * Genera la construccion del script correspondiente
		 * @access private
		 */
		private static function Construir() {
			if(is_array(self::$Codigo) == true) {
				$Codigo[] = (self::$Lib_JQuery == false) ? '' : '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>';
				$Codigo[] = (self::$Etiqueta_Script == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = '$.ajax({';
				$Codigo[] = implode(', ', self::$Codigo);
				$Codigo[] = '});';
				$Codigo[] = (self::$Etiqueta_Script == true) ? '</script>' : '';
				return implode("\n", $Codigo);
			}
		}
		
		/**
		 * Metodo Publico
		 * Datos($Selector_Datos = false)
		 * 
		 * Indica el tipo de datos que se enviaran y se tomaran
		 * @param $Selector_Datos: tiene dos opciones
		 * @var '#Id_formulario': serializa los datos tomados del formulario
		 * @var array('Nombre' => 'Valor', 'Nombre' => '"Valor"')
		 */
		public static function Datos($Selector_Datos = false) {
			if(is_array($Selector_Datos) == true) {
				foreach ($Selector_Datos AS $Llave => $Valor) {
					$Data[] = $Llave.': '.$Valor;
				}
				self::$Codigo[] = 'data: { '.implode(', ', $Data).' }';
			}
			elseif($Selector_Datos == true AND is_bool($Selector_Datos) == false) {
				self::$Codigo[] = 'data: $("'.$Selector_Datos.'").serialize()';
			}
			else {
				self::$Codigo[] = 'data: null';
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * FinalizadoEnvio($Funcion = false)
		 * 
		 * Genera un espacio success dentro de la peticion y ejecuta la funcion correspondiente
		 * que se genere, esta se cargara siempre y cuando sea exitosa la peticion
		 * @param $Funcion: esta es la funcion que estara dentro del success
		 * @internal maneja una variable javascript "respuesta" que tomara los datos correspondientes
		 */
		public static function FinalizadoEnvio($Funcion = false) {
			if($Funcion == true AND is_bool($Funcion) == false) {
				self::$Codigo[] = 'success: function(Respuesta) { '.$Funcion.' }';
			}
			return new static;
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
		 * Metodo Publico
		 * TipoDatos($Tipo = false)
		 * 
		 * Asigna el tipo de dato que se enviara
		 * @param $Tipo: los tipos soportados son json, xml, html
		 */
		public static function TipoDatos($Tipo = false) {
			self::$Codigo[] = ($Tipo == true AND is_bool($Tipo) == false) ? 'dataType: "'.$Tipo.'"' : 'dataType: "html"';
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * TipoEnvio($Tipo = false)
		 * 
		 * Selecciona el tipo de envio
		 * @param $Tipo: los tipos soportados son POST y GET
		 */
		public static function TipoEnvio($Tipo = false) {
			self::$Codigo[] = ($Tipo == true AND is_bool($Tipo) == false) ? 'type: "'.mb_strtoupper($Tipo).'"' : 'type: "POST"';
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * URL($Url = false)
		 * 
		 * Direccion donde se enviara la peticion correspondiente
		 * @param $Url: direccion correspondiente
		 */
		public static function URL($Url = false) {
			self::$Codigo[] = ($Url == true AND is_bool($Url) == false) ? 'url: "'.$Url.'"' : 'url: null';
			return new static;
		}
	}