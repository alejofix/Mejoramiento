<?php
	
	namespace NeuralPHP\Peticion\Archivo;
	use \NeuralPHP\Peticion\Contenedor\ContenedorArray;
	
	class Gestion {
		
		/**
		 * Gestion::raw
		 * 
		 * Contenedor de los datos del archivo
		 * cargado por $_FILES 
		 * 
		 * @return array
		 */
		private $raw;
		
		/**
		 * Gestion::__construct()
		 * 
		 * Genera las variables necesarias para el
		 * proceso de gestion de archivos 
		 * 
		 * @param array $parametros 
		 * @return void
		 */
		function __construct(array $parametros = array()) {
			
			$this->raw = array(
				'nombre' => $parametros['nombre'],
				'tipo' => $parametros['tipo'],
				'temporal' => $parametros['temporal'],
				'errorCodigo' => $parametros['errorCodigo'],
				'errorMensaje' => $this->mensajeError($parametros['errorCodigo']),
				'tamano' => $this->tamanoCalculo($parametros['tamano'])
			);
			
		}
		
		/**
		 * Gestion::copiar()
		 * 
		 * Genera el proceso de mover el archivo a
		 * la ubicacion indicada en caso de no
		 * ingresar el nombre con su respectiva
		 * extension se tomara el nombre original
		 * del archivo para mover la carga al
		 * directorio indicado
		 * 
		 * @param string $directorio 
		 * @param string $nombre 
		 * @return boolean
		 */
		public function copiar($directorio = null, $nombre = null) {
			if(is_string($directorio) == true):
				return $this->copiarExistenciaDir(rtrim(rtrim($directorio, '\\'), '/'), $nombre);
			else:
				return null;
			endif;
		}
		
		/**
		 * Gestion::copiarExistenciaDir()
		 * 
		 * Genera la validacion de la existencia
		 * del directorio 
		 * 
		 * @param string $directorio 
		 * @param string $nombre 
		 * @return boolean
		 */
		private function copiarExistenciaDir($directorio = null, $nombre = null) {
			if(is_dir($directorio) == true):
				$nombre = (is_string($nombre) == true) ? $nombre : $this->raw['nombre'];
				return move_uploaded_file($this->raw['temporal'], implode(DIRECTORY_SEPARATOR, array($directorio, $nombre)));
			else:
				return null;
			endif;
		}
		
		/**
		 * Gestion::tamanoCalculo()
		 * 
		 * Retorna el tamano del archivo y se
		 * retorna la variacion correspondiente
		 * para el tipo de tamano 
		 * 
		 * @param integer $tamano 
		 * @return array
		 */
		private function tamanoCalculo($tamano = 0) {
			return array(
				'bytes' => $tamano,
				'kilobytes' => $tamano / 1024,
				'megabytes' => ($tamano / 1024) / 1024,
				'gigabytes' => (($tamano / 1024) / 1024) / 1024
			);
		}
		
		/**
		 * Gestion::mensajeError()
		 * 
		 * Retorna el mensaje de error dependiendo
		 * del codigo 
		 * 
		 * @param integer $error 
		 * @return string
		 */
		private function mensajeError($error = null) {
			switch($error):
				case UPLOAD_ERR_OK : 
						return 'Archivo cargado con exito';
					break;
				case UPLOAD_ERR_INI_SIZE : 
						return 'El archivo supera el tamaño permitido por el servidor';
					break;
				case UPLOAD_ERR_FORM_SIZE : 
						return 'El archivo supera el tamaño permitido por la directiva del formulario HTML';
					break;
				case UPLOAD_ERR_PARTIAL : 
						return 'El archivo fue parcialmente cargado';
					break;
				case UPLOAD_ERR_NO_FILE :
						return 'El archivo no fue cargado';
					break;
				case UPLOAD_ERR_NO_TMP_DIR : 
						return 'El archivo temporal no se encuentra en el servidor, directorio temporal no existe';
					break;
				case UPLOAD_ERR_CANT_WRITE :
						return 'Error al escribir el archivo en el disco';
					break;
				case UPLOAD_ERR_EXTENSION :
						return 'El carga del archivo fue detenida por la extensión del archivo';
					break;
				default : 
						return 'Error desconocido';
					break;
			endswitch;
		}
		
		/**
		 * Gestion::obtener()
		 * 
		 * Genera el proceso de retornar los
		 * valores de la llave de la matriz de
		 * datos se retornara con el objeto de
		 * administracion de datos en caso de error
		 * se retornara un valor nulo 
		 * 
		 * @param string $parametro 
		 * @return string
		 */
		public function obtener($parametro = null) {
			if(func_num_args() >= 1):
				
				if($parametro != null):
					if(array_key_exists($parametro, $this->raw) == true):
						return (is_array($this->raw[$parametro]) == true) ? new ContenedorArray($this->raw[$parametro]) : $this->raw[$parametro];
					endif;
					return null;
				endif;
				return null;
				
			endif;
			return $this->raw;
		}
	}