<?php
	
	class Enlace extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Index::Index()
		 * 
		 * guión DTH - Plantilla MD
		 * @return void
		 */
		public function Link1() {
			$Plantilla = new NeuralPlantillasTwig(APP);
          	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Enlace', 'Link1.html')));
		}

	}