<?php

	class Pregunta_Modelo extends Modelo {
		
		private $conexion = false;
		
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);	
		}
		
		public function existenciaTipoPregunta($id = false) {
			$consulta = $this->conexion->prepare('
			SELECT 
				COUNT(ID) 
			AS 
				CANTIDAD 
			FROM 
				CCAA_PREGUNTA_TIPO 
			WHERE 
				ID = ? 
			AND 
				ESTADO = ? 
			');
			$consulta->bindValue(1, $id);
			$consulta->bindValue(2, 1);
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
	}