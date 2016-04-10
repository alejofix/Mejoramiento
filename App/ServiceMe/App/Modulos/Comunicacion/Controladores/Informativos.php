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
		 * Informativos::Listado()
		 * 
		 * Listado de Informativos
		 * @return void
		 */
		public function Listado() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Comunicación');
			echo $Plantilla->MostrarPlantilla('Informativos/Listado.html');
		}
		
				
		public function AjaxVista() {
			if(AppValidar::PeticionAjax() == true):
				$plantilla = new NeuralPlantillasTwig(APP);
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Informativos', 'AjaxVista.html')));
			else:
				echo 'No es posible cargar la información';
			endif;
		}
		

	}