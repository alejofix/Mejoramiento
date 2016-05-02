<?php
	
	namespace NeuralPHP\Peticion\Servidor;
	use \NeuralPHP\Peticion\Contenedor\ContenedorArray;
	
	class Servidor extends ContenedorArray {
		
		/**
		 * Servidor::ipServidor()
		 * 
		 * Retorna la direccion ip del servidor
		 * donde se ejecuta la peticion 
		 * 
		 * @return string
		 */
		public function ipServidor() {
			return $this->obtener('SERVER_ADDR');
		}
		
		/**
		 * Servidor::ipCliente()
		 * 
		 * retorna la ip del cliente
		 * 
		 * @return mixed
		 */
		public function ipCliente() {
			return $this->obtener('REMOTE_ADDR');
		}
		
		/**
		 * Servidor::puertoServidor()
		 *
		 * puerto de comunicacion del servidor
		 *  
		 * @return integer
		 */
		public function puertoServidor() {
			return $this->obtener('SERVER_PORT');
		}
		
		/**
		 * Servidor::puertoCliente()
		 * 
		 * Puerto de comunicacion que utiliza el
		 * usuario para visualizar el servidor 
		 * 
		 * @return integer
		 */
		public function puertoCliente() {
			return $this->obtener('REMOTE_PORT');
		}
		
		/**
		 * Servidor::navegadorCliente()
		 *
		 * Retorna la informacion del navegador del
		 * cliente a traves de la funcion
		 * get_browser la cual debe estar
		 * configurada en el servidor para la
		 * validacion correspondiente 
		 * 
		 * @return array
		 */
		public function navegadorCliente() {
			return get_browser($this->obtener('HTTP_USER_AGENT'), true);
		}
	}