<?php
	
	class Perfil extends Controlador {
		
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
		 * Perfil():Contrasena():Editar()
		 * genera la plantilla inicial
		 * @return void
		 */
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Perfil');
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Perfil', 'Perfil.html')));
		}
		
		public function Contrasena() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Perfil');
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Perfil', 'Contrasena.html')));
		}
		
		public function Editar() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Perfil');
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Perfil', 'Editar.html')));
		}

	}