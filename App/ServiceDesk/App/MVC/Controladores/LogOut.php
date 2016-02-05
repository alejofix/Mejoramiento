<?php
	
	class LogOut extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializar(APP);
			NeuralSesiones::Finalizar();
		}
		
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index'));
			exit();
		}
		
		public function Error($Mensaje = false) {
			header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index', 'Index', 'Index', array($Mensaje)));
			exit();
		}
	}