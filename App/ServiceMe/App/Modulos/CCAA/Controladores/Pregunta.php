<?php

	class Pregunta extends Controlador {
		
		function __Construct (){
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
				$this->existenciaTipoPregunta($tipo);
			else:
				echo '
				No hay Formulario Valido ...
				';
			endif;
		}
		
		private function existenciaTipoPregunta($tipo = false) {
			$validar = $this->Modelo->existenciaTipoPregunta($tipo);
			
			if($validar['CANTIDAD'] == 1):
				$this->mostrarPlantilla($tipo);
			else:
				echo '
				Formulario Inexistente ...
				';
			endif;
		}

		private function mostrarPlantilla () {
			$pl = new NeuralPlantillasTwig(APP);
			$pl->Parametro('tipo', $tipo);
			$pl->Parametro('listado', $this->Modelo->listadoTipoPregunta($tipo));
			echo $pl->MostrarPlantilla('Pregunta', 'MostrarPlantilla', $tipo.'.html');
		}
		
	}