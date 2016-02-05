<?php
	
	/**
	 * Controlador: Index
	 */
	class Index extends Controlador {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializar(APP);
			if(isset($_SESSION['SESIONEXPERTOS']) == true):
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Inicio'));
			endif;
		}
		
		/**
		 * Metodo: Index
		 */
		public function Index($error = false) {
			
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Filtro('Codificar', function($Texto) {
				return NeuralCriptografia::Codificar($Texto, array(date("Y-m-d"), APP));
			});
			$Plantilla->Parametro('Fecha', date("Y-m-d"));
			$Plantilla->Parametro('Error', $error);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Index', 'Login.html')));	
		}
		
		public function Prueba() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Index', 'Prueba.html')));
		}
	}