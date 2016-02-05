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
	
	class Bootstrap extends ErrorDesarrollo {
		
		/**
		 * Carpeta de la Aplicacion
		 * @access private
		 */
		private static $CarpetaApp = 'App';
		
		/**
		 * Matriz de carpetas de la App
		 * @access private
		 */
		private static $FoldersApp = array('Ayudas', 'Configuracion', 'Modulos', 'MVC', 'Proveedores', 'Temporales');
		
		/**
		 * Matriz de carpetas de MVC
		 * @access private
		 */
		private static $FoldersAppMVC = array('Controladores', 'Modelos', 'Vistas');
		
		/**
		 * Matriz de carpetas de Modulo
		 * @access private
		 */
		private static $FoldersAppModulo = array('Controladores', 'Modelos', 'Vistas');
		
		/**
		 * Alias para los modelos
		 * @access private
		 */
		const ALIAS_MODELO = '_Modelo';
		
		/**
		 * Extension JSON
		 * @access private
		 */
		const EXT_JSON = '.json';
		
		/**
		 * Extension PHP
		 * @access private
		 */
		const EXT_PHP = '.php';
		
		/**
		 * Constante Index
		 * @access private
		 */
		const INDEX = 'Index';
		
		/**
		 * Configuracion de Mod Rewrite
		 * @access private
		 */
		const MODREWRITE_ARCHIVO = '{"Set" : "index.php", "Request" : "Url"}';
		
		/**
		 * Configuracion de Mod Rewrite
		 * @access private
		 */
		const MODREWRITE_HTACCESS = '{"Set" : ".htaccess", "Request" : "Url"}';
		
		/**
		 * Constante predeterminado
		 * @access private
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Metodo Magico
		 * __Construct()
		 * 
		 * Genera la carga de los archivos de configuracion
		 * @access private
		 */
		function __Construct() {
			$this->MatrizAccesos = self::ServidorCargarConfiguracion('Acceso', self::EXT_JSON);
			$this->MatrizErrores = self::ServidorCargarConfiguracion('Errores', self::EXT_JSON);
		}
		
		/**
		 * Metodo Publico
		 * AppCargar()
		 * 
		 * Genera la carga de la aplicacion
		 * @access private
		 */
		public function AppCargar() {
			try {
				self::AppValidacion(self::ServidorModReWrite());
			}
			catch(NeuralException $Error) {
				echo $Error->__toString();
			}
		}
		
		/**
		 * Metodo Privado
		 * AppCargarConfiguracion($Carpeta = false, $Archivo = false, $Extension = self::EXT_JSON)
		 * 
		 * Genera la carga de las configuraciones de la aplicacion
		 * @access private
		 */
		private function AppCargarConfiguracion($Carpeta = false, $Archivo = false, $Extension = self::EXT_JSON) {
			$File = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, self::$CarpetaApp, self::$FoldersApp[1], $Archivo.$Extension));
			if(file_exists($File) == true) {
				if(json_decode(file_get_contents($File), true) == true) {
					return json_decode(file_get_contents($File), true);
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración: '.$Archivo.', No es Correcto, Realizar la Validación del Formato Correspondiente');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración: '.$Archivo.', No Existe');
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
		 * AppSeleccionModuloControlador($MatrizAcceso = false, $ModReWrite = false, $ConfigModulos = false)
		 * 
		 * Selecciona el modulo o controlador para cargar
		 * @access private
		 */
		private function AppSeleccionModuloControlador($MatrizAcceso = false, $ModReWrite = false, $ConfigModulos = false) {
			if(self::ModuloValidarSeleccion($Modulo = (isset($ModReWrite[1]) == true ) ? $ModReWrite[1] : self::INDEX, $ConfigModulos) == true) {
				self::ModuloCargarModulo($MatrizAcceso, $ModReWrite);
			}
			else {
				self::BaseCargarControlador($MatrizAcceso, $ModReWrite);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppValidacion($ModReWrite = array())
		 * 
		 * valida la existencia de la aplicacion
		 * @access private
		 */
		private function AppValidacion($ModReWrite = array()) {
			if(array_key_exists($ModReWrite[0], $this->MatrizAccesos) == true) {
				self::AppValidarExistenciaCarpeta($this->MatrizAccesos[$ModReWrite[0]], $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'Aplicación: <strong>'.$ModReWrite[0].'</strong> No Existe');
				parent::AsignarValor('Informacion', 'Actualmente la aplicación seleccionada no se encuentra en el archivo de configuración, validar la aplicación correspondiente en existencia y en el archivo de configuración');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], self::PREDETERMINADO);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppValidarExistenciaCarpeta($MatrizAcceso = false, $ModReWrite = false)
		 * 
		 * Valida la existencia de la carpeta del proyecto
		 * @access private
		 */
		private function AppValidarExistenciaCarpeta($MatrizAcceso = false, $ModReWrite = false) {
			if(is_dir(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $MatrizAcceso['Carpeta']))) == true) {
				if(self::AppValidarExistenciaForlders($MatrizAcceso['Carpeta']) == true) {
					self::ModoErroresZyos($ModReWrite);
					self::AppSeleccionModuloControlador($MatrizAcceso, $ModReWrite, self::AppCargarConfiguracion($MatrizAcceso['Carpeta'], 'Modulos', self::EXT_JSON));
				}
				else {
					parent::AsignarValor('Titulo', 'Carpetas de la Aplicación: <br /><strong>'.$ModReWrite[0].'</strong> No Existen');
					parent::AsignarValor('Informacion', 'La estructura interna de la aplicación no esta completa o inexistente, realice la validación de la estructura correspondiente de las diferentes Carpetas: <h2>App</h2><h5>'.implode(', ', self::$FoldersApp).'</h5>');
					parent::AsignarValor('Aplicacion', $ModReWrite[0]);
					parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
				}
			}
			else {
				parent::AsignarValor('Titulo', 'Carpeta de Aplicación: <br /><strong>'.$ModReWrite[0].'</strong> No Existe');
				parent::AsignarValor('Informacion', 'La aplicación seleccionada no es posible ubicar la carpeta correspondiente para ejecutarla, por favor realice la validación de la existencia de la carpeta correspondiente y/ó el archivo de configuración');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * AppValidarExistenciaForlders($Carpeta = false)
		 * 
		 * realiza la validacion de las carpetas de la aplicacion
		 * @access private
		 */
		private function AppValidarExistenciaForlders($Carpeta = false) {
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, self::$CarpetaApp));
			foreach (self::$FoldersApp AS $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($Directorio, $Folder))) == false) {
					$Error[] = $Folder;
				}
			}
			return (isset($Error) == true) ? false : true;
		}
		
		/**
		 * Metodo Privado
		 * BaseCargarControlador($MatrizAcceso = array(), $ModReWrite = array())
		 * 
		 * Genera el proceso de carga del controlador
		 * @access private
		 */
		private function BaseCargarControlador($MatrizAcceso = array(), $ModReWrite = array()) {
			if(self::BaseValidarEstructura($MatrizAcceso['Carpeta']) == true) {
				self::BaseValidarControlador($MatrizAcceso, $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'Carpetas MVC de la Aplicación: <br /><strong>'.$ModReWrite[0].'</strong> No Existen');
				parent::AsignarValor('Informacion', 'La aplicación seleccionada no es posible ubicar las carpetas correspondiente de la carpeta MVC para ejecutarla, por favor realice la validación de la existencia de la carpeta correspondiente: <h2>MVC</h2><h5>'.implode(', ', self::$FoldersAppMVC).'</h5>');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * BaseEjecutar($Carpeta = self::PREDETERMINADO, $Controller = self::INDEX, $Metodo = self::INDEX, $ModReWrite = array())
		 * 
		 * Ejecuta la carga del controlador
		 * @access private
		 */
		private function BaseEjecutar($Carpeta = self::PREDETERMINADO, $Controller = self::INDEX, $Metodo = self::INDEX, $ModReWrite = array()) {
			if(self::AppMetodosPublicosClase($Controller, $Metodo) == true) {
				self::ZyosCargarParametrosServidor($Carpeta);
				self::ZyosCargarProveedoresApp($Carpeta);
				self::ZyosCargarProveedoresApp($Carpeta);
				self::ZyosCargarAyudas($Carpeta);
				$Controlador = new $Controller;
				$Controlador->CargarModelo(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, self::$CarpetaApp, self::$FoldersApp[3], self::$FoldersAppModulo[1])), $Controller, self::ALIAS_MODELO, self::EXT_PHP);
				if(isset($ModReWrite[3]) == true) {
					$Datos = self::AppOrganizarParametrosControlador(3, $ModReWrite);
					eval("\$Controlador->\$Metodo(".$Datos.");");
				}
				else {
					$Controlador->$Metodo(false);
				}
			}
			elseif(method_exists($Controller, $Metodo) == true) {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado Es Privado ó Protegido');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado de la clase correspondiente tiene una visibilidad Privada ó Protegida.');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
			else {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado No Existe');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado no existe en la clase correspondiente.');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * BaseValidarControlador($MatrizAcceso, $ModReWrite)
		 * 
		 * Genera la validacion del controlador
		 * @access private
		 */
		private function BaseValidarControlador($MatrizAcceso, $ModReWrite) {
			$Controlador = (isset($ModReWrite[1])) ? $ModReWrite[1] : self::INDEX;
			$Metodo = (isset($ModReWrite[2])) ? $ModReWrite[2] : self::INDEX;
			$File =  implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $MatrizAcceso['Carpeta'], self::$CarpetaApp, self::$FoldersApp[3], self::$FoldersAppMVC[0], $Controlador.self::EXT_PHP));
			if(file_exists($File) == true) {
				require $File;
				self::BaseEjecutar($MatrizAcceso['Carpeta'], $Controlador, $Metodo, $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'El Controlador de la Aplicación No Existe');
				parent::AsignarValor('Informacion', 'El controlador seleccionado no existe en la carpeta de controladores del MVC de la aplicación.');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Controlador', $Controlador);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * BaseValidarEstructura($Carpeta = false)
		 * 
		 * Proceso de validacion de la estructura
		 * @access private
		 */
		private function BaseValidarEstructura($Carpeta = false) {
			$File = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, self::$CarpetaApp, self::$FoldersApp[3]));
			foreach (self::$FoldersAppMVC AS $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($File, $Folder))) == false) {
					$Error[] = $Folder;
				}
			}
			return (isset($Error)== true) ? false : true;
		}
		
		/**
		 * Metodo Privado
		 * ModuloCargarModulo($MatrizAcceso = false, $ModReWrite = false)
		 * 
		 * Proceso de carga del modulo
		 * @access private
		 */
		private function ModuloCargarModulo($MatrizAcceso = false, $ModReWrite = false) {
			$Modulo = (isset($ModReWrite[1]) == true) ? $ModReWrite[1] : self::INDEX;
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $MatrizAcceso['Carpeta'], self::$CarpetaApp, self::$FoldersApp[2], $Modulo));
			if(is_dir($Directorio) == true) {
				if(self::ModuloValidarEstructura($Directorio) == true) {
					self::ModuloValidarControlador($MatrizAcceso, $ModReWrite, $Directorio, $Modulo);
				}
				else {
					parent::AsignarValor('Titulo', 'Las Carpetas del Modulo No Existen');
					parent::AsignarValor('Informacion', 'Las carpetas del modulo no existen dentro del Modulo: <h2>'.$Modulo.'</h2><h5>'.implode(', ', self::$FoldersAppModulo).'</h5>');
					parent::AsignarValor('Aplicacion', $ModReWrite[0]);
					parent::AsignarValor('Modulo', $Modulo);
					parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
				}
			}
			else {
				parent::AsignarValor('Titulo', 'La Carpeta del Modulo No Existe');
				parent::AsignarValor('Informacion', 'La carpeta del Modulo correspondiente no Existe dentro de la aplicación');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Modulo', $Modulo);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloEjecutar($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $Controller = self::INDEX, $Metodo = self::INDEX, $ModReWrite = array())
		 * 
		 * Carga el controlador correspondiente
		 * @access private
		 */
		private function ModuloEjecutar($Carpeta = self::PREDETERMINADO, $Modulo = self::INDEX, $Controller = self::INDEX, $Metodo = self::INDEX, $ModReWrite = array()) {
			if(self::AppMetodosPublicosClase($Controller, $Metodo) == true) {
				self::ZyosCargarParametrosServidor($Carpeta);
				self::ZyosCargarProveedoresApp($Carpeta);
				self::ZyosCargarAyudas($Carpeta);
				$Controlador = new $Controller;
				$Controlador->CargarModelo(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, self::$CarpetaApp, self::$FoldersApp[2], $Modulo, self::$FoldersAppModulo[1])), $Controller, self::ALIAS_MODELO, self::EXT_PHP);
				if(isset($ModReWrite[4]) == true) {
					$Datos = self::AppOrganizarParametrosControlador(4, $ModReWrite);
					eval("\$Controlador->\$Metodo(".$Datos.");");
				}
				else {
					$Controlador->$Metodo(false);
				}
			}
			elseif(method_exists($Controller, $Metodo) == true) {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado Es Privado ó Protegido');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado de la clase correspondiente tiene una visibilidad Privada ó Protegida.');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Modulo', $Modulo);
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
			else {
				parent::AsignarValor('Titulo', 'El Metodo Seleccionado No Existe');
				parent::AsignarValor('Informacion', 'El Metodo que ha seleccionado no existe en la clase correspondiente.');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Modulo', $Modulo);
				parent::AsignarValor('Controlador', $Controller);
				parent::AsignarValor('Metodo', $Metodo);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarControlador($MatrizAcceso, $ModReWrite, $Directorio, $Modulo)
		 * 
		 * Genera la validacion del controlador correspondiente
		 * @access private
		 */
		private function ModuloValidarControlador($MatrizAcceso, $ModReWrite, $Directorio, $Modulo) {
			$Controlador = (isset($ModReWrite[2]) == true) ? $ModReWrite[2] : self::INDEX;
			$Metodo = (isset($ModReWrite[3]) == true) ? $ModReWrite[3] : self::INDEX;;
			$File = implode(DIRECTORY_SEPARATOR, array($Directorio, self::$FoldersAppModulo[0],$Controlador.self::EXT_PHP));
			if(file_exists($File) == true) {
				require $File;
				self::ModuloEjecutar($MatrizAcceso['Carpeta'], $Modulo, $Controlador, $Metodo, $ModReWrite);
			}
			else {
				parent::AsignarValor('Titulo', 'El Controlado del Modulo No Existe');
				parent::AsignarValor('Informacion', 'El controlador dentro del Modulo no existe');
				parent::AsignarValor('Aplicacion', $ModReWrite[0]);
				parent::AsignarValor('Modulo', $ModReWrite[1]);
				parent::AsignarValor('Controlador', $Controlador);
				parent::SeleccionEjecutar($this->MatrizAccesos, $this->MatrizErrores[404], $ModReWrite[0]);
			}
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarEstructura($Directorio = false)
		 * 
		 * Valida la estructura del modulo de la aplicacion
		 * @access private
		 */
		private function ModuloValidarEstructura($Directorio = false) {
			foreach (self::$FoldersAppModulo AS $Folder) {
				if(is_dir(implode(DIRECTORY_SEPARATOR, array($Directorio, $Folder))) == false) {
					$Error[] = $Folder;
				}
			}
			return (isset($Error) == true) ? false : true;
		}
		
		/**
		 * Metodo Privado
		 * ModuloValidarSeleccion($Modulo = false, $ConfigModulos = false)
		 * 
		 * Valida si esta activo el modulo correspondiente
		 * @access private
		 */
		private function ModuloValidarSeleccion($Modulo = false, $ConfigModulos = false) {
			if($ConfigModulos['Configuracion']['Activo'] == true) {
				if(array_key_exists($Modulo, $ConfigModulos['Modulos']) == true) {
					return ($ConfigModulos['Modulos'][$Modulo] == true) ? true : false;
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
		 * ServidorCargarConfiguracion($Archivo = false, $Extension = self::EXT_JSON)
		 * 
		 * carga el archivo de configuracion correspondiente
		 * @access private
		 */
		private function ServidorCargarConfiguracion($Archivo = false, $Extension = self::EXT_JSON) {
			$File = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, self::$CarpetaApp, $Archivo.$Extension));
			if(file_exists($File) == true) {
				return json_decode(file_get_contents($File), true);
			}
			else {
				
				throw new NeuralException('El Archivo de Configuración: '.$Archivo.', No Existe.');
			}
		}
		
		/**
		 * Metodo Privado
		 * ServidorModReWrite($Configuracion = self::MODREWRITE_HTACCESS, $Predeterminado = self::PREDETERMINADO)
		 * 
		 * Lee los parametros correspondientes al servidor Apache
		 * @access private
		 */
		private function ServidorModReWrite($Configuracion = self::MODREWRITE_HTACCESS, $Predeterminado = self::PREDETERMINADO) {
			$Url = (isset($_GET['Url'])) ? trim($_GET['Url']) : self::PREDETERMINADO;
			if($Url != null) {
				$Url = strip_tags(rtrim($Url, '/'));
				$Array = explode('/', $Url);
				return (empty($Array[0]) == true) ? array(self::PREDETERMINADO) : $Array;
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
			$ArchivoProveedor = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Carpeta, 'App', 'Configuracion', 'Proveedores'.self::EXT_JSON));
			if(file_exists($ArchivoProveedor) == true) {
				$Proveedores = json_decode(file_get_contents($ArchivoProveedor), true);
				if($Proveedores == true) {
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
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración de Proveedores de la Aplicación No Es Correcto');
				}
			}
			else {
				throw new NeuralException('el Archivo de Configuración de Proveedores de la Aplicación No Existe');
			}
			unset($Carpeta, $Directorio, $Proveedores, $Archivo, $ArchivoProveedor);
		}
		
		/**
		 * Metodo Privado
		 * ModoErroresZyos($ModReWrite = false)
		 * 
		 * @access private
		 * Genera el proceso de activar los errores nativos de PHP y desactivarlos
		 */
		private function ModoErroresZyos($ModReWrite = false) {
			$App = (isset($ModReWrite[0]) == true) ? $ModReWrite[0] : self::PREDETERMINADO;
			if(array_key_exists($App, $this->MatrizAccesos) == true) {
				if($this->MatrizAccesos[$App]['Entorno_Desarrollo']['Localhost']['Activo'] == true) {
					if(array_key_exists($_SERVER['REMOTE_ADDR'], array_flip($this->MatrizAccesos[$App]['Entorno_Desarrollo']['Localhost']['ip'])) == true) {
						error_reporting(E_ALL ^ E_STRICT);
						if($this->MatrizAccesos[$App]['Entorno_Desarrollo']['Localhost']['Plantilla'] == true) {
							if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, 'Librerias', 'Error', 'php_error.php'))) == true) {
								require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, 'Librerias', 'Error', 'php_error.php'));
								\php_error\reportErrors();
							}
						}
					}
				}
				elseif($this->MatrizAccesos[$App]['Entorno_Desarrollo']['Externo']['Activo'] == true) {
					error_reporting(E_ALL ^ E_STRICT);
					if($this->MatrizAccesos[$App]['Entorno_Desarrollo']['Localhost']['Plantilla'] == true) {
						if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, 'Librerias', 'Error', 'php_error.php'))) == true) {
							require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, 'Librerias', 'Error', 'php_error.php'));
							\php_error\reportErrors();
						}
					}
				}
				else {
					error_reporting(0);
				}
			}
			else {
				error_reporting(0);
			}
		}
		
		/**
		 * Metodo Magico
		 * __Destruct()
		 * 
		 * Proceso de liberacion de memoria, se genera el proceso del garbage collector para una eliminacion de datos mas eficiente
		 * @access private
		 */
		function __Destruct() {
			gc_enable();
			$this->MatrizAccesos;
			$this->MatrizErrores;
			gc_collect_cycles();
			gc_disable();
		}
	}