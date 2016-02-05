<?php
	
	class Prueba extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index() {
			
			
		}
		
		public function Ciudad() {
			$conexion = NeuralConexionDB::DoctrineDBAL(APP);
			$consulta = $conexion->prepare('SELECT UPPER(NOMBRE_COMUNIDAD) CIUDAD, UPPER(DEPARTAMENTO) DEPARTAMENTO, UPPER(DIVISION) DIVISION FROM RADIOGRAFIA_NODOS GROUP BY NOMBRE_COMUNIDAD ORDER BY NOMBRE_COMUNIDAD ASC');
			$consulta->execute();
			$info = $consulta->fetchAll(PDO::FETCH_ASSOC);
			
			
			foreach ($info AS $array):
				$conexion->insert('RADIOGRAFIA_CIUDADES', $array);
				
			endforeach;
			
			echo 'Validar';
		}
	}