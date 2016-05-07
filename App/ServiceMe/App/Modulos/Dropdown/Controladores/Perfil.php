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
			$Val = new NeuralJQueryFormularioValidacion(true, true, false);  
            $Val->Requerido('password0', 'Ingrese su Contraseña Actual');	
			$Val->Requerido('password1', 'Ingrese su Nueva Contraseña');
			$Val->Requerido('password2', 'Confirme su Nueva Contraseña');
				
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
		    $Plantilla->Parametro('Titulo', 'Perfil');
            $Plantilla->Parametro('validacion', $Val->Constructor('formularioContrasena'));
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Perfil', 'Contrasena.html')));
		}
		
		public function Editar() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Perfil');
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Perfil', 'Editar.html')));
		}
		
	}