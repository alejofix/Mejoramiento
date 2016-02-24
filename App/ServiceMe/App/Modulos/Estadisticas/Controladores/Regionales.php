<?php

	class Regionales extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index() {
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla('Regionales', 'Index.html');
		}
	}