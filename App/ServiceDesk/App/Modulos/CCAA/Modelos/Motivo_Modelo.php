<?php
	
	class Motivo_Modelo extends Modelo {
		
		private $conexion = false;
		
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		public function existenciaTipo($id = false) {
			$consulta = $this->conexion->prepare('SELECT COUNT(ID) AS CANTIDAD FROM CCAA_MOTIVO_TIPO WHERE ID = ? AND ESTADO = ?');
			$consulta->bindValue(1, $id);
			$consulta->bindValue(2, 1);
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		public function listadoTipo($tipo = false) {
			$consulta = $this->conexion->prepare('SELECT ID, RAZON FROM CCAA_MOTIVO_RAZON WHERE TIPO = ? AND ESTADO = ?');
			$consulta->bindValue(1, $tipo);
			$consulta->bindValue(2, 1);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function GuardarInfo($array = false) {
			$SQL = new NeuralBDGab($this->conexion, 'CCAA_MOTIVO');
			foreach ($array AS $columna => $valor):
				$SQL->Sentencia($columna, $valor);
			endforeach;
			return $SQL->Insertar();
		}
	}