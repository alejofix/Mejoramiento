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
	
	class NeuralJQueryFormularioValidacion {
		
		/**
		 * Contenedor de Mensajes
		 */
		private $Mensajes;
		
		/**
		 * Contenedor de Reglas
		 */
		private $Reglas;
		
		/**
		 * Constructor
		 * 
		 * @param $EtiquetaScript: valor true o false para agregar las etiquetas script
		 * @param $LibValidate: valor true o false incluye la libreria del plugin validate de jquery
		 * @param $LibJQuery: valor true o false incluye la libreria de jquery
		 */
		function __Construct($EtiquetaScript = true, $LibValidate = true, $LibJQuery = false) {
			$this->EtiquetaScript = (is_bool($EtiquetaScript) == true) ? $EtiquetaScript : true;
			$this->LibreriaValidate = (is_bool($LibValidate) == true) ? $LibValidate : true;
			$this->LibreriaJQuery = (is_bool($LibJQuery) == true) ? $LibJQuery : true;
		}
		
		/**
		 * Metodo Publico
		 * ArchivoExtension($CampoName = false, $Extension = false, $Mensaje = false)
		 * 
		 * Solo acepta las extensiones indicadas
		 * las extensiones deben ingresarse con du respectivo MIME Type
		 * @param $CampoName: etiqueta name del input
		 * @param $Extension: array de MIME Types de las extensiones correspondientes
		 * @example array('image/gif', 'image/jpeg', 'image/png', 'application/excel', 'application/pdf')
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return Solo Son Aceptados Los Archivos con la Extensión: image/png, image/jpeg
		 */
		public function ArchivoExtension($CampoName = false, $Extension = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Extension == true AND is_array($Extension) == true) {
				$this->Reglas[$CampoName]['accept'] = implode(', ', $Extension);
				$this->Mensajes[$CampoName]['accept'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'Solo Son Aceptados Los Archivos con la Extensión Especificada';
			}
		}
		
		/**
		 * Metodo Publico
		 * CampoIgual($CampoName = false, $IdCampoIgual = false, $Mensaje = false)
		 * 
		 * Valida si el valor es igual a otro campo en el mismo formulario
		 * @param $CampoName: etiqueta name del input
		 * @param $IdCampoIgual: Id del campo maestro para comparar
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return Los Campos No Son Iguales
		 */
		public function CampoIgual($CampoName = false, $IdCampoIgual = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $IdCampoIgual == true AND is_bool($IdCampoIgual) == false) {
				$this->Reglas[$CampoName]['equalTo'] = "#".$IdCampoIgual;
				$this->Mensajes[$CampoName]['equalTo'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'Los Campos No Son Iguales';
			}
		}
		
		/**
		 * Metodo Publico
		 * CantMaxCaracteres($CampoName= false, $Cantidad = false, $Mensaje = false)
		 * 
		 * Determina la cantidad maxima de caracteres que deben ingresarse
		 * @param $CampoName: etiqueta name del input
		 * @param $Cantidad: Cantidad de caracteres de valor numerico
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return La Cantidad Maxima Son 100 Caracteres
		 */
		public function CantMaxCaracteres($CampoName= false, $Cantidad = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Cantidad == true AND is_numeric($Cantidad) == true) {
				$this->Reglas[$CampoName]['maxlength'] = $Cantidad;
				$this->Mensajes[$CampoName]['maxlength'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'La Cantidad Maxima Son '.$Cantidad.' Caracteres';
			}
		}
		
		/**
		 * Metodo Publico
		 * CantMinCaracteres($CampoName= false, $Cantidad = false, $Mensaje = false)
		 * 
		 * Determina la cantidad minima de caracteres que deben ingresarse
		 * @param $CampoName: etiqueta name del input
		 * @param $Cantidad: Cantidad de caracteres de valor numerico
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return La Cantidad Minima Son 100 Caracteres
		 */
		public function CantMinCaracteres($CampoName= false, $Cantidad = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Cantidad == true AND is_numeric($Cantidad) == true) {
				$this->Reglas[$CampoName]['minlength'] = $Cantidad;
				$this->Mensajes[$CampoName]['minlength'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'La Cantidad Minima Son '.$Cantidad.' Caracteres';
			}
		}
		
		/**
		 * Metodo Publico
		 * Constructor($IdFormulario = false)
		 * 
		 * Genera el script correspondiente
		 * @param $IdFormulario: Id del formulario donde se tomara como punto de validacion
		 */
		public function Constructor($IdFormulario = false) {
			if(is_array($this->Reglas) == true AND is_array($this->Mensajes) == true) {
				$Regla[] = ($this->LibreriaJQuery == false) ? '' : '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.min.js').'"></script>';
				$Regla[] = ($this->LibreriaValidate == false) ? '' : '<script src="'.NeuralRutasApp::WebPublicoSistema('js/jquery.validate.min.js').'"></script>';
				$Regla[] = ($this->LibreriaValidate == false) ? '' : '<script src="'.NeuralRutasApp::WebPublicoSistema('js/additional-methods.min.js').'"></script>';
				$Regla[] = ($this->EtiquetaScript == true) ? '<script type="text/javascript">' : '';
				$Regla[] = '$(document).ready(function() { ';
				$Regla[] = '$("#'.$IdFormulario.'").validate(';
				if(isset($this->_SubmitHandler) == true) {
					$String = json_encode(array_merge(array('rules' => $this->Reglas), array('messages' => $this->Mensajes)),JSON_PRETTY_PRINT);
					$Regla[] = substr($String, 0, -1).', "submitHandler" : function(form) { '.$this->_SubmitHandler.' }}';
				}
				else {
					$Regla[] = json_encode(array_merge(array('rules' => $this->Reglas), array('messages' => $this->Mensajes)), JSON_PRETTY_PRINT);
				}
				$Regla[] = ');';
				$Regla[] = '});';
				$Regla[] = ($this->EtiquetaScript == true) ? '</script>' : '';
				return implode("\n", $Regla);
			}
			else {
				throw new NeuralException('No Hay Reglas Ni Mensajes Para Mostrar');
			}
		}
		
		/**
		 * Metodo Publico
		 * ControlEnvio($Controlador = false)
		 * 
		 * El control de envio es un proceso de codigo jquery para gestionar el envio del formulario
		 * este control de envio evita enviar los datos del formulario
		 * lo cual genera la necesidad que el codigo a implementar sea un proceso ajax
		 * @param $Controlador: codigo JQuery con el procedimiento ajax
		 * @example ControlEnvio("$.ajax({...})")
		 */
		public function ControlEnvio($Controlador = false) {
			$this->_SubmitHandler = trim($Controlador);
		}
		
		/**
		 * Metodo Publico
		 * Digitos($CampoNombre = false, $Mensaje = false)
		 * 
		 * Valida si el valor es un digito
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return Solo Es Permitido un Digito
		 */
		public function Digitos($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['digits'] = true;
				$this->Mensajes[$CampoName]['digits'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'Solo Es Permitido un Digito';
			}
		}
		
		/**
		 * Metodo Publico
		 * Email($CampoNombre = false, $Mensaje = false)
		 * 
		 * Valida si el valor es un correo electronico
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Formato del Correo No Es Correcto
		 */
		public function Email($CampoNombre = false, $Mensaje = false) {
			if($CampoNombre == true AND is_bool($CampoNombre) == false) {
				$this->Reglas[$CampoNombre]['email'] = true;
				$this->Mensajes[$CampoNombre]['email'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Formato del Correo No Es Correcto';
			}
		}
		
		/**
		 * Metodo Publico
		 * Fecha($CampoNombre = false, $Mensaje = false)
		 * 
		 * Valida si el valor es una Fecha
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Formato de Fecha No Es Correcto
		 */
		public function Fecha($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['date'] = true;
				$this->Mensajes[$CampoName]['date'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Formato de Fecha No Es Correcto';
			}
		}
		
		/**
		 * Metodo Publico
		 * FechaISO($CampoNombre = false, $Mensaje = false)
		 * 
		 * Valida si el valor es una Fecha
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Formato de Fecha No Es Correcto
		 */
		public function FechaISO($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['dateISO'] = true;
				$this->Mensajes[$CampoName]['dateISO'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Formato de Fecha No Es Correcto';
			}
		}
		
		/**
		 * Metodo Publico
		 * Numero($CampoName = false, $Mensaje = false)
		 * 
		 * Valida si el valor es numerico
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Campo Debe Tener Unicamente Datos Númericos
		 */
		public function Numero($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['number'] = true;
				$this->Mensajes[$CampoName]['number'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Campo Debe Tener Unicamente Datos Númericos';
			}
		}
		
		/**
		 * Metodo Publico
		 * RangoLongitud($CampoName = false, $Rango = false, $Mensaje)
		 * 
		 * Limita a utilizar una cantidad de caracteres en especifico en el campo
		 * @param $CampoName: etiqueta name del input
		 * @param $Rango: array con el valor inicial y valor fina
		 * @example array('1', '45')
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return La Longitud del Campo es de 1 a 45 Caracteres
		 */
		public function RangoLongitud($CampoName = false, $Rango = false, $Mensaje) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Rango == true AND is_array($Rango) == true) {
				$this->Reglas[$CampoName]['rangelength'] = $Rango;
				$this->Mensajes[$CampoName]['rangelength'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'La Longitud del Campo es de '.$Rango[0].' a '.$Rango[1].' Caracteres';
			}
		}
		
		/**
		 * Metodo Publico
		 * RangoValor($CampoName = false, $Rango = false, $Mensaje = false)
		 * 
		 * Limita a utilizar un valor numerico de valor de inicio y valor de fin
		 * @param $CampoName: etiqueta name del input
		 * @param $Rango: array con el valor inicial y valor fina
		 * @example array('1', '45')
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Rango de Valor del Campo es de 1 a 45
		 */
		public function RangoValor($CampoName = false, $Rango = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Rango == true AND is_array($Rango) == true) {
				$this->Reglas[$CampoName]['range'] = $Rango;
				$this->Mensajes[$CampoName]['range'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Rango de Valor del Campo es de '.$Rango[0].' a '.$Rango[1];
			}
		}
		
		/**
		 * Metodo Publico
		 * Remoto($CampoName = false, $Url = false, $Metodo = false, $Async = false, $Mensaje = false)
		 * 
		 * Genera una peticion ajax para consultar la informacion ingresada
		 * @param $CampoName: etiqueta name del input
		 * @param $Url: Url a la cual se enviara la peticion ajax para su consulta
		 * @param $Metodo: Tipo de metodo de envio de datos POST o GET
		 * @param $Async: valor predeterminado true o false
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return No Se Encuentra La Información Ingresada
		 */
		public function Remoto($CampoName = false, $Url = false, $Metodo = false, $Async = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Url == true AND is_bool($Url) == false) {
				$MetodoValido = ($Metodo == false) ? 'POST' : mb_strtoupper($Metodo);
				$AsyncValido = ($Async == true AND is_bool($Async) == true) ? $Async : false;
				$this->Reglas[$CampoName]['remote'] = array('url' => $Url, 'type' => $MetodoValido, 'async' => $AsyncValido);
				$this->Mensajes[$CampoName]['remote'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'No Se Encuentra La Información Ingresada';
			}
		}
		
		/**
		 * Metodo Publico
		 * Requerido($CampoName = false, $Mensaje = false)
		 * 
		 * Obliga al campo que sea obligatorio o requerido
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Campo es Requerido
		 */
		public function Requerido($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['required'] = true;
				$this->Mensajes[$CampoName]['required'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Campo es Requerido';
			}
		}
		
		/**
		 * Metodo Publico
		 * URL($CampoNombre = false, $Mensaje = false)
		 * 
		 * Valida si el valor es una URL
		 * @param $CampoName: etiqueta name del input
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Formato de la URL No Es Correcto
		 */
		public function URL($CampoName = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false) {
				$this->Reglas[$CampoName]['url'] = true;
				$this->Mensajes[$CampoName]['url'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Formato de la URL No Es Correcto';
			}
		}
		
		/**
		 * Metodo Publico
		 * ValorMaximo($CampoName= false, $Cantidad = false, $Mensaje = false)
		 * 
		 * Determina valor Maximo del campo
		 * @param $CampoName: etiqueta name del input
		 * @param $Cantidad: valor numerico
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Valor Maximo Es 100
		 */
		public function ValorMaximo($CampoName= false, $Cantidad = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Cantidad == true AND is_numeric($Cantidad) == true) {
				$this->Reglas[$CampoName]['max'] = $Cantidad;
				$this->Mensajes[$CampoName]['max'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Valor Maximo Es '.$Cantidad;
			}
		}
		
		/**
		 * Metodo Publico
		 * ValorMinimo($CampoName= false, $Cantidad = false, $Mensaje = false)
		 * 
		 * Determina valor minimo del campo
		 * @param $CampoName: etiqueta name del input
		 * @param $Cantidad: valor numerico
		 * @param $Mensaje: Mensaje Personalizado, false mensaje predeterminado
		 * @return El Valor Minimo Es 100
		 */
		public function ValorMinimo($CampoName= false, $Cantidad = false, $Mensaje = false) {
			if($CampoName == true AND is_bool($CampoName) == false AND $Cantidad == true AND is_numeric($Cantidad) == true) {
				$this->Reglas[$CampoName]['min'] = $Cantidad;
				$this->Mensajes[$CampoName]['min'] = ($Mensaje == true AND is_bool($Mensaje) == false) ? $Mensaje : 'El Valor Minimo Es '.$Cantidad;
			}
		}	
	}
