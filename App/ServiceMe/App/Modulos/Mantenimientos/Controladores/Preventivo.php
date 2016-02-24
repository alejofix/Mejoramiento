<?php
	
	class Preventivo extends Controlador {
		
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
			$Plantilla->Parametro('activo', __CLASS__);
			$Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Mantenimientos');
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Preventivo', 'Preventivo.html')));
		}

	}