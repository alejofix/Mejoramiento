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
	 
	class NeuralCorreoSwiftMailer {
		
		/**
		 * Valor predeterminado del formato del correo
		 */
		const HTML = 'text/html';
		
		/**
		 * Codificacion iso-8859-1
		 */
		const ISO88591 = 'iso-8859-1';
		
		/**
		 * Valor predeterminado del formato del correo
		 */
		const PLANO = 'text/plain';
		
		/**
		 * Valor predeterminado de la Aplicacion
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Codificacion UTF-8
		 */
		const UTF8 = 'utf-8';
		
		/**
		 * Constructor
		 * 
		 * Asigna valores por defecto
		 * @param $App: Aplicacion configurada en el archivo de configuracion de correo
		 * @param $Prioridad: Es la prioridad que se le dara al correo
		 * @example 1: Prioridad Urgente
		 * @example 2: Prioridad Alta
		 * @example 3: Prioridad Normal
		 * @example 4: Prioridad Baja
		 * @example 5: Prioridad Muy Baja
		 * @param $Tipo: Indica el formato del correo HTML o Plano con las constantes predefinidas de la clase
		 * @param $Codificacion: codificacion del correo por defecto UTF-8
		 */
		function __Construct($App = self::PREDETERMINADO, $Prioridad = 3, $Tipo = self::HTML, $Codificacion = self::UTF8) {
			$this->APP = $App;
			$this->BodyTipo = $Tipo;
			$this->Prioridad = $Prioridad;
			$this->Codificacion = $Codificacion;
		}
		
		/**
		 * Metodo Publico
		 * ArchivoAdjunto($RutaFisicaArchivo = false)
		 * 
		 * Adjunta un archivo al correo correspondiente
		 * no se realiza la validacion de la existencia del archivo
		 * @param $RutaFisicaArchivo: Ruta real del archivo
		 * @example c:\servidor\www\archivo.pdf
		 * @example /opt/lampp/archivo.pdf
		 */
		public function ArchivoAdjunto($RutaFisicaArchivo = false) {
			if($RutaFisicaArchivo == true) {
				$this->Adjunto[] = trim($RutaFisicaArchivo);
			}
		}
		
		/**
		 * Metodo Publico
		 * Asunto($Texto = false)
		 * 
		 * Agrega el Asunto del mensaje
		 * @param $Texto: asunto correspondiente
		 */
		public function Asunto($Texto = false) {
			$this->Subject = trim($Texto);
		}
		
		/**
		 * Metodo Publico
		 * EnviarA($Correo = false)
		 * 
		 * Agrega los correos a los cuales se enviara el correo
		 */
		public function EnviarA($Correo = false) {
			if($Correo == true) {
				$this->To[] = trim(mb_strtolower($Correo));
			}
		}
		
		/**
		 * Metodo Publico
		 * EnviarCopia($Correo = false)
		 * 
		 * Agrega los correos a los cuales se le enviara una copia
		 */
		public function EnviarCopia($Correo = false) {
			if($Correo == true) {
				$this->CC[] = trim(mb_strtolower($Correo));
			}
		}
		
		/**
		 * Metodo Publico
		 * EnviarCopiaOculta($Correo = false)
		 * 
		 * Agrega los correos a los cuales se les enviara una copia oculta
		 */
		public function EnviarCopiaOculta($Correo = false) {
			if($Correo == true) {
				$this->CCO[] = trim(mb_strtolower($Correo));
			}
		}
		
		/**
		 * Metodo Publico
		 * EnviarCorreo()
		 * 
		 * Genera el proceso del envio del correo electronico
		 * @return true o false si pudo ser enviado o no
		 */
		public function EnviarCorreo() {
			if(isset($this->Subject) == true AND isset($this->To) == true AND isset($this->From) == true AND isset($this->Body) == true) {
				self::ValidarLibreria_SwiftMailer();
				$Mensaje = self::OrganizarMensaje();
				$Configuracion = self::OrganizarConfiguracion();
				$Mail = Swift_Mailer::newInstance($Configuracion);
				$Enviando = $Mail->send($Mensaje);
				return $Enviando;
			}
			else {
				throw new NeuralException('Para Enviar el Correo Debe Tener Un Asunto, Mensaje, Destinatario, Remitente');
			}
		}
		
		/**
		 * Metodo Publico
		 * Mensaje($Texto = false)
		 * 
		 * Agrega el mensaje al cuerpo del mensaje
		 * @param $Texto: texto del mensaje
		 */
		public function Mensaje($Texto = false) {
			if($Texto == true) {
				$this->Body = $Texto;
			}
		}
		
		/**
		 * Metodo Publico
		 * MensajeAlternativo($Texto = false, $Tipo = self::PLANO)
		 * 
		 * Agrega un mensaje alternativo para navegadores que no soporte html
		 * @param $Texto: Texto Correspondiente
		 * @param $Tipo: se asigna valor por constantes HTML o PLANO
		 */
		public function MensajeAlternativo($Texto = false, $Tipo = self::PLANO) {
			if($Texto == true) {
				$this->BodyAlt = $Texto;
				$this->BodyAltTipo = $Tipo;
			}
		}
		
		/**
		 * Metodo Privado
		 * OrganizarConfiguracion()
		 * 
		 * Organiza los parametros de la configuracion seleccionada para su conexion
		 * @access private
		 */
		private function OrganizarConfiguracion() {
			$Parametros = self::ParametrosArchivoConfiguracion();
			$Configuracion = Swift_SmtpTransport::newInstance();
			$Configuracion->setHost($Parametros['Servidor']);
			if(self::ValidarParametros($Parametros['Puerto']) == true) {
				$Configuracion->setPort($Parametros['Puerto']);
			}
			if(self::ValidarParametros($Parametros['Codificacion']) == true) {
				$Configuracion->setEncryption($Parametros['Codificacion']);
			}
			if(self::ValidarParametros($Parametros['Usuario']) == true) {
				$Configuracion->setUsername($Parametros['Usuario']);
			}
			if(self::ValidarParametros($Parametros['Password']) == true) {
				$Configuracion->setPassword($Parametros['Password']);
			}
			return $Configuracion;
		}
		
		/**
		 * Metodo Privado
		 * OrganizarMensaje()
		 * 
		 * Organiza los parametros para crear el mensaje correspondiente
		 * @access private
		 */
		private function OrganizarMensaje() {
			$Mensaje = Swift_Message::newInstance();
			$Mensaje->setCharset($this->Codificacion);
			$Mensaje->setSubject($this->Subject);
			$Mensaje->setFrom($this->From);
			$Mensaje->setTo($this->To);
			if(isset($this->CC) == true) { $Mensaje->setCc($this->CC); }
			if(isset($this->CCO) == true) { $Mensaje->setBcc($this->CCO); }
			$Mensaje->setBody($this->Body, $this->BodyTipo);
			if(isset($this->BodyAlt) == true) { $Mensaje->addPart($this->BodyAlt, $this->BodyAltTipo); }
			
			if(isset($this->Adjunto) == true) {
				foreach ($this->Adjunto AS $Adjunto) {
					$Mensaje->attach(Swift_Attachment::fromPath($Adjunto));
				}
			}
			$Mensaje->setPriority($this->Prioridad);
			return $Mensaje;
		}
		
		/**
		 * Metodo Privado
		 * ParametrosArchivoConfiguracion()
		 * 
		 * Lee el archivo de configuracion y regresa los parametros de configuracion
		 * @access private
		 */
		private function ParametrosArchivoConfiguracion() {
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Correo.json'));
			if(file_exists($Archivo) == true) {
				$Parametros = json_decode(file_get_contents($Archivo), true);
				if($Parametros == true) {
					if(array_key_exists($this->APP, $Parametros) == true) {
						return $Parametros[$this->APP];
					}
					else {
						throw new NeuralException('La Aplicación No Existe en el Archivo de Configuración de Correo');
					}
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración de Correo No es Correcto');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de Correo No Existe');
			}
		}
		
		/**
		 * Metodo Publico
		 * Remitente($Nombre = false, $Correo = false)
		 * 
		 * Agrega el remitente del correo correspondiente
		 * @param $Nombre: Nombre que se visualizara
		 * @param $Correo: correo del remitente
		 */
		public function Remitente($Nombre = false, $Correo = false) {
			$this->From = array($Correo => $Nombre);
		}
		
		/**
		 * Metodo Privado
		 * ValidarLibreria_SwiftMailer()
		 * 
		 * Valida si fue agregada la libreria de lo contrario la agrega
		 * @access private
		 */
		private function ValidarLibreria_SwiftMailer() {
			if(class_exists('Swift') == false) {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'SwiftMailer', 'lib', 'swift_required.php'))) == true) {
					require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'SwiftMailer', 'lib', 'swift_required.php'));
				}
				else {
					throw new NeuralException('El Proveedor SwiftMailer No Existe');
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * ValidarParametros($Parametro)
		 * 
		 * Determina si hay que agregar o no el parametro evaluado
		 * @access private
		 */
		private function ValidarParametros($Parametro) {
			if(is_bool($Parametro) == true) {
				return false;
			}
			elseif(is_array($Parametro) == true) {
				return false;
			}
			else {
				return true;
			}
		}
	}