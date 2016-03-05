<?php
	
	class Mejoras_CCAA extends Controlador {
		
		/**
		 * Index::__Construct()
		 * 
		 * genera la validacion del permiso asignado para su visualizacion
		 * @return void
		 */
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('ESCRITURA');
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
            $Plantilla->Parametro('Titulo', 'Mejoras_CCAA');
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Mejoras_CCAA', 'Mejoras_CCAA.html')));
		}

	}