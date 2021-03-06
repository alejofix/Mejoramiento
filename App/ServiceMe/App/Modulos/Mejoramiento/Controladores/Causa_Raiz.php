<?php
	
	class Causa_Raiz extends Controlador {
		
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
            $Plantilla->Parametro('Titulo', 'Causa_Raiz');
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Causa_Raiz', 'Causa_Raiz.html')));
		}

		/**
		 * Index::Asignar()
		 * 
		 * genera la plantilla Asignar
		 * @return void
		 */		
		public function Asignar() {
			$Plantilla = new NeuralPlantillasTwig (APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Asignar');
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Causa_Raiz', 'Asignar.html')));
			
		}
		
		/**
		 * Index::Seguimientos()
		 * 
		 * genera la plantilla Seguimientos
		 * @return void
		 */
		public function Seguimientos() {
			$Plantilla = new NeuralPlantillasTwig (APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Seguimientos');
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Causa_Raiz', 'Seguimientos.html')));
			
		}

	}