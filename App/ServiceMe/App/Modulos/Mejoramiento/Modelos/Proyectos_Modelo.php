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
		
		/**  
		 * tabla PROYECTOS_LISTA_BASE
		 * @return array
		 * @author alejo_fix
		 */
		
		
		public function listadoProyectosBase () {
			$consulta = $this->conexion->prepare('
				SELECT 
					ID, NOMBRE, FECHA 
				FROM 
					PROYECTOS_LISTA_BASE 
				WHERE 
					ESTADO = ? 
				ORDER BY 
					FECHA 
				ASC
			');
			$consulta->bindValue(1, 5, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		
		/**
		 * lista Analistas  
		 * Cargo 1 MEJORAMIENTO
 
		 * @return array
		 * @author alejo_fix
		 */
		 		
		public function listadoAnalistasMejoramiento () {
			$consulta = $this->conexion->prepare('
				SELECT 
					ID, NOMBRE, USUARIO_RR, CORREO, EMPRESA, CARGO, ESTADO, PERMISO 
				FROM 
					USUARIOS 
				WHERE 
					PERMISO = ?
                AND
                	ESTADO = ?
				ORDER BY
					ID
				DESC
			');
			$consulta->bindValue(1, 2, PDO::PARAM_INT);
			$consulta->bindValue(2, 1);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
	}