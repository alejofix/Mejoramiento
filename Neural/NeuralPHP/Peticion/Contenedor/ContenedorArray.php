<?php
	
	namespace NeuralPHP\Peticion\Contenedor;
	
	class ContenedorArray {
		
		/**
		 * ContenedorArray::raw
		 * 
		 * Contenedor de los datos a ser
		 * administrados en el proceso de la clase
		 * actual 
		 * 
		 * @return array
		 */
		private $raw;
		
		/**
		 * ContenedorArray::__construct()
		 * 
		 * Genera el proceso de asignacion de las
		 * variables correspondientes para el
		 * proceso indicado 
		 * 
		 * @param array $array 
		 * @return void
		 */
		function __construct(array $array = array()) {
			$this->raw = $array;
		}
		
		/**
		 * ContenedorArray::buscar()
		 * 
		 * Genera la busqueda de las llaves del
		 * array de datos y retorna el par nombre
		 * valor de cada una de ellas que cumplan
		 * con la condicion del prefijo, en caso de
		 * no encontrarse el prefijo en los nombres
		 * del array de datos se retornara un valor
		 * null en caso de exito retornara un array
		 * y se retornara como el objeto contenedor
		 * para su procesamiento 
		 * 
		 * @param string $prefijo 
		 * @return object
		 */
		public function buscar($prefijo = null) {
			if($prefijo != null):
				
				foreach ($this->raw AS $nombre => $valor):
					if(strpos($nombre, $prefijo) === 0):
						$lista[$nombre] = $valor;
					endif;
				endforeach;
				
			endif;
			
			return (isset($lista) == true) ? new self($lista) : null;
		}
		
		/**
		 * ContenedorArray::contar()
		 * 
		 * Retorna la cantidad de elementos que
		 * contiene los datos de la peticion 
		 * 
		 * @return integer
		 */
		public function contar() {
			return count($this->raw);
		}
		
		/**
		 * ContenedorArray::crear()
		 * 
		 * Agrega un elemento al array de la
		 * peticion en caso de que exista retornara
		 * un valor false en caso de exito se
		 * retornara un valor true 
		 * 
		 * @param string $nombre 
		 * @param mixed $valor 
		 * @return boolean
		 */
		public function crear($nombre = null, $valor = null) {
			if(array_key_exists($nombre, $this->raw) == false):
				$this->raw[$nombre] = $valor;
				return true;
			endif;
			return false;
		}
		
		/**
		 * ContenedorArray::elementos()
		 * 
		 * Retorna el array de los nombres de los
		 * elementos obtenidos dentro de la
		 * peticion 
		 * 
		 * @return array
		 */
		public function elementos() {
			return array_keys($this->raw);
		}
		
		/**
		 * ContenedorArray::eliminar()
		 * 
		 * Elimina el elemento indicado se
		 * retornara true en caso de existo y
		 * retornara en caso de error false los
		 * cuales son que no exista el elemento en
		 * este caso 
		 * 
		 * @param string $parametro 
		 * @return boolean
		 */
		public function eliminar($parametro = null) {
			if($parametro != null):
				
				if(array_key_exists($parametro, $this->raw) == true):
					unset($this->raw[$parametro]);
					return true;
				endif;
				return false;
				
			endif;
			return false;
		}
		
		/**
		 * ContenedorArray::existencia()
		 * 
		 * Valida la existencia del elemento
		 * correspondiente para determinar si esta
		 * dentro de la matriz de datos de la
		 * peticion retornara un valor true en caso
		 * de que exista y un valor false en caso
		 * contrario se presentara un valor nulo si
		 * no se ingresa el parametro a validar o
		 * es una opcion diferentes a un string o
		 * un integer 
		 * 
		 * @param string $parametro 
		 * @return boolean
		 */
		public function existencia($parametro = null) {
			if(func_num_args() >= 1):
				
				if($parametro != null):
					return array_key_exists($parametro, $this->raw);
				endif;
				return null;
				
			endif;
			return null;
		}
		
		/**
		 * ContenedorArray::obtener()
		 * 
		 * Genera el proceso de retornar los
		 * valores de la llave de la matriz de
		 * datos se retornara con el objeto de
		 * administracion de datos en caso de error
		 * se retornara un valor nulo 
		 * 
		 * @param string $parametro 
		 * @return object
		 */
		public function obtener($parametro = null) {
			if(func_num_args() >= 1):
				
				if($parametro != null):
					
					if(array_key_exists($parametro, $this->raw) == true):
						return (is_array($this->raw[$parametro]) == true) ? new self($this->raw[$parametro]) : $this->raw[$parametro];
					endif;
					return null;
					
				endif;
				return null;
				
			endif;
			return $this->raw;
		}
		
		/**
		 * ContenedorArray::reemplazar()
		 * 
		 * Genera el proceso de reemplazar un
		 * elemnto que ya existe dentro de la
		 * matriz de la peticion en caso de error
		 * retornara un valor false no se agregara
		 * el elemento en caso de exito un valor
		 * true 
		 * 
		 * @param string $nombre 
		 * @param mixed $valor 
		 * @return boolean
		 */
		public function reemplazar($nombre = null, $valor = null) {
			if(array_key_exists($nombre, $this->raw) == true):
				$this->raw[$nombre] = $valor;
				return true;
			endif;
			return false;
		}
	}