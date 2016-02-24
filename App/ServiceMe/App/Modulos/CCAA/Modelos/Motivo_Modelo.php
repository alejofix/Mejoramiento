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
				$SQL->Sentencia('Fecha', date("Y-m-d H:i:s"));
			endforeach;
			return $SQL->Insertar();
		}
		
		/**
		 * 	CCAA:: listadoDecosRR()
		 *  listado referencia para tabla LISTA_REFERENCIA_DECOS_RR
		 * 	@return array
		 * 	@author alejo_fix
		 */
		public function listadoDecosRR() {
			$Consulta = $this->conexion->prepare('
				SELECT 
					LISTA_REFERENCIA_DECOS_RR.PLATAFORMA,
					LISTA_REFERENCIA_DECOS_RR.MARCA,
					LISTA_REFERENCIA_DECOS_RR.MODELO,
					LISTA_REFERENCIA_DECOS_RR.REFERENCIA					
				FROM 
					LISTA_REFERENCIA_DECOS_RR 
				WHERE 
					LISTA_REFERENCIA_DECOS_RR.ESTADO = ?
			'
			);
			$Consulta->bindValue(1, 1);
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC); 
		}
		
	}