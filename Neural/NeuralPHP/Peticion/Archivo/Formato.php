<?php
	
	namespace NeuralPHP\Peticion\Archivo;
	
	class Formato {
		
		/**
		 * Formato::parametros
		 * 
		 * Parametros de los campos que retorna php
		 * al recibir la carga de un archivo 
		 * 
		 * @return array
		 */
		private $parametros = array('error', 'name', 'size', 'tmp_name', 'type');
		
		/**
		 * Formato::raw
		 * 
		 * Contenedor del formato generado por el
		 * proceso interno de la libreria 
		 * 
		 * @return array
		 */
		private $raw;
		
		/**
		 * Formato::traduccion
		 * 
		 * Listado de nombres de las llaves de los
		 * archivos para el proceso de traduccion y
		 * de mejoramiento de la experiencia de
		 * manejo de archivos 
		 * 
		 * @return array
		 */
		private $traduccion = array('error' => 'errorCodigo', 'name' => 'nombre', 'size' => 'tamano', 'tmp_name' => 'temporal', 'type' => 'tipo');
		
		/**
		 * Formato::procesar()
		 * 
		 * Inicializa el proceso de el formato que
		 * deberia tener los campos input file para
		 * carga de archivos retornando el formato
		 * para el proceso general del archivo 
		 * 
		 * @param array $array 
		 * @return array
		 */
		public function procesar(array $array = array()) {
			
			foreach ($array AS $campo => $valor):
				$this->seleccion($campo, $valor);
			endforeach;
			
			return $this->raw;
		}
		
		/**
		 * Formato::seleccion()
		 * 
		  * Genera el proceso inicial de validacion
		 * para determinar la populacion del array
		 * correspondiente con la informacion del
		 * archivo recibida directamente por la
		 * peticion 
		 * 
		 * @param mixed $campo 
		 * @param mixed $parametros 
		 * @return void
		 */
		private function seleccion($campo = null, $parametros = null) {
			foreach ($parametros AS $tipo => $valor):
				
				if(is_array($valor) == true):
					$this->generador($this->traduccion[$tipo], array($campo), $valor);
				else:
					$this->raw[$campo][$this->traduccion[$tipo]] = $valor;
				endif;
				
			endforeach;
		}
		
		/**
		 * Formato::generador()
		 * 
		 * Genera la validacion de los niveles
		 * subsecuentes de los parametros
		 * necesarios e informativos del archivo
		 * cargado, en este punto se va evaluar la
		 * cantidad de niveles y se retoma el
		 * proceso para generar el array
		 * correspondiente 
		 * 
		 * @param string $tipo 
		 * @param array $campo 
		 * @param mixed $valor 
		 * @return void
		 */
		private function generador($tipo = null, $campo = array(), $valor = null) {
			foreach ($valor AS $nombre => $param):
				
				if(is_array($param) == true):
					self::generador($tipo, array_merge($campo, array($nombre)), $param);
				else:
					$this->generadorEval(array($tipo), array_merge($campo, array($nombre)), $param);
				endif;
				
			endforeach;
		}
		
		/**
		 * Formato::generadorEval()
		 * 
		 * genera la construccion individual del esquema estructurado de la matriz y genera la organizacion dentro
		 * de un contenedor para retornar la informacion correspondiente
		 * @param array $tipo
		 * @param array $campo
		 * @param string $valor
		 * @return void
		 */
		private function generadorEval($tipo = null, $campo = array(), $valor = null) {
			$parametros = implode('\'][\'', array_merge($campo, $tipo));
			eval('$this->raw[\''.$parametros.'\'] = \''.$valor.'\';');
		}
	}