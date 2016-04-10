<?php

	class Proyectos_Modelo extends Modelo {
		
		private $conexion = false;
		
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		public function NuevoProyecto ($array = false, $usuario = false) {
			$SQL = new NeuralBDGab($this->conexion, 'PROYECTOS_LISTA_BASE');
			foreach ($array AS $columna => $valor):
			$SQL->Sentencia($columna, $valor);
			$SQL->Sentencia('Fecha', date("Y-m-d H:i:s"));
			endforeach;
			return $SQL->Insertar();
		}
		
	}