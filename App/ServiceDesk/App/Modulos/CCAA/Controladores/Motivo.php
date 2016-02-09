<?php
	
	class Motivo extends Controlador {
		
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index($tipo = false) {
			if(is_bool($tipo) == false):
				$this->validarTipo($tipo);
			else:
				echo '
				No hay Formulario Seleccionado ...  
				';
			endif;
		}
		
		private function validarTipo($tipo = false) {
			if(is_numeric($tipo) == true):
				$this->existenciaTipo($tipo);
			else:
				echo '
				No hay Formulario Valido ...
				';
			endif;
		}
		
		private function existenciaTipo($tipo = false) {
			$validar = $this->Modelo->existenciaTipo($tipo);
			
			if($validar['CANTIDAD'] == 1):
				$this->mostrarPlantilla($tipo);
			else:
				echo '
				Formulario Inexistente ...
				';
			endif;
		}
		
		private function mostrarPlantilla($tipo = false) {
			$pl = new NeuralPlantillasTwig(APP);
			$pl->Parametro('listado', $this->Modelo->listadoTipo($tipo));
			$pl->Parametro('validacionForm', $this->validacionForm());
			$pl->Parametro('tipo', $tipo);
			echo $pl->MostrarPlantilla('Motivo', 'MostrarPlantilla', $tipo.'.html');
		}
		
		private function validacionForm() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, true);
			$Val->Numero('CUENTA', 'El Aviso debe Ser Numérico');
			$Val->CantMaxCaracteres('CUENTA', 10, 'Debe ingresar max 10 Números');
			$Val->Requerido('CUENTA', 'Debe Ingresar Número Cta Suscriptor.');
			$Val->Requerido('RAZON', 'Debe Seleccionar una Opción');
			$Val->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
                            ->TipoEnvio('POST')
                            ->Datos('#Formularios')
                            ->URL(NeuralRutasApp::RutaUrlAppModulo('CCAA', 'Motivo', 'ProcesarIndex'))
                      		->FinalizadoEnvio('$("#respuestaDiv").html(Respuesta);')
			);
			return $Val->Constructor('Formularios');
		}
		
		
		public function ProcesarIndex() {
			if(isset($_POST) == true AND AppValidar::PeticionAjax() == true):
				$this->ProcesarIndexVacio();
			else:
				echo '
				No resuelve Petición Ajax ...
				';
			endif;
		}
		
		private function ProcesarIndexVacio() {
			// Ayudas::print_r($_POST);
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->guardarIndex();
			else:
			echo '
				Hay Datos vacíos en el Formulario ...
				';
			endif;
		}
		
		private function guardarIndex() {
			$datos = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
			$this->Modelo->GuardarInfo($datos);
			$this->plantilla();
			echo '
			Información almacenada con éxito ... 
			';
		}
		
		public function plantilla() {
			echo '
			!Gracias!, 
			';
		}
	}