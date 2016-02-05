<?php
	
	class Js extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Js::Index()
		 * 
		 * Genera el aviso de no permiso
		 * @return void
		 */
		public function Index() {
			echo <<<EOT
	/**
	 *	Lo sentimos
	 *
	 *	No tiene permisos de observar el archivo JS
	 */		
EOT;
		}
		
		/**
		 * Js::notificacion()
		 * 
		 * Muestra la plantilla JS con las variables que se requieren
		 * @param bool $archivo
		 * @return void
		 */
		public function notificacion($archivo = false) {
			header('Content-Type: application/javascript');
			if(is_bool($archivo) == false):
				$plantilla = new NeuralPlantillasTwig(APP);
				$plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Js', 'Notificacion.js')));
			else:
				$this->Index();
			endif;
		}
		
		/**
		 * Js::gestionChat()
		 * 
		 * Genera la vista del archivo de js para
		 * la gestion del chat cuando se ejecuta
		 * @param bool $archivo
		 * @return void
		 */
		public function gestionChat($archivo = false) {
			header('Content-Type: application/javascript');
			if(is_bool($archivo) == false):
				$plantilla = new NeuralPlantillasTwig(APP);
				$plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
				$plantilla->Parametro('listaSalas', $this->idSalas());
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Js', 'GuardarChat.js')));
			else:
				$this->Index();
			endif;
		}
		
		/**
		 * Js::idSalas()
		 * 
		 * Genera la organizacion de las salas de la sesion
		 * @return string
		 */
		private function idSalas() {
			$sesion = AppSesion::obtenerDatos();
			if(is_array($sesion['Chat']) == true AND count($sesion['Chat']) >= 1):
				foreach ($sesion['Chat'] AS $valor):
					$lista[] = $valor['ID'];
				endforeach;
				return json_encode($lista);
			else:
				return '{}';
			endif;
		}
	}