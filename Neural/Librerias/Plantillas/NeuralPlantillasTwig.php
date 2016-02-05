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
	
	use \Neural\WorkSpace;
	
	class NeuralPlantillasTwig {
		
		/**
		 * Codificacion de las Plantillas
		 */
		private static $Codificacion = 'UTF-8';
		
		/**
		 * Modo de Depuracion de las Plantillas Twig
		 */
		private static $Depuracion = false;
		
		/**
		 * Variable de Indice
		 * @access private
		 */
		private static $Index = 'Index';
		
		/**
		 * Constante Predeterminada
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Constructor
		 * __Construct($App = self::PREDETERMINADO, $Cache = false)
		 * 
		 * Indica si es necesario la activacion de cache y se indica la aplicacion donde se encuentra
		 * @param $App: Aplicacion donde se tomaran las rutas correspondientes
		 * @param $Cache: puede manejar dos opciones
		 * @example booleano: valor true para activar cache false para desactivar
		 * @example indicar directorio personalizado /opt/mi_cache/ ó c:\mi_cache\
		 */
		function __Construct($App = self::PREDETERMINADO, $Cache = false, $AppCarpetaVista = false) {
			$this->APP = trim($App);
			$this->AppCarpetaVista = $AppCarpetaVista;
			if(is_bool($Cache) == true) {
				$this->Cache = ($Cache == true) ? true : false;
			}
			elseif(is_dir($Cache) == true) {
				$this->Cache = $Cache;
			}
		}
		
		/**
		 * Metodo Privado
		 * Autoloader()
		 * 
		 * Determina si ya fue incluida la libreria de twig
		 * de lo contrario incluye la libreria correspondiente
		 * @access private
		 */
		private function Autoloader() {
			if(class_exists('Twig_Autoloader') == false) {
				require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'Twig', 'Autoloader.php'));
			}
			Twig_Autoloader::register();
		}
		
		/**
		 * Metodo Publico
		 * Filtro($Nombre = false, $Valor = false)
		 * 
		 * Asigna un filtro a la etiqueta {{Variable|mi_filtro}} de la plantilla
		 * @param $Nombre: Nombre del filtro que se le asignara
		 * @param $Valor: funcion anonima que se generara como filtro
		 */
		public function Filtro($Nombre = false, $Valor = false) {
			if($Nombre == true AND $Valor == true) {
				$this->Filtro[trim($Nombre)] = $Valor;
			}
		}
		
		/**
		 * Metodo Publico
		 * Funcion($Nombre = false, $Valor = false)
		 * 
		 * Genera una funcion para ser utilizada en la plantilla
		 * @param $Nombre: Nombre de la funcion
		 * @param $Valor: funcion anonima que sera pasada a la plantilla
		 */
		public function Funcion($Nombre = false, $Valor = false) {
			if($Nombre == true AND $Valor == true) {
				$this->Function[trim($Nombre)] = $Valor;
			}
		}
		
		/**
		 * Metodo Publico
		 * MostrarPlantilla($PlantillaRuta = false)
		 * 
		 * Muestra la plantilla correspondiente
		 */
		public function MostrarPlantilla() {
			$PlantillaRuta = implode(DIRECTORY_SEPARATOR, func_get_args());
			$RutasApp = self::RutaVistaSistema($this->Cache);
			$Twig_Loader_Filesystem = (isset($RutasApp['Modulo']) == true) ? array($RutasApp['MVC'], $RutasApp['Modulo']) : $RutasApp['MVC'];
			$Twig_Environment_Array = ($this->Cache == true) ? array('charset' => self::$Codificacion, 'debug' => self::$Depuracion, 'cache' => $RutasApp['Cache']) : array('charset' => self::$Codificacion, 'debug' => self::$Depuracion);
			$Parametros = (isset($this->Parametro) == true) ? $this->Parametro : array();
			
			if(self::ValidarExistenciaPlantilla($PlantillaRuta, $RutasApp) == true) {
				self::Autoloader();
				$Cargador = new Twig_Loader_Filesystem($Twig_Loader_Filesystem);
				$Twig = new Twig_Environment($Cargador, $Twig_Environment_Array);
				$Twig->addExtension(new Twig_Extension_StringLoader());
				
				if(defined('APPNEURALPHPHOST') == true) {
					$Twig->addGlobal('NeuralRutaApp', implode('/', array(__NeuralUrlRaiz__)));
					$Twig->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'WebRoot', 'Web')));
				}
				else {
					$Twig->addGlobal('NeuralRutaBase', __NeuralUrlRaiz__);
					$Twig->addGlobal('NeuralRutaApp', implode('/', array(__NeuralUrlRaiz__, $this->APP)));
					$Twig->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, $this->APP, 'WebRoot', 'Web')));
				}
				
				if(isset($this->Filtro) == true) {
					foreach ($this->Filtro AS $NombreFiltro => $FuncionFiltro) {
						$Filtro = new Twig_SimpleFilter($NombreFiltro, $FuncionFiltro);
						$Twig->addFilter($Filtro);
					}
				}
				
				if(isset($this->Function) == true) {
					foreach ($this->Function AS $NombreFuncion => $Funcion) {
						$FuncionAnonima = new Twig_SimpleFunction($NombreFuncion, $Funcion);
						$Twig->addFunction($FuncionAnonima);
					}
				}
				
				return $Twig->render($PlantillaRuta, $Parametros);
			}
			else {
				if(isset($RutasApp['Modulo']) == true) {
					throw new NeuralException('La Plantilla: '.$PlantillaRuta.' No Se Encuentra En las Vistas del Modulo');
				}
				else {
					throw new NeuralException('La Plantilla: '.$PlantillaRuta.' No Se Encuentra En las Vistas de la Aplicación');
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * Parametro($Nombre = false, $Valor = false)
		 * 
		 * Asigna un valor a una variable establecida en la plantilla
		 * @param $Nombre: Nombre de la variable de la plantilla {{Nombre}}
		 * @param $Valor: valor que se asigna a la variable
		 */
		public function Parametro($Nombre = false, $Valor = false) {
			if($Nombre == true AND $Valor == true) {
				$this->Parametro[trim($Nombre)] = $Valor;
			}
		}
		
		/**
		 * Metodo Privado
		 * RutaVistaSistema($Cache = false)
		 * 
		 * Valida el tipo de cache a utilizar
		 * @access private
		 */
		private function RutaVistaSistema($Cache = false) {
			if(is_bool($Cache) == true) {
				$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'));
				if(file_exists($Archivo) == true) {
					$Datos = json_decode(file_get_contents($Archivo), true);
					if($Datos == true) {
						if(array_key_exists($this->APP, $Datos) == true) {
							return self::RutaVistaSistemaProceso($Datos[$this->APP]);
						}
					}
					else {
						throw new NeuralException('El Formato del Archivo de Configuración de Accesos No es Correcto.');
					}
				}
				else {
					throw new NeuralException('El Archivo de Configuración de Accesos No Existe.');
				}
			}
			elseif(is_dir($Cache) == true) {
				return $Cache;
			}
		}
		
		/**
		 * Metodo Privado
		 * RutaVistaSistemaProceso($Array = array())
		 * 
		 * Validacion de tipo de cache correspondiente
		 * @access private
		 */
		private function RutaVistaSistemaProceso($Array = array()) {
			$Aplicacion = Neural\WorkSpace\Miscelaneos::LeerModReWrite();
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Array['Carpeta'], 'App', 'Configuracion', 'Modulos.json'));
			if(file_exists($Archivo) == true) {
				$Matriz = json_decode(file_get_contents($Archivo), true);
				if($Matriz == true) {
					$Comparacion = (isset($Aplicacion[1]) == true) ? $Aplicacion[1] : self::$Index;
					if($Matriz['Configuracion']['Activo'] == true) {
						if(array_key_exists($Comparacion, $Matriz['Modulos']) == true) {
							if($Matriz['Modulos'][$Comparacion] == true) {
								if($this->AppCarpetaVista == true AND is_dir($this->AppCarpetaVista) == true) {
									$Data['Modulo'] = $this->AppCarpetaVista;
								}
								else {
									$Data['Modulo'] = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Array['Carpeta'], 'App', 'Modulos', $Comparacion, 'Vistas'));
								}
							}
						}
					}
					if($this->AppCarpetaVista == true AND is_dir($this->AppCarpetaVista) == true) {
						$Data['MVC'] = $this->AppCarpetaVista;
						$Data['Cache'] = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Array['Carpeta'], 'App', 'Temporales'));
					}
					else {
						$Data['MVC'] = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Array['Carpeta'], 'App', 'MVC', 'Vistas'));
						$Data['Cache'] = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Array['Carpeta'], 'App', 'Temporales'));
					}
					unset($Aplicacion, $Archivo, $Matriz);
					return $Data;
				}
				else {
					throw new NeuralException('El Formato del Archivo de Modulos No es Correcto.');
				}
			}
			else {
				throw new NeuralException('El Archivo de Modulos No Existe en la Aplicación');
			}
		}
		
		/**
		 * Metodo Privado
		 * ValidarExistenciaPlantilla($Plantilla = false, $Rutas = array())
		 * 
		 * Valida la existencia de la plantilla correspondiente
		 * @access private
		 */
		private function ValidarExistenciaPlantilla($Plantilla = false, $Rutas = array()) {
			if(isset($Rutas['Modulo']) == true) {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array($Rutas['Modulo'], $Plantilla))) == true) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array($Rutas['MVC'], $Plantilla))) == true) {
					return true;
				}
				else {
					return false;
				}
			}
		}
	}