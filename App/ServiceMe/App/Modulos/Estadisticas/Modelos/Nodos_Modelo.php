<?php
	
	class Nodos_Modelo {
		
		private $conexion = false;
		
		function __construct() {
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APP);
		}
		
		public function mes($inicio = false, $fin = false) {
			$consulta = $this->conexion->prepare("
				SELECT 
					COUNT(GUIONES_REGISTRO.UBICACION) CANTIDAD, GUIONES_REGISTRO_UBICACION.NOMBRE
				FROM 
					GUIONES_REGISTRO
				INNER JOIN 
					GUIONES_REGISTRO_UBICACION 
   				ON 
				   	GUIONES_REGISTRO.UBICACION = GUIONES_REGISTRO_UBICACION.ID
				WHERE 
					UBICACION BETWEEN 1 AND 2
				GROUP BY UBICACION
			");
		}
	}