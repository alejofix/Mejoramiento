<?php
	
	namespace NeuralPHP\Peticion;
	use \NeuralPHP\Peticion\Archivo\Archivo;
	use \NeuralPHP\Peticion\Contenedor\ContenedorArray;
	use \NeuralPHP\Peticion\Servidor\Servidor;
	
	class Init {
		
		/**
		 * Peticion::archivo
		 * 
		 * Variable donde se contiene el proceso
		 * correspondiente para el procesamiento de
		 * la peticion $_FILES
		 * 
		 * @return object
		 */
		public $archivo;
		
		/**
		 * Peticion::cookie
		 * 
		 * Variable donde se contiene el proceso
		 * correspondiente para el procesamiento de
		 * la peticion $_COOKIE
		 * 
		 * @return object
		 */
		public $cookie;
		
		/**
		 * Peticion::get
		 * 
		 * Variable donde se contiene el proceso
		 * correspondiente para el procesamiento de
		 * la peticion $_GET
		 * 
		 * @return object
		 */
		public $get;
		
		/**
		 * Peticion::post
		 * 
		 * Variable donde se contiene el proceso
		 * correspondiente para el procesamiento de
		 * la peticion $_POST
		 * 
		 * @return object
		 */
		public $post;
		
		/**
		 * Peticion::servidor
		 * 
		 * Variable donde se contiene el proceso
		 * correspondiente para el procesamiento de
		 * la peticion $_SERVER
		 * 
		 * @return object
		 */
		public $servidor;
		
		/**
		 * Peticion::temporal
		 * 
		 * Contenedor de datos vacio que puede
		 * utilizarse dentro del proceso actual, al
		 * terminar la carga del script la
		 * informacion es eliminada 
		 * 
		 * @return object
		 */
		public $temporal;
		
		/**
		 * Peticion::__construct()
		 * 
		 * Genera las variables necesarias para el
		 * proceso correspondiente de la peticion
		 * de datos 
		 * 
		 * @return void
		 */
		function __construct() {
			$this->inicializar($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, array());
		}
		
		/**
		 * Peticion::inicializar()
		 * 
		 * Genera el proceso de asignar los datos
		 * de las diferentes peticiones a los
		 * objetos de contenedores los cuales se
		 * puede obtener los datos respectivos de
		 * la peticion 
		 * 
		 * @param array $get 
		 * @param array $post 
		 * @param array $archivo 
		 * @param array $cookie 
		 * @param array $servidor 
		 * @param array $temporal 
		 * @return void
		 */
		private function inicializar(array $get = array(), array $post = array(), array $archivo = array(), array $cookie = array(), array $servidor = array(), array $temporal = array()) {
			$this->get = new ContenedorArray($get);
			$this->post = new ContenedorArray($post);
			$this->archivo = new Archivo($archivo, true);
			$this->cookie = new ContenedorArray($cookie);
			$this->servidor = new Servidor($servidor);
			$this->temporal = new ContenedorArray($temporal);
		}
		
		/**
		 * Peticion::ajax()
		 * 
		 * Genera la validacion si se esta
		 * generando una peticion ajax retorna
		 * valor booleano false en caso de no ser
		 * enviada la peticion ajax en caso de
		 * exito se retorna un valor booleano true 
		 * 
		 * @return boolean
		 */
		public function ajax() {
			return (empty($_SERVER['HTTP_X_REQUESTED_WITH']) == false AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? (boolean) true : (boolean) false;
		}
	}