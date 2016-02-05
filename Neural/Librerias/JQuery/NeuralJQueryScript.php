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
	
	class NeuralJQueryScript {
		
		/**
		 * Contenedor de Parametros
		 */
		private static $Codigo;
		
		/**
		 * Metodo Magico
		 */
		function __toString() {
			return 'Script No Completo';
		}
		
		/**
		 * Metodo Publico
		 * CargarIntervaloTiempo($EtiquetaScript = true, $LibJQuery = false)
		 * 
		 * Genera la carga de una peticion ajax sobre los parametros recibidos
		 * @param $EtiquetaScript: valor true activa las estiquetas false las desactiva
		 * @param $LibJQuery: agrega la libreria jqueri de ser necesario valor true false
		 */
		public static function CargarIntervaloTiempo($EtiquetaScript = true, $LibJQuery = false) {
			if(self::ValidarRequerimiento(array('Principal', 'Url', 'Tiempo')) == true) {
				$Codigo[] = ($LibJQuery == true) ? '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>' : '';
				$Codigo[] = ($EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = "\t".'$(document).ready(function() { ';
				$Codigo[] = "\t\t".'$("'.self::$Codigo['Principal'].'").load("'.self::$Codigo['Url'].'");';
				$Codigo[] = "\t\t".'setInterval(function() { $("'.self::$Codigo['Principal'].'").load("'.self::$Codigo['Url'].'"); }, '.self::$Codigo['Tiempo'].'000);';
				$Codigo[] = "\t".'});';
				$Codigo[] = ($EtiquetaScript == true) ? '</script>' : '';
                self::$Codigo = '';
				return implode("\n", $Codigo);
			}
			else {
				throw new NeuralException('Es Necesario los Metodos IdPrincipal, URL y Tiempo');
			}
		}
		
		/**
		 * Metodo Publico
		 * CargarIntervaloTiempoPost($EtiquetaScript = true, $LibJQuery = false)
		 * 
		 * Genera la carga de una peticion ajax sobre los parametros recibidos y con datos post que se especifican
		 * @param $EtiquetaScript: valor true activa las estiquetas false las desactiva
		 * @param $LibJQuery: agrega la libreria jqueri de ser necesario valor true false
		 */
		public static function CargarIntervaloTiempoPost($EtiquetaScript = true, $LibJQuery = false) {
			if(self::ValidarRequerimiento(array('Principal', 'Url', 'Tiempo', 'Parametros')) == true) {
				$Codigo[] = ($LibJQuery == true) ? '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>' : '';
				$Codigo[] = ($EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = "\t".'$(document).ready(function() { ';
				$Codigo[] = "\t\t".'$("'.self::$Codigo['Principal'].'").load("'.self::$Codigo['Url'].'");';
				$Codigo[] = "\t\t".'setInterval(function() { $("'.self::$Codigo['Principal'].'").load("'.self::$Codigo['Url'].'", '.json_encode(self::$Codigo['Parametros']).' ); }, '.self::$Codigo['Tiempo'].'000);';
				$Codigo[] = "\t".'});';
				$Codigo[] = ($EtiquetaScript == true) ? '</script>' : '';
                self::$Codigo = '';
				return implode("\n", $Codigo);
			}
			else {
				throw new NeuralException('Es Necesario los Metodos IdPrincipal, URL, Tiempo y Parametros');
			}
		}
		
		/**
		 * Metodo Publico
		 * CargarLinkPeticion($EtiquetaScript = true, $LibJQuery = false)
		 * 
		 * Metodo para asociado al evento click de jquery para inicial la carga de una pagina por ajax
		 * @param $EtiquetaScript: valor true activa las estiquetas false las desactiva
		 * @param $LibJQuery: agrega la libreria jqueri de ser necesario valor true false
		 */
		public static function CargarLinkPeticion($EtiquetaScript = true, $LibJQuery = false) {
			if(self::ValidarRequerimiento(array('Principal', 'Secundario', 'Url')) == true) {
				$Codigo[] = ($LibJQuery == true) ? '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>' : '';
				$Codigo[] = ($EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = "\t".'$(document).ready(function() { ';
				$Codigo[] = "\t\t".'$("'.self::$Codigo['Principal'].'").click(function() { ';
				$Codigo[] = "\t\t\t".'evento.preventDefault();';
				$Codigo[] = "\t\t\t".'$("'.self::$Codigo['Secundario'].'").load("'.self::$Codigo['Url'].'");';
				$Codigo[] = "\t\t".'});';
				$Codigo[] = "\t".'});';
				$Codigo[] = ($EtiquetaScript == true) ? '</script>' : '';
                self::$Codigo = '';
				return implode("\n", $Codigo);
			}
            else {
                throw new NeuralException('Es Necesario los Metodos IdPrincipal, IdSecundario y URL');
            }
		}
		
		/**
		 * Metodo Publico
		 * CargarLinkPeticionPost($EtiquetaScript = true, $LibJQuery = false)
		 * 
		 * Metodo para asociado al evento click de jquery para inicial la carga de una pagina por ajax
		 * enviando una matriz por POST
		 * @param $EtiquetaScript: valor true activa las estiquetas false las desactiva
		 * @param $LibJQuery: agrega la libreria jqueri de ser necesario valor true false
		 */
		public static function CargarLinkPeticionPost($EtiquetaScript = true, $LibJQuery = false) {
			if(self::ValidarRequerimiento(array('Principal', 'Secundario', 'Url', 'Parametros')) == true) {
				$Codigo[] = ($LibJQuery == true) ? '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>' : '';
				$Codigo[] = ($EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = "\t".'$(document).ready(function() { ';
				$Codigo[] = "\t\t".'$("'.self::$Codigo['Principal'].'").click(function() { ';
				$Codigo[] = "\t\t\t".'evento.preventDefault();';
				$Codigo[] = "\t\t\t".'$("'.self::$Codigo['Secundario'].'").load("'.self::$Codigo['Url'].'", '.json_encode(self::$Codigo['Parametros']).');';
				$Codigo[] = "\t\t".'});';
				$Codigo[] = "\t".'});';
				$Codigo[] = ($EtiquetaScript == true) ? '</script>' : '';
                self::$Codigo = '';
				return implode("\n", $Codigo);
			}
            else {
                throw new NeuralException('Es Necesario los Metodos IdPrincipal, IdSecundario, URL y Parametros');
            }
		}
		
		/**
		 * Metodo Publico
		 * IdPrincipal($Id = false)
		 * 
		 * Asigna el valor del ID inicial asignado a la etiqueta HTML
		 * @param $Id: valor del ID inicial asignado a la etiqueta HTML
		 */
		public static function IdPrincipal($Id = false) {
			if($Id == true AND is_bool($Id) == false) {
				self::$Codigo['Principal'] = '#'.$Id;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * IdSecundario($Id = false)
		 * 
		 * Asigna el valor del ID final asignado a la etiqueta HTML
		 * @param $Id: valor del ID final asignado a la etiqueta HTML
		 */
		public static function IdSecundario($Id = false) {
			if($Id == true AND is_bool($Id) == false) {
				self::$Codigo['Secundario'] = '#'.$Id;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Parametros($Parametros = false)
		 * 
		 * Asigna el array de parametros
		 * @param $Parametros: array de parametros asociativos
		 * @example array('Nombre' => 'Valor')
		 */
		public static function Parametros($Parametros = false) {
			if($Parametros == true AND is_array($Parametros) == true) {
				self::$Codigo['Parametros'] = $Parametros;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Puntero($Puntero = false)
		 * 
		 * Asigna el nombre del puntero o dato que se enviara por la peticion ajax
		 * @param $Puntero: nombre del puntero o dato que se enviara por la peticion ajax
		 */
		public static function Puntero($Puntero = false) {
			if($Puntero == true AND is_bool($Puntero) == false) {
				self::$Codigo['Puntero'] = $Puntero;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * SelectDependientePost($EtiquetaScript = true, $LibJQuery = false)
		 * 
		 * Genera el proceso para crear sistema de select dependientes
		 * @param $EtiquetaScript: valor true activa las estiquetas false las desactiva
		 * @param $LibJQuery: agrega la libreria jqueri de ser necesario valor true false
		 */
		public static function SelectDependientePost($EtiquetaScript = true, $LibJQuery = false) {
			if(self::ValidarRequerimiento(array('Principal', 'Secundario', 'Url', 'Puntero')) == true) {
				$Codigo[] = ($LibJQuery == true) ? '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>' : '';
				$Codigo[] = ($EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Codigo[] = "\t".'$(document).ready(function() { ';
				$Codigo[] = "\t\t".'$("'.self::$Codigo['Principal'].'").change(function() { ';
				$Codigo[] = "\t\t\t".'$("'.self::$Codigo['Principal'].' option:selected").each(function() { ';
				$Codigo[] = "\t\t\t\t".self::$Codigo['Puntero'].' = $(this).val();';
				$Codigo[] = "\t\t\t\t".'$.post("'.self::$Codigo['Url'].'", { '.self::$Codigo['Puntero'].' : '.self::$Codigo['Puntero'].' }, function(data) { ';
				$Codigo[] = "\t\t\t\t\t".'$("'.self::$Codigo['Secundario'].'").html(data);';
				$Codigo[] = "\t\t\t\t".'});';
				$Codigo[] = "\t\t\t".'});';
				$Codigo[] = "\t\t".'});';
				$Codigo[] = "\t".'});';
				$Codigo[] = ($EtiquetaScript == true) ? '</script>' : '';
                self::$Codigo = '';
				return implode("\n", $Codigo);
			}
			else {
				throw new NeuralException('Es Necesario los Metodos IdPrincipal, IdSecundario, URL y Puntero');
			}
		}
		
		/**
		 * Metodo Publico
		 * Tiempo($Segundos = false)
		 * 
		 * Genera la asignacion del tiempo para aquellas peticiones de intervalo de tiempo
		 * @param $Segundos: tiempo en segundos
		 */
		public static function Tiempo($Segundos = false) {
			if($Segundos == true AND is_bool($Segundos) == false AND is_numeric($Segundos) == true) {
				self::$Codigo['Tiempo'] = $Segundos;
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * URL($Url = false)
		 * 
		 * Asigna la url donde se realizara la peticion ajax
		 * @param $Url: url donde se realizara la peticion ajax
		 */
		public static function URL($Url = false) {
			if($Url == true AND is_bool($Url) == false) {
				self::$Codigo['Url'] = $Url;
			}
			return new static;
		}
		
		/**
		 * Metodo Privado
		 * ValidarRequerimiento($Array = false)
		 * 
		 * Valida los requerimientos correspondientes
		 * @access private
		 */
		private static function ValidarRequerimiento($Array = false) {
			foreach ($Array AS $Llave => $Valor) {
				if(array_key_exists($Valor, self::$Codigo) == false) {
					$Error[] = true;
				}
			}
			return (isset($Error) == true) ? false : true;
		}
	}