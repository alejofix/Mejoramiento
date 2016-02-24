<?php
	
	class Historial extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Historial::Index()
		 * 
		 * Genera la consulta de los datos
		 * @return void
		 */
		public function Index() {
			//if(AppValidar::PeticionAjax() == true):
				header('Content-Type: application/json');
				$sesion = AppSesion::obtenerDatos();
				echo json_encode($this->Modelo->consulta($this->ordenarIDChat($sesion['Chat']), count($sesion['Chat'])));
			//else:
				//exit('No es posible procesar su solicitud');
			//endif;
		}
		
		/**
		 * Historial::ordenarIDChat()
		 * 
		 * Organiza los id del chat
		 * @param array $array
		 * @return array
		 */
		private function ordenarIDChat($array = false) {
			if(is_array($array) == true):
				foreach ($array AS $valor):
					$lista[] = $valor['ID'];
				endforeach;
				return (isset($lista) == true) ? $lista : 'No es posible procesar su solicitud No hay Chat asignados';
			else:
				exit('No es posible procesar su solicitud No hay Chat asignados');
			endif;
		}
	}