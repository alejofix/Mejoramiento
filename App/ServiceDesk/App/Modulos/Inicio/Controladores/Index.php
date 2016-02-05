<?php
	
	class Index extends Controlador {
		
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
            $Plantilla->Parametro('Titulo', 'Bienvenido');
            // $Plantilla->Parametro('Cchat', 'cantidadChat3');
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Inicio', 'Inicio.html')));
		}
		
		public function Prueba() {
			Ayudas::print_r($_SESSION);
			Ayudas::print_r(AppSesion::obtenerDatos());
		}
	}