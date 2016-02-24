<?php
	
	class Mensaje extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('ESCRITURA');
		}
		
		/**
		 * Mensaje::Index()
		 * 
		 * Genera el proceso de guardar el mensaje correspondiente
		 * @return void
		 */
		public function Index() {
			if(AppValidar::PeticionAjax() == true):
				$this->validarPost();
			else:
				exit('No es posible procesar su solicitud');
			endif;
		}
		
		/**
		 * Mensaje::validarPost()
		 * 
		 * Genera la validacion del 
		 * @return void
		 */
		private function validarPost() {
			if(isset($_POST) == true):
				$this->validarVacio();
			else:
				exit('No hay datos para procesar');
			endif;
		}
		
		/**
		 * Mensaje::validarVacio()
		 * 
		 * Genera la validacion que los datos no esten vacios
		 * @return void
		 */
		private function validarVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->formatoPost();
			else:
				exit('No es posible procesar datos vacíos');
			endif;
		}
		
		/**
		 * Mensaje::formatoPost()
		 * 
		 * Genera el formato de los datos post
		 * @return void
		 */
		private function formatoPost() {
			$DatosPost = AppFormato::Espacio()->Mayusculas(array('USUARIO'))->MatrizDatos($_POST);
			$sesion = AppSesion::obtenerDatos();
			$this->Modelo->guardar($DatosPost['SALA'], $DatosPost['MENSAJE'], $sesion['Informacion']['USUARIO_RR']);
			
			header('Content-Type: application/json');
			echo json_encode($this->Modelo->mensajes($DatosPost['SALA']));
		}
		
		/**
		 * Mensaje::salas()
		 * 
		 * Genera el proceso correspondiente de
		 * obtener el listados de los chats
		 * @return void
		 */
		public function salas() {
			if(AppValidar::PeticionAjax() == true):
				$this->salasValidarPost();
			else:
				exit('No es posible procesar su solicitud');
			endif;
		}
		
		/**
		 * Mensaje::salasValidarPost()
		 * 
		 * Genera la validacion de la existencia de 
		 * los datos post
		 * @return void
		 */
		private function salasValidarPost() {
			if(isset($_POST) == true):
				$this->salasValidarVacio();
			else:
				exit('No hay datos para procesar');
			endif;
		}
		
		/**
		 * Mensaje::salasValidarVacio()
		 * 
		 * Genera la validacion de los datos post estan
		 * vacios
		 * @return void
		 */
		private function salasValidarVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->salasProcesar();
			else:
				exit('No es posible procesar datos vacios');
			endif;
		}
		
		/**
		 * Mensaje::salasProcesar()
		 * 
		 * Imprime la peticion correspondiente
		 * @return void
		 */
		private function salasProcesar() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST['SALAS']);
			echo json_encode($this->Modelo->salasMensajes($DatosPost));
		}
	}