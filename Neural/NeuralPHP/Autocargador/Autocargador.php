<?php
	
	namespace NeuralPHP\Autocargador;
	
	class Autocargador {
		
		/**
		 * Autocargador::namespace
		 * 
		 * Contenedor de los diferentes namespace y
		 * clases para su carga 
		 * 
		 * @return array
		 */
		private $namespace = array();
		
		/**
		 * Autocargador::contenedor
		 * 
		 * Contenedor temporal donde se ubica el
		 * archivo de el namespace o la clase
		 * indicada 
		 * 
		 * @return string
		 */
		private $contenedor = null;
		
		/**
		 * Autocargador::agregar()
		 * 
		 * Agrega un namespace o una clase para el
		 * proceso de autocarga de clases se
		 * registran los diferentes procesos a
		 * traves del metodo para su autocarga 
		 * 
		 * @param string $prefijo 
		 * @param string $directorio 
		 * @return void
		 */
		public function agregar($prefijo = null, $directorio = null) {
			$this->namespace[ltrim($prefijo, '\\')] = rtrim(rtrim($directorio, '\\'), '/');
		}
		
		/**
		 * Autocargador::registrar()
		 * 
		 * Genera el proceso general de registro de
		 * las clases y archivos 
		 * 
		 * @return void
		 */
		public function registrar() {
			spl_autoload_register(array($this, 'cargar'));
		}
		
		/**
		 * Autocargador::retirarRegistro()
		 * 
		 * Genera el proceso de retirar el registro
		 * del autocargador 
		 * 
		 * @return void
		 */
		public function retirarRegistro() {
			spl_autoload_unregister(array($this, 'cargar'));
		}
		
		/**
		 * Autocargador::cargar()
		 * 
		 * Metodo encargado de generar el proceso
		 * de carga del namespace o funcion
		 * correspondiente al proceso de autocarga
		 * de clases 
		 * 
		 * @param string $clase 
		 * @return void
		 */
		private function cargar($clase) {
			if($this->buscarPrefijo($clase) != null):
				$archivo = $this->contenedor.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $clase).'.php';
				if(file_exists($archivo) == true):
					require $archivo;
				endif;
			endif;
		}
		
		/**
		 * Autocargador::buscarPrefijo()
		 * 
		 * Genera el proceso de busqueda del
		 * prefijo de la clase o funcion cargada
		 * para el proceso general de require del
		 * archivo correspondiente 
		 * 
		 * @param string $prefijo 
		 * @return boolean
		 */
		private function buscarPrefijo($prefijo) {
			foreach ($this->namespace AS $nombre => $directorio):
				if(strpos($prefijo, $nombre) !== false):
					$this->contenedor = $directorio;
					return true;
				endif;
			endforeach;
			return;
		}
	}