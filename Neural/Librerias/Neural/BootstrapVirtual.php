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

	class BootstrapVirtual extends ErrorDesarrollo {
		
		/**
		 * Listado de Carpetas que dependen de la aplicacion
		 * @access private
		 */
		private static $CarpetasBase = array('App', 'Web');
		
		/**
		 * Listado de Carpetas que dependen de la aplicacion
		 * @access private
		 */
		private static $CarpetasBaseApp = array('Ayudas', 'Configuracion', 'Modulos', 'MVC', 'Proveedores', 'Temporales');
		
		/**
		 * Listado de Carpetas que dependen de la aplicacion
		 * @access private
		 */
		private static $CarpetasMVC = array('Controladores', 'Modelos', 'Vistas');
		
		/**
		 * Constante Predeterminada
		 * @access private
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Extension de Archivos PHP
		 * @access private
		 */
		const EXT_PHP = '.php';
		
		/**
		 * Extension de Archivos JSON
		 * @access private
		 */
		const EXT_JSON = '.json';
		
		/**
		 * Constante Inicial
		 * @access private
		 */
		const INDEX = 'Index';
		
		/**
		 * Alias del Modelo
		 * @access private
		 */
		const ALIAS_MODELO = '_Modelo';
		
		/**
		 * Constructor
		 * 
		 * Genera la carga de la informacion necesaria
		 * @param $Aplicacion: aplicacion configurada
		 * @access private
		 */
		function __Construct($Aplicacion = false) {
			$this->App = ($Aplicacion == true) ? $Aplicacion : self::PREDETERMINADO;
			$this->ConfgiAccesos = self::LeerConfiguracion('Acceso');
			$this->ConfgiErrores = self::LeerConfiguracion('Errores');
			
		}
		
		/**
		 * Metodo Privado
		 * AppBaseAplicacion()
		 * 
		 * Validacion de la aplicacion correspondiente
		 * @access private
		 */
		private function AppBaseAplicacion() {
			return (array_key_exists($this->App, $this->ConfgiAccesos) == true) ? true : false;
		}
		
		/**
		 * Metodo Privado
		 * AppBaseEstructuraValidacion($Carpeta = self::PREDETERMINADO)
		 * 
		 * Genera la validacion de la estructura base de la aplicacion
		 * @access private
		 */
		private function AppBaseEstructuraValidacion($Carpeta = self::PREDETERMINADO) {
			if(self::AppBaseExistenciaCarpeta($Carpeta) == true) {
				if(self::AppBaseExistenciaEstructuraGeneral($Carpeta) == true) {
					if(self::AppBaseExistenciaEstructuraGeneralApp($Carpeta) == true) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					parent::AsignarValor('Titulo', 'Carpetas de la Aplicación: <br /><strong>'.$this->App.'</strong> No Existen');
					parent::AsignarValor('Informacion', 'La estructura interna de la aplicación no esta completa o inexistente, realice la validación de la estructura correspondiente de las diferentes Carpetas: <h2>App</h2><h5>'.implode(', ', self::$CarpetasBase).'</h5>');
					parent::AsignarValor('Aplicacion', $this->App);
					parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
				}
			}
			else {
				parent::AsignarValor('Titulo', 'Carpetas de la Aplicación: <br /><strong>'.$this->App.'</strong> No Existen');
				parent::AsignarValor('Informacion', 'No Existe la Carpeta de la Aplicación');
				parent::AsignarValor('Aplicacion', $this->App);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppBaseExistenciaCarpeta($Carpeta = self::PREDETERMINADO)
		 * 
		 * Genera la validacion de la existencia de la aplicacion
		 * @access private
		 */
		private function AppBaseExistenciaCarpeta($Carpeta = self::PREDETERMINADO) {
			return (is_dir(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta))) == true) ? true : false;
		}
		
		/**
		 * Metodo Privado
		 * AppBaseExistenciaEstructuraGeneral($Carpeta = self::PREDETERMINADO)
		 * 
		 * Genera la validacion de la existencia de la estructura general
		 * @access private
		 */
		private function AppBaseExistenciaEstructuraGeneral($Carpeta = self::PREDETERMINADO) {
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta));
			foreach (self::$CarpetasBase AS $Llave => $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($Directorio, $Folder))) == false) {
					$Data[] = false;
				}
			}
			return (isset($Data) == true) ? false : true;
		}
		
		/**
		 * Metodo Privado
		 * AppBaseExistenciaEstructuraGeneralApp($Carpeta = self::PREDETERMINADO)
		 * 
		 * Genera la validacion de la existencia de la estuctura general de la app
		 * @access private
		 */
		private function AppBaseExistenciaEstructuraGeneralApp($Carpeta = self::PREDETERMINADO) {
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App'));
			foreach (self::$CarpetasBaseApp AS $Llave => $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($Directorio, $Folder))) == false) {
					$Data[] = false;
				}
			}
			return (isset($Data) == true) ? false : true;
		}
		
		/**
		 * Metodo Publico
		 * AppCargar()
		 * 
		 * Genera el proceso de la carga de la aplicacion
		 * @access private
		 */
		public function AppCargar() {
			try {
				self::AppSeleccion(self::ServidorModReWrite());
			}
			catch(NeuralException $Error) {
				echo $Error->__toString();
			}
		}
		
		/**
		 * Metodo Privado
		 * AppEjecutarProcesar($Carpeta = self::PREDETERMINADO, $Controller = self::INDEX, $Metodo = self::INDEX, $Repeticiones = 2, $RutaModelos = false, $ModReWrite = array())
		 * 
		 * Genera el proceso de ejecucion de la aplicacion
		 * @access private
		 */
		private function AppEjecutarProcesar($Carpeta = self::PREDETERMINADO, $Controller = self::INDEX, $Metodo = self::INDEX, $Repeticiones = 2, $RutaModelos = false, $ModReWrite = array()) {
			if(self::AppMetodosPublicosClase($Controller, $Metodo) == true) {
				self::ZyosCargarParametrosServidor($Carpeta);
				self::ZyosCargarProveedoresApp($Carpeta);
				self::ZyosCargarAyudas($Carpeta);
				$Controlador = new $Controller;
				$Controlador->CargarModelo($RutaModelos, $Controller, self::ALIAS_MODELO, self::EXT_PHP);
				if(isset($ModReWrite[$Repeticiones]) == true) {
					$Datos = self::AppOrganizarParametrosControlador($Repeticiones, $ModReWrite);
					eval("\$Controlador->\$Metodo(".$Datos.");");
				}
				else {
					$Controlador->$Metodo(false);
				}
			}
			elseif(method_exists($Controller, $Metodo) == true) {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado Es Privado ó Protegido');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado de la clase correspondiente tiene una visibilidad Privada ó Protegida.');
				parent::AsignarValor('Aplicacion', $this->App);
				if($Repeticiones == 3) {
					parent::AsignarValor('Modulo', $ModReWrite[0]);
				}
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
			else {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado No Existe');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado no existe en la clase correspondiente.');
				parent::AsignarValor('Aplicacion', $this->App);
				if($Repeticiones == 3) {
					parent::AsignarValor('Modulo', $ModReWrite[0]);
				}
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppMetodosPublicosClase($Clase = false, $Metodo = false)
		 * 
		 * Valida la existencia del metodo del controlador correspondiente
		 * @access private
		 */
		private function AppMetodosPublicosClase($Clase = false, $Metodo = false) {
			$Matriz = get_class_methods($Clase);
			return (array_key_exists($Metodo, array_flip($Matriz)) == true) ? true : false;
		}
		
		/**
		 * Metodo Privado
		 * AppOrganizarParametrosControlador($Cantidad, $ModReWrite)
		 * 
		 * Organiza los parametros correspondientes para pasarlos hacia el metodo del controlador
		 * @access private
		 */
		private function AppOrganizarParametrosControlador($Cantidad = 3, $ModReWrite = false) {
			foreach ($ModReWrite AS $Puntero => $Parametro) {
				if($Puntero >= $Cantidad) {
					$Lista[] = '$ModReWrite['.$Puntero.']';
				}
			}
			return implode(', ', $Lista);
		}
		
		/**
		 * Metodo Privado
		 * AppSeleccion($ModReWrite = array())
		 * 
		 * Genera la seleccion de tipo de aplicacion
		 * @access private
		 */
		private function AppSeleccion($ModReWrite = array()) {
			if(self::AppBaseAplicacion() == true) {
				$Carpeta = $this->ConfgiAccesos[$this->App]['Carpeta'];
				if(self::AppBaseEstructuraValidacion($Carpeta) == true) {
					self::AppSeleccionarModuloControlador($Carpeta, $ModReWrite);
				}
				else {
					parent::AsignarValor('Titulo', 'Carpetas de la Aplicación: <br /><strong>'.$this->App.'</strong> No Existen');
					parent::AsignarValor('Informacion', 'No Existe la Estructura Interna de la Aplicación, recuerde que la aplicación require de la existencias de las siguientes carpetas: <h5>'.implode(', ', self::$CarpetasBaseApp).'</h5>');
					parent::AsignarValor('Aplicacion', $this->App);
					parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
				}
			}
			else {
				parent::AsignarValor('Titulo', 'La Aplicación: <br /><strong>'.$this->App.'</strong> No Existe');
				parent::AsignarValor('Informacion', 'La aplicación Seleccionada no existe dentro del Archivo de Configuración de Acceso');
				parent::AsignarValor('Aplicacion', $this->App);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppSeleccionarModuloControlador($Carpeta = self::PREDETERMINADO, $ModReWrite = false)
		 * 
		 * Genera el proceso de selccion de app
		 * @access private
		 */
		private function AppSeleccionarModuloControlador($Carpeta = self::PREDETERMINADO, $ModReWrite = false) {
			$ModuloControlador = (isset($ModReWrite[0]) == true) ? $ModReWrite[0] : self::INDEX;
			if(self::AppValidarModuloActivo($Carpeta, $ModuloControlador) == true) {
				self::ModuloValidarExistencia($Carpeta, $ModuloControlador, $ModReWrite);
			}
			else {
				self::ControladorValidarExistencia($Carpeta, $ModuloControlador, $ModReWrite);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppValidarModuloActivo($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX)
		 * 
		 * Genera la validacion de modulo activo
		 * @access private
		 */
		private function AppValidarModuloActivo($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX) {
			$ConfigModulo = self::LeerConfiguracionJsonApp($Carpeta, 'Modulos');
			if($ConfigModulo['Configuracion']['Activo'] == true) {
				if(array_key_exists($Modulo, $ConfigModulo['Modulos']) == true) {
					if($ConfigModulo['Modulos'][$Modulo] == true) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		
		/**
		 * Metodo Privado
		 * ControladorEjecutar($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX, $ModReWrite = false)
		 * 
		 * Genera la llamada e ejecucion del controlador
		 * @access private
		 */
		private function ControladorEjecutar($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX, $ModReWrite = false) {
			$Metodo = (isset($ModReWrite[1]) == true) ? $ModReWrite[1] : self::INDEX;
			$RutaModelos = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'MVC', 'Modelos'));
			self::AppEjecutarProcesar($Carpeta, $Controlador, $Metodo, 2, $RutaModelos, $ModReWrite);
		}
		
		/**
		 * Metodo Privado
		 * ControladorExistenciaControlador($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX)
		 * 
		 * Genera la validacion de la existencia del controlador
		 * @access private
		 */
		private function ControladorExistenciaControlador($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX) {
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'MVC', 'Controladores', $Controlador.self::EXT_PHP));
			if(file_exists($Archivo) == true) {
				require $Archivo;
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Metodo Privado
		 * ControladorValidarExistencia($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX, $ModReWrite = array())
		 * 
		 * Genera la carga del controlador correspondiente
		 * @access private
		 */
		private function ControladorValidarExistencia($Carpeta = self::PREDETERMINADO, $Controlador = self::INDEX, $ModReWrite = array()) {
			if(self::ControladorExistenciaControlador($Carpeta, $Controlador) == true) {
				self::ControladorEjecutar($Carpeta, $Controlador, $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'El Controlador: <br /><strong>'.$Controlador.'</strong> No Existe');
				parent::AsignarValor('Informacion', 'El Controlador Seleccionado No Existe en la Carpeta MVC');
				parent::AsignarValor('Aplicacion', $this->App);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * LeerConfiguracion($Archivo = false)
		 * 
		 * Genera la lectura de los archivos de configuracion
		 * @access private
		 */
		private function LeerConfiguracion($Archivo = false) {
			$ArchivoConfig = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', $Archivo.self::EXT_JSON));
			if(file_exists($ArchivoConfig) == true) {
				return self::LeerConfiguracionJson($ArchivoConfig);
			}
			else {
				throw new NeuralException('El Archivo de Configuración: '.$Archivo.' No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * LeerConfiguracionJson($Archivo = false)
		 * 
		 * Genera la lectura del archivo de configuracion
		 * @access private
		 */
		private function LeerConfiguracionJson($Archivo = false) {
			$Datos = json_decode(file_get_contents($Archivo), true);
			if($Datos == true) {
				return $Datos;
			}
			else {
				throw new NeuralException('El Formato del Archivo de Configuración No Es Correcto');
			}
		}
		
		/**
		 * Metodo Privado
		 * LeerConfiguracionJsonApp($Carpeta = self::PREDETERMINADO, $Archivo = false)
		 * 
		 * Genera el proceso interno de lectura de archivo de config app
		 * @access private
		 */
		private function LeerConfiguracionJsonApp($Carpeta = self::PREDETERMINADO, $Archivo = false) {
			$File = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Configuracion', $Archivo.self::EXT_JSON));
			if(file_exists($File) == true) {
				$Data = json_decode(file_get_contents($File), true);
				if($Data == true) {
					return $Data;
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración: '.$Archivo.' Su Formato No Es Correcto');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de la Aplicación: '.$Archivo. ' No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloEjecutar($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $ModReWrite = false)
		 * 
		 * Genera el proceso de ejecucion del modulo
		 * @access private
		 */
		private function ModuloEjecutar($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $ModReWrite = false) {
			$Controlador = (isset($ModReWrite[1]) == true) ? $ModReWrite[1] : self::INDEX;
			$Metodo = (isset($ModReWrite[2]) == true) ? $ModReWrite[2] : self::INDEX;
			$RutaModelos = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Modulos', $Modulo, 'Modelos'));
			if(self::ModuloValidarExistenciaControlador($Carpeta, $Modulo, $Controlador) == true) {
				self::AppEjecutarProcesar($Carpeta, $Controlador, $Metodo, 3, $RutaModelos, $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'El Controlador: <br /><strong>'.$Controlador.'</strong> No Existe');
				parent::AsignarValor('Informacion', 'El Controlador Seleccionado No Existe en el Modulo');
				parent::AsignarValor('Aplicacion', $this->App);
				parent::AsignarValor('Modulo', $ModReWrite[0]);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarExistencia($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $ModReWrite = false)
		 * 
		 * Genera proceso de validacion de modulo y existencia
		 * @access private
		 */
		private function ModuloValidarExistencia($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $ModReWrite = false) {
			$Ruta = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Modulos', $Modulo));
			if(is_dir($Ruta) == true) {
				if(self::ModuloValidarExistenciaEstructura($Ruta) == true) {
					self::ModuloEjecutar($Carpeta, $Modulo, $ModReWrite);
				}
				else {
					parent::AsignarValor('Titulo', 'Carpetas del Modulo: <br /><strong>'.$Modulo.'</strong> No Existen');
					parent::AsignarValor('Informacion', 'No Existe la Estructura Interna del Modulo, recuerde que la aplicación require de la existencias de las siguientes carpetas: <h5>'.implode(', ', self::$CarpetasMVC).'</h5>');
					parent::AsignarValor('Aplicacion', $this->App);
					parent::AsignarValor('Modulo', $Modulo);
					parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
				}
			}
			else {
				parent::AsignarValor('Titulo', 'La Carpetas del Modulo: <br /><strong>'.$Modulo.'</strong> No Existe');
				parent::AsignarValor('Informacion', 'No Existe la Carpeta del Modulo Seleccionado');
				parent::AsignarValor('Aplicacion', $this->App);
				parent::AsignarValor('Modulo', $Modulo);
				parent::SeleccionEjecutar($this->ConfgiAccesos, $this->ConfgiErrores[404], $this->App);
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarExistenciaControlador($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $Controlador = self::INDEX)
		 * 
		 * Genera la validacion de existencia del controlador
		 * @access private
		 */
		private function ModuloValidarExistenciaControlador($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $Controlador = self::INDEX) {
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Modulos', $Modulo, 'Controladores', $Controlador.self::EXT_PHP));
			if(file_exists($Archivo) == true) {
				require $Archivo;
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarExistenciaEstructura($Ruta = false)
		 * 
		 * Genera la validacion de la estructura
		 * @access private
		 */
		private function ModuloValidarExistenciaEstructura($Ruta = false) {
			foreach (self::$CarpetasMVC AS $Llave => $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($Ruta, $Folder))) == false) {
					$Data[] = false;
				}
			}
			return (isset($Data) == true) ? false : true;
		}
		
		/**
		 * Metodo Privado
		 * ServidorModReWrite()
		 * 
		 * Genera la lectura de los parametros de apache para el manejo de parametros
		 * @access private
		 */
		private function ServidorModReWrite() {
			$Url = (isset($_GET['Url'])) ? trim($_GET['Url']) : self::INDEX;
			if($Url != null) {
				$Url = strip_tags(rtrim($Url, '/'));
				$Array = explode('/', $Url);
				return (empty($Array[0]) == true) ? array(self::INDEX) : $Array;
			}
			else {
				throw new NeuralException('El Mod_ReWrite de Apache No Se Encuentra Activo');
			}
		}
		
		/**
		 * Metodo Privado
		 * ZyosCargarAyudas($Carpeta = false)
		 * 
		 * Genera la carga de las ayudas de la aplicacion
		 * @access private
		 */
		private function ZyosCargarAyudas($Carpeta = false) {
			if($Carpeta == true) {
				$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Ayudas'));
				if(is_dir($Directorio) == true) {
					if($ListadoAyudas = opendir($Directorio)) {
						while (($Archivo = readdir($ListadoAyudas)) !== false){
							if($Archivo <> '.' AND $Archivo <> '..' AND $Archivo <> '.htaccess') {
								require implode(DIRECTORY_SEPARATOR, array($Directorio, $Archivo));
							}
						}
						closedir($ListadoAyudas);
					}
				}
				else {
					throw new NeuralException('El Directorio de Ayudas de la Aplicación No Existe');
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * ZyosCargarParametrosServidor($Carpeta = false)
		 * 
		 * Carga y aplica las configuraciones adicionales correspondiente
		 * @access private
		 */
		private function ZyosCargarParametrosServidor($Carpeta = false) {
			if($Carpeta == true) {
				$ConfigServidor = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Configuracion', 'Servidor.json'));
				if(file_exists($ConfigServidor) == true) {
					$Parametros = json_decode(file_get_contents($ConfigServidor), true);
					if($Parametros == true) {
						foreach ($Parametros['PHPini'] AS $PHPini_N => $PHPini_D) {
							if($PHPini_D['Habilitado'] == true) {
								ini_set($PHPini_D['Data']['Parametro'], $PHPini_D['Data']['Valor']);
							}
						}
						foreach ($Parametros['Cabeceras'] AS $Cabeceras_N => $Cabeceras_D) {
							if($Cabeceras_D['Habilitado'] == true) {
								header($Cabeceras_D['Data']);
							}
						}
					}
					else {
						throw new NeuralException('El Formato del Archivo de Configuración de Parametros del Servidor en la Aplicación No Es Correcto');
					}
				}
				else {
					throw new NeuralException('El Archivo de Parametros del Servidor Dentro de la Aplicación No Existe');
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * ZyosCargarProveedoresApp($Carpeta = self::PREDETERMINADO)
		 * 
		 * Genera la carga de los proveedores configurados
		 * @access private
		 */
		private function ZyosCargarProveedoresApp($Carpeta = self::PREDETERMINADO) {
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Proveedores'));
			$Proveedores = self::LeerConfiguracionJsonApp($Carpeta, 'Proveedores');
			if($Proveedores['Configuracion']['Activo'] == true) {
				foreach($Proveedores['Paquetes'] AS $Nombre => $Matriz) {
					if($Matriz['Estado'] == true) {
						$Archivo = implode(DIRECTORY_SEPARATOR, array_merge(array($Directorio), $Matriz['Ubicacion']));
						if(file_exists($Archivo) == true) {
							require $Archivo;
						}
					}
				}
			}
			unset($Carpeta, $Directorio, $Proveedores, $Archivo);
		}
		
		/**
		 * Destructor
		 * 
		 * Genera la limpieza de datos de pre carga de informacion
		 * @access private
		 */
		function __destruct() {
			gc_enable();
			$this->ConfgiAccesos;
			$this->ConfgiErrores;
			gc_collect_cycles();
			gc_disable();
		}
	}