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

		private function mostrarPlantilla () {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('tipo', $tipo);
			$plantilla->Parametro('pregunta', $pregunta);
			echo $plantilla->MostrarPlantilla('Pregunta', $tipo, $pregunta.'.html');
		}
		
	}