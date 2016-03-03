<?php
	
	class Informativos extends Controlador {
		
		/**
		 * Index::__Construct()
		 * 
		 * genera la validacion del permiso asignado para su visualizacion
		 * @return void
		 */
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Index::Index()
		 * 
		 * genera la plantilla inicial
		 * @return void
		 */
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Comunicación');
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Informativos', 'Informativos.html')));
		}
		
		/**
		 * Informativos::Vista()
		 * 
		 * Genera la tabla infor
		 * @return raw
		 */
		public function Vista() {
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Informativos', 'Vista.html')));
		}
		
		/**
		 * Informativos::AjaxVista()
		 * 
		 * Genera la validacion de existencia peticion ajax
		 * @return void
		 */
		public function AjaxVista() {
			if(AppValidar::PeticionAjax() == true):
				$plantilla = new NeuralPlantillasTwig(APP);
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Informativos', 'AjaxVista.html')));
			else:
				echo 'No es posible cargar el Ajax';
			endif;
		}
		

	}