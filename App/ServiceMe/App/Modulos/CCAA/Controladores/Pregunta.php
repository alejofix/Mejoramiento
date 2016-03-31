<?php
	
	class Pregunta extends Controlador {
		
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index($tipo = false) {
			if(is_bool($tipo) == false):
				$this->validarTipo($tipo);
			else:
				echo '
				No hay Formulario Preguntas Seleccionado ...  
				';
			endif;
		}
		
		private function validarTipo($tipo = false) {
			if(is_numeric($tipo) == true):
				$this->existenciaTipo($tipo);
			else:
				echo '
				No hay Formulario Valido de Preguntas  ...
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
			$pl->Parametro('tipo', $tipo);
			$pl->Parametro('validacionForm1', $this->validacionForm1());
			$pl->Parametro('listadoTipoPregunta', $this->Modelo->listadoTipoPregunta());
			$pl->Parametro('listadoPreguntas', $this->Modelo->listadoPreguntas());
			echo $pl->MostrarPlantilla('Pregunta', 'MostrarPlantilla', $tipo.'.html');
		}
		
		private function validacionForm() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, true);
			$Val->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
                            ->TipoEnvio('POST')
                            ->Datos('#Formularios')
                            ->URL(NeuralRutasApp::RutaUrlAppModulo('CCAA', 'Pregunta', 'ProcesarIndex'))
                      		->FinalizadoEnvio('$("#respuestaDiv").html(Respuesta);')
			);
			return $Val->Constructor('Formularios');
		}
		
		/**
		 * validacionForm1::validacionForm1()
		 * 
		 * valida el formulario para MostrarPlantilla/1.html
		 */
		
		private function validacionForm1() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, true);
			$Val->Numero('CUENTA', 'La Cuenta debe de ser Numérica ');
			$Val->CantMaxCaracteres('CUENTA', 8, 'Debe ingresar max 8 Números');
			$Val->Requerido('CUENTA', 'Debe Ingresar Número Cta Suscriptor.');
			$Val->Requerido('PREGUNTA1', 'Debe Seleccionar una Opción.');
			$Val->Requerido('PREGUNTA2', 'Debe Seleccionar una Opción.');
			$Val->Requerido('PREGUNTA3', 'Debe Seleccionar una Opción.');
			$Val->Requerido('PREGUNTA4', 'Debe Seleccionar una Opción.');
			$Val->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
                            ->TipoEnvio('POST')
                            ->Datos('#Formularios')
                            ->URL(NeuralRutasApp::RutaUrlAppModulo('CCAA', 'Pregunta', 'ProcesarIndex'))
                      		->FinalizadoEnvio('$("#respuestaDiv").html(Respuesta);')
			);
			return $Val->Constructor('Formularios');
		}
		
		private function casos1() {
			
			
			
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
	 		Ayudas::print_r($_POST);
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