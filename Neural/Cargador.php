<?php
	
	require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'NeuralPHP', 'Autocargador', 'Autocargador.php'));
		
	class SysNeuralCargador {
		
		/**
		 * Constante de extension de json
		 * @access private
		 */
		const EXT_JSON = '.json';
		
		/**
		 * Metodo Publico
		 * Cargador($Inicializar = false)
		 * 
		 * Genera el proceso de carga inicial
		 * @access private
		 */
		public static function Cargador($Inicializar = false) {
			if($Inicializar == true) {
				self::Cargarlibrerias();
			}
		}
		
		/**
		 * Metodo Privado
		 * Cargarlibrerias()
		 * 
		 * Genera el proceso de carga de librerias
		 * @access private
		 */
		private static function Cargarlibrerias() {
			self::IncluirArchivo(array('Librerias', 'Neural', 'Miscelaneos', 'Miscelaneos.php'));
			self::IncluirArchivo(array('Configuraciones', 'RutasBase.php'));
			self::IncluirArchivo(array('Configuraciones', 'Url.php'));
			self::IncluirProveedores();
			self::IncluirArchivo(array('Librerias', 'Error', 'ManejoErrores.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'Bootstrap', 'ErrorUsuario.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'Bootstrap', 'ErrorDesarrollo.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'Controlador.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'Modelo.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'NeuralRutasApp.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'Bootstrap.php'));
			self::IncluirArchivo(array('Librerias', 'Neural', 'BootstrapVirtual.php'));
			self::IncluirArchivo(array('Librerias', 'BD', 'NeuralConexionDB.php'));
			self::IncluirArchivo(array('Librerias', 'BD', 'NeuralBDGab.php'));
			self::IncluirArchivo(array('Librerias', 'BD', 'NeuralBDConsultas.php'));
			self::IncluirArchivo(array('Librerias', 'Cache', 'NeuralCacheSimple.php'));
			self::IncluirArchivo(array('Librerias', 'Correo', 'NeuralCorreoSwiftMailer.php'));
			self::IncluirArchivo(array('Librerias', 'Criptografia', 'NeuralCriptografia.php'));
			self::IncluirArchivo(array('Librerias', 'JQuery', 'NeuralJQueryAjaxConstructor.php'));
			self::IncluirArchivo(array('Librerias', 'JQuery', 'NeuralJQueryConstructor.php'));
			self::IncluirArchivo(array('Librerias', 'JQuery', 'NeuralJQueryScript.php'));
			self::IncluirArchivo(array('Librerias', 'JQuery', 'NeuralJQValidacion.php'));
			self::IncluirArchivo(array('Librerias', 'Plantillas', 'NeuralPlantillasTwig.php'));
			self::IncluirArchivo(array('Librerias', 'Sesiones', 'NeuralSesiones.php'));
			self::IncluirAyudasGenerales();
			//self::IncluirArchivo(array('Librerias', '', ''));
		}
		
		/**
		 * Metodo Privado
		 * IncluirArchivo($Array = array())
		 * 
		 * Genera el proceso de incluir los archivos
		 * @access private
		 */
		private static function IncluirArchivo($Array = array()) {
			$File = implode(DIRECTORY_SEPARATOR, array_merge(array(__DIR__), $Array));
			if(file_exists($File) == true) {
				require $File;
			}
			unset($File, $Array);
		}
		
		/**
		 * Metodo Privado
		 * LeerArchivoConfiguracion($Archivo = false, $Extension = self::EXT_JSON)
		 * 
		 * Lee los archivos de configuracion
		 * @access private
		 */
		private static function LeerArchivoConfiguracion($Archivo = false, $Extension = self::EXT_JSON) {
			$File = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', $Archivo.$Extension));
			if(file_exists($File) == true) {
				return json_decode(file_get_contents($File), true);
			}
			else {
				throw new NeuralException('El Archivo: '.$Archivo.', No Existe en La Configuración de Neural.');
			}
		}
		
		/**
		 * Metodo Privado
		 * IncluirProveedores()
		 * 
		 * Incluye los proveedores correspondientes
		 * @access private
		 */
		private static function IncluirProveedores() {
			$Configuracion = self::LeerArchivoConfiguracion('Proveedores', self::EXT_JSON);
			foreach ($Configuracion AS $Nombre => $Parametros) {
				if($Parametros['Habilitado'] == true) {
					$File = implode(DIRECTORY_SEPARATOR, array_merge(array(__SysNeuralFileRootProveedores__), $Parametros['Ruta']));
					if(file_exists($File) == true) {
						require $File;
					}
					else {
						throw new NeuralException('El Archivo del Proveedor: '.$Nombre.', No Existe');
					}
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * IncluirAyudasGenerales()
		 * 
		 * Incluye los archivos de las ayudas globales
		 * @access private
		 */
		private static function IncluirAyudasGenerales() {
			$Directorio = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'Ayudas'));
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
		}
	}
	
	/**
	 * Ejecutar la carga completa
	 * @access private
	 */
	SysNeuralCargador::Cargador(true);
	
	$cargador = new \NeuralPHP\Autocargador\Autocargador();
	$cargador->agregar('NeuralPHP', __DIR__);
	$cargador->registrar();