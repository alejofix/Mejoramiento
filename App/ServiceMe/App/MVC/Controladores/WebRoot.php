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
	
	class WebRoot extends Controlador {
		
		/**
		 * Constructor
		 * Genera la ruta maestra de la aplicacion para ser aplicada a los archivos
		 * que se encuentran en el folder Web Publico
		 * @access private
		 */
		function __Construct() {
			parent::__Construct();
			$this->RutaBaseMaestra = implode(DIRECTORY_SEPARATOR, array(dirname(dirname(dirname(__DIR__))), 'Web'));
			$this->RutaErrores = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRoot__, 'Web'));
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Metodo Necesario para el manejo del sistema
		 */
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlApp('WebRoot', 'Web'));
			exit();
		}
		
		/**
		 * Metodo Publico
		 * Web($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false)
		 * 
		 * Este metodo tomara los parametros y consultaran la informacion correspondiente en la carpeta web
		 * de la aplicacion, se importaran los datos de estos archivos asi no se encontraran sensibles a manipulacion
		 * directa
		 * 
		 * Se encuentran 6 niveles de parametros, eso significa que tiene 5 niveles de arbol de carpetas 
		 * donde el sexto es el archivo correspondiente
		 * 
		 * Tipos de archivos soportados
		 * - JPGE
		 * - GIF
		 * - PNG
		 * - CSS
		 * - JS
		 * - TXT
		 * - PDF
		 * - XML
		 */
		public function Web($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false) {
			if($Base1 == true) {
				$RutaFisicaHTML = self::OrganizarDireccionPublica($Base1, $Base2, $Base3, $Base4, $Base5, $Base6);
				$NombreArchivo = self::NombreArchivo($Base1, $Base2, $Base3, $Base4, $Base5, $Base6);
				$RutaFisicaCompleta = implode(DIRECTORY_SEPARATOR, array($this->RutaBaseMaestra, $RutaFisicaHTML));
				$Extension = mb_strtolower(substr(strrchr($RutaFisicaHTML, '.'), 1));
				if(file_exists($RutaFisicaCompleta) == true AND $Extension <> '.htaccess' AND $Extension <> 'htaccess' AND $Extension <> 'php' AND $Extension <> 'phar' AND $Extension <> 'json') {
					$Peso = filesize($RutaFisicaCompleta);
					if(array_key_exists($Extension, array_flip(array('jpg', 'gif', 'png', 'bmp', 'css', 'js', 'txt', 'pdf', 'xml', 'swf'))) == true) {
						self::MostrarArchivo($RutaFisicaCompleta, $Peso, $NombreArchivo, $Extension);
					}
					else {
						self::DescargarArchivo($RutaFisicaCompleta, $Peso, $NombreArchivo, $Extension);
					}
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * ErroresWeb($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false)
		 * 
		 * Este metodo tomara los parametros y consultaran la informacion correspondiente en la carpeta web
		 * de la aplicacion, se importaran los datos de estos archivos asi no se encontraran sensibles a manipulacion
		 * directa
		 * 
		 * Se encuentran 6 niveles de parametros, eso significa que tiene 5 niveles de arbol de carpetas 
		 * donde el sexto es el archivo correspondiente
		 * 
		 * Tipos de archivos soportados
		 * - JPGE
		 * - GIF
		 * - PNG
		 * - CSS
		 * - JS
		 * - TXT
		 * - PDF
		 * - XML
		 */
		public function ErroresWeb($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false) {
			if($Base1 == true) {
				$RutaFisicaHTML = self::OrganizarDireccionPublica($Base1, $Base2, $Base3, $Base4, $Base5, $Base6);
				$NombreArchivo = self::NombreArchivo($Base1, $Base2, $Base3, $Base4, $Base5, $Base6);
				$RutaFisicaCompleta = implode(DIRECTORY_SEPARATOR, array($this->RutaErrores, $RutaFisicaHTML));
				$Extension = mb_strtolower(substr(strrchr($RutaFisicaHTML, '.'), 1));
				if(file_exists($RutaFisicaCompleta) == true AND $Extension <> '.htaccess' AND $Extension <> 'htaccess' AND $Extension <> 'php' AND $Extension <> 'phar' AND $Extension <> 'json') {
					$Peso = filesize($RutaFisicaCompleta);
					if(array_key_exists($Extension, array_flip(array('jpg', 'gif', 'png', 'bmp', 'css', 'js', 'txt', 'pdf', 'xml'))) == true) {
						self::MostrarArchivo($RutaFisicaCompleta, $Peso, $NombreArchivo, $Extension);
					}
					else {
						self::DescargarArchivo($RutaFisicaCompleta, $Peso, $NombreArchivo, $Extension);
					}
				}
			}
		}
				
		/**
		 * Metodo Privado
		 * NombreArchivo($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false)
		 * 
		 * Retorna el nombre del archivo
		 * @access private
		 */
		private function NombreArchivo($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false) {
			if($Base1 == true) {
				if($Base6 == true) { return trim($Base6); }
				elseif($Base5 == true) { return trim($Base5); }
				elseif($Base4 == true) { return trim($Base4); }
				elseif($Base3 == true) { return trim($Base3); }
				elseif($Base2 == true) { return trim($Base2); }
				elseif($Base1 == true) { return trim($Base1); }
			}
		}
		
		/**
		 * Metodo Privado
		 * OrganizarDireccionPublica($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false)
		 * 
		 * Organiza y genera la direccion publica a la cual se accesara a la informacion
		 * @access private
		 */
		private function OrganizarDireccionPublica($Base1 = false, $Base2 = false, $Base3 = false, $Base4 = false, $Base5 = false, $Base6 = false) {
			if($Base1 == true) {
				if($Base1 == true) { $Directorio[] = trim($Base1); }
				if($Base2 == true) { $Directorio[] = trim($Base2); }
				if($Base3 == true) { $Directorio[] = trim($Base3); }
				if($Base4 == true) { $Directorio[] = trim($Base4); }
				if($Base5 == true) { $Directorio[] = trim($Base5); }
				if($Base6 == true) { $Directorio[] = trim($Base6); }
				return implode(DIRECTORY_SEPARATOR, $Directorio);
			}
		}
		
		/**
		 * Metodo Privado
		 * MostrarArchivo($RutaFisica = false, $Peso = false, $Archivo = false, $Extension = false)
		 * 
		 * Genera la lectura del archivo remoto para mostrarlo en la vista correspondiente
		 * @access private
		 */
		private function MostrarArchivo($RutaFisica = false, $Peso = false, $Archivo = false, $Extension = false) {
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			if($Extension == 'jpg') {
				header('Content-Type: image/jpeg');
			}
			elseif($Extension == 'gif') {
				header('Content-Type: image/gif');
			}
			elseif($Extension == 'png') {
				header('Content-Type: image/png');
			}
			elseif($Extension == 'bmp') {
				header('Content-Type: image/bmp');
			}
			elseif($Extension == 'css') {
				header('Content-Type: text/css');
			}
			elseif($Extension == 'js') {
				header('Content-Type: text/javascript');
			}
			elseif($Extension == 'txt') {
				header('Content-Type: text/plain');
			}
			elseif($Extension == 'xml') {
				header('Content-Type: text/xml');
			}
			elseif($Extension == 'pdf') {
				header('Content-Type: application/pdf');
			}
			elseif($Extension == 'swf') {
				header('Content-Type: application/x-shockwave-flash');
			}
			header("Content-Length: ".$Peso);
			header('Content-Disposition: inline; filename=' . $Archivo);
			header('Content-Transfer-Encoding: binary');
			$Fopen = fopen($RutaFisica, 'rb');
			$Buffer = fread($Fopen, $Peso);
			fclose ($Fopen);
			print $Buffer;
			exit();
		}
		
		/**
		 * Metodo Privado
		 * DescargarArchivo($RutaFisica = false, $Peso = false, $Archivo = false, $Extension = false)
		 * 
		 * Genera la descarga de toda extension distintas a las aprobadas para mostrar
		 * @access private
		 */
		private function DescargarArchivo($RutaFisica = false, $Peso = false, $Archivo = false, $Extension = false) {
			if(is_dir($RutaFisica) ==false AND file_exists($RutaFisica) == true) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $Archivo);
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . $Peso);
				ob_clean();
				flush();
				readfile($RutaFisica);
				exit();
			}
		}
		
		
	}