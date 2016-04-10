<?php
	
	class Proyectos extends Controlador {
		
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
            $Plantilla->Parametro('Titulo', 'Proyectos');
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Proyectos', 'Proyectos.html')));
		}
		
		private function validacionForm() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, true);
			$Val->Requerido('PROYECTO', 'Informe Nombre del Proyecto.');
			$Val->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
                            ->TipoEnvio('POST')
                            ->Datos('#add_event_form')
                            ->URL(NeuralRutasApp::RutaUrlAppModulo('Mejoramiento', 'Proyectos', 'ProcesarIndex'))
                      		->FinalizadoEnvio('$("#respuestaLi").html(Respuesta);')
			);
			return $Val->Constructor('add_event_form');
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
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Proyectos', 'Seguimientos.html')));
		}
		
		
		public function ProcesarIndex() {
			if(isset($_POST) == true AND AppValidar::PeticionAjax() == true):
				$this->ProcesarProyecto();
			else:
				echo '
				No resuelve Petición Ajax ...
				';
			endif;
		}
		
		private function ProcesarProyecto() {
			Ayudas::print_r($_POST);
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->NuevoProyecto();
			else:
			echo '
				Proyecto sin Nombre...
				';
			endif;
		}
		
		public function NuevoProyecto () {
			
			$DatosPost = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
			unset($_POST, $DatosPost['PROYECTO']);
			$this->Modelo->GuardarInfo($datos);
			$this->plantilla();
			echo '
			Proyecto Creado ... 
			';
			
		}
		
		public function plantilla() {
			echo '
			!Asignar Analista!, 
			';
		}

	}