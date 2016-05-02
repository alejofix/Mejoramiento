<?php
	
	namespace NeuralPHP\Peticion\Archivo;
	
	class Archivo {
		
		/**
		 * Archivo::parametros
		 * 
		 * Parametros de los campos que retorna php
		 * al recibir la carga de un archivo
		 */
		private $parametros = array('errorCodigo', 'nombre', 'tamano', 'temporal', 'tipo');
		
		/**
		 * Archivo::raw
		 *
		 * Contenedor de los datos de la peticion 
		 * 
		 * @return array
		 */
		private $raw;
		
		/**
		 * Archivo::__construct()
		 * 
		 * Asignacion del array de la peticion al
		 * contenedor interno
		 * 
		 * @param array $peticion array de la peticion
		 * @param boolean $formato genera el formato respectivo
		 * @return void
		 */
		function __construct(array $peticion = array(), $formato = false) {
			if($formato == true):
				$formato = new Formato();
				$this->raw = $formato->procesar($peticion);
			else:
				$this->raw = $peticion;
			endif;
		}
		
		/**
		 * Archivo::buscar()
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
		 * Archivo::contar()
		 * 
		 * Retorna la cantidad de elementos en el
		 * nivel indicado 
		 * 
		 * @return integer
		 */
		public function contar() {
			return count($this->raw);
		}
		
		/**
		 * Archivo::elementos()
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
		 * Archivo::eliminar()
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
		 * Archivo::existencia()
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
		 * Archivo::obtener()
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
						$comparacion = array_keys($this->raw[$parametro]);
						sort($comparacion);
						return ($comparacion == $this->parametros) ? new Gestion($this->raw[$parametro]) : new self($this->raw[$parametro]);
					endif;
					return null;
					
				endif;
				return null;
				
			endif;
			return $this->raw;
		}
	}