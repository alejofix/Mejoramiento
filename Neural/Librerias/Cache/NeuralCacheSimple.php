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
	
	class NeuralCacheSimple {
		
		/**
		 * Nivel de compresion de datos
		 * @access private
		 */
		private static $NivelCompresion = 9;
		
		/**
		 * Tiempo predeterminado de expiracion
		 * @access private
		 */
		private $TiempoExpiracion = 60;
		
		/**
		 * Constante Predeterminada
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Constructor
		 * 
		 * Genera el tiempo de expiracion indicada subcarpeta y ubicacion de cache personalizada
		 * @param $Expiracion: tiempo de expiracion en segundos
		 * @param $SubCarpeta: nombre de la carpeta donde se guardaran los archivos de cache
		 * @param $UbucacionCache: ubicacion fisica de la nueva cache
		 */
		function __Construct($Expiracion = false, $SubCarpeta = false, $UbicacionCache = false) {
			$this->CacheTemporales = self::SeleccionUbicacionCache($UbicacionCache);
			if($Expiracion == true) {
				$this->TiempoExpiracion = $Expiracion;
			}
			if($SubCarpeta == true) {
				$this->CacheSubCarpeta = self::Hash(trim($SubCarpeta));
			}
		}
		
		/**
		 * Metodo Privado
		 * Compresion($Cadena = false)
		 * 
		 * Genera la compresion de los datos
		 * @access private
		 */
		private function Compresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzcompress($Cadena, self::$NivelCompresion) : $Cadena;
		}
		
		/**
		 * Metodo Privado
		 * ContenedorInfo($Tiempo = false, $SubDirectorio = false, $LlaveUnica = false)
		 * 
		 * Genera la informacion base del contenedor de informacion	
		 * @access private
		 */
		private function ContenedorInfo($Tiempo = false, $SubDirectorio = false, $LlaveUnica = false) {
			$Matriz = array('Comparacion' => strtotime($Tiempo), 'Fecha_Creacion' => $Tiempo, 'Contenedor' => $SubDirectorio, 'Archivo_Cache' => $LlaveUnica, 'Caducidad' => $this->TiempoExpiracion);
			return json_encode($Matriz);
		}
		
		/**
		 * Metodo Privado
		 * CrearSubDirectorio()
		 * 
		 * Crea la carpeta contenedora de la cache
		 * @access private
		 */
		private function CrearSubDirectorio() {
			if(isset($this->CacheSubCarpeta) == true) {
				$Directorio = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $this->CacheSubCarpeta));
				if(is_dir($Directorio) == false) {
					mkdir($Directorio);
				}
				return $this->CacheSubCarpeta;
			}
			else {
				$Directorio = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, self::Hash(self::PREDETERMINADO)));
				if(is_dir($Directorio) == false) {
					mkdir($Directorio);
				}
				return self::Hash(self::PREDETERMINADO);
			}
		}
		
		/**
		 * Metodo Privado
		 * DesCompresion($Cadena = false)
		 * 
		 * Genera la descompresion de los datos
		 * @access private
		 */
		private function DesCompresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzuncompress($Cadena) : $Cadena;
		}
		
		/**
		 * Metodo Publico
		 * GuardarCache($NombreUnico = false, $ValorAlmacenar = false)
		 * 
		 * Genera el proceso de guardar los datos en la cache
		 * esto aplica para textos y matrices, no acepta objetos
		 * @param $NombreUnico: identificacion de la cache
		 * @param $ValorAlmacenar: valor respectivo a guardar
		 */
		public function GuardarCache($NombreUnico = false, $ValorAlmacenar = false) {
			$SubDirectorio = self::CrearSubDirectorio();
			$LlaveUnica = self::Hash($NombreUnico);
			$ContenedorCache = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.cache'));
			$ContenedorInfo = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.info'));
			file_put_contents($ContenedorCache, self::Compresion(json_encode($ValorAlmacenar)), LOCK_EX);
			file_put_contents($ContenedorInfo, self::ContenedorInfo(date("Y-m-d H:i:s"), $SubDirectorio, $LlaveUnica));
		}
		
		/**
		 * Metodo Privado
		 * Hash($Cadena = false)
		 * 
		 * Genera el hash de la cadena correspondiente
		 * @access private
		 */
		private function Hash($Cadena = false) {
			return hash('sha256', $Cadena);
		}
		
		/**
		 * Metodo Privado
		 * LeerArchivoCache($RutaArchivo = false)
		 * 
		 * Leer el archivo de cache correspondiente
		 * @access private
		 */
		private function LeerArchivoCache($RutaArchivo = false) {
			return json_decode(self::DesCompresion(file_get_contents($RutaArchivo)), true);
		}
		
		/**
		 * Metodo Publico
		 * ObtenerCache($NombreUnico = false)
		 * 
		 * Obtiene los datos que se encuentran contenidos en la cache correspondiente
		 * @param $NombreUnico: Identificacion de la cache
		 */
		public function ObtenerCache($NombreUnico = false) {
			if($NombreUnico == true) {
				$SubDirectorio = self::CrearSubDirectorio();
				$LlaveUnica = self::Hash($NombreUnico);
				$ContenedorCache = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.cache'));
				$ContenedorInfo = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.info'));
				if(file_exists($ContenedorCache) == true AND file_exists($ContenedorInfo) == true) {
					return self::LeerArchivoCache($ContenedorCache);
				}
				return null;
			}
		}
		
		/**
		 * Metodo Privado
		 * SeleccionUbicacionCache($UbicacionCache = false)
		 * 
		 * Genera la ubicacion de la cache correspondiente
		 * @access private
		 */
		private function SeleccionUbicacionCache($UbicacionCache = false) {
			if($UbicacionCache == true) {
				if(is_dir($UbicacionCache) == true) {
					return $UbicacionCache;
				}
				else {
					throw new NeuralException('La Ubicación Indicada a Cache Simple No Existe ó No es Correcta');
				}
			}
			else {
				return self::UbicacionPorDefectoApp();
			}
		}
		
		/**
		 * Metodo Privado
		 * UbicacionPorDefectoApp($App = self::PREDETERMINADO)
		 * 
		 * Genera la ruta de la cache correspondiente
		 * @access private
		 */
		private function UbicacionPorDefectoApp($App = self::PREDETERMINADO) {
			$ModReWrite = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
			$Aplicacion = (isset($ModReWrite[0]) == true) ? $ModReWrite[0] : $App;
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'));
			if(file_exists($Archivo)) {
				$Data = json_decode(file_get_contents($Archivo), true);
				if(array_key_exists($Aplicacion, $Data) == true) {
					$UbicacionCache = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootApp__, $Data[$Aplicacion]['Carpeta'], 'App', 'Temporales'));
					if(is_dir($UbicacionCache) == true) {
						unset($ModReWrite, $App, $Aplicacion, $Archivo, $Data);
						return $UbicacionCache;
					}
					else {
						throw new NeuralException('La Ubicación Indicada a Cache Simple No Existe ó No es Correcta en la Estructura de la Aplicación');
					}
				}
				else {
					throw new NeuralException('La Aplicación No existe en La Archivo de Configuración de Accesos');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de Accesos No Existe');
			}
		}
		
		/**
		 * Metodo Publico
		 * ValidarExistenciaCache($NombreUnico = false)
		 * 
		 * Valida la existencia de la cache correspondiente
		 * @param $NombreUnico: Identificacion de la cache
		 * @return true: existe cache
		 * @return false: no existe la cache
		 */
		public function ValidarExistenciaCache($NombreUnico = false) {
			$SubDirectorio = self::CrearSubDirectorio();
			$LlaveUnica = self::Hash($NombreUnico);
			$ContenedorCache = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.cache'));
			$ContenedorInfo = implode(DIRECTORY_SEPARATOR, array($this->CacheTemporales, $SubDirectorio, $LlaveUnica.'.info'));
			if(file_exists($ContenedorCache) == true AND file_exists($ContenedorInfo) == true) {
				$Datos = json_decode(file_get_contents($ContenedorInfo), true);
				$TiempoActual = strtotime(date("Y-m-d H:i:s"));
				$TiempoCache = $Datos['Comparacion'] + $this->TiempoExpiracion;
				return ($TiempoCache >= $TiempoActual) ? true : false; 
			}
			else {
				return false;
			}
			
		}
	}