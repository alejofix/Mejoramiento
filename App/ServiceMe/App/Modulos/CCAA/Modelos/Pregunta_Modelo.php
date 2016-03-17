<?php
	
	class Pregunta_Modelo extends Modelo {
		
		private $conexion = false;
		
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		public function existenciaTipo($id = false) {
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
		
		public function listadoTipo($tipo = false) {
			$consulta = $this->conexion->prepare('
			SELECT 
				ID, OPCION 
			FROM 
				CCAA_PREGUNTA_OPCION 
			WHERE 
				TIPO = ? 
			AND 
				ESTADO = ?
			');
			$consulta->bindValue(1, $tipo);
			$consulta->bindValue(2, 1);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function GuardarInfo($array = false) {
			$SQL = new NeuralBDGab($this->conexion, 'CCAA_PREGUNTA');
			foreach ($array AS $columna => $valor):
				$SQL->Sentencia($columna, $valor);
				$SQL->Sentencia('Fecha', date("Y-m-d H:i:s"));
			endforeach;
			return $SQL->Insertar();
		}
		
		/**
		 * * CCAA listadoTipoPregunta
		 * lista la informacón de formularios preguntas
		 * @author alejo_fix
		 * @return Array
		 */
		
		 public function listadoTipoPregunta(){
		 	$consulta = $this->conexion->prepare('
			SELECT 
				CCAA_PREGUNTA_TIPO.ID,
			    CCAA_PREGUNTA_TIPO.NOMBRE,
			    CCAA_PREGUNTA_TIPO.SERVICIO,
			    CCAA_PREGUNTA_TIPO.ESTADO
			FROM
				CCAA_PREGUNTA_TIPO
			WHERE 
				ESTADO = ?	
			 ');
			$consulta->bindValue(1, 1);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		 }		
		
		/**
		 * CCAA listadoPreguntas()
		 * Listado de preguntas del formato Preguntas CCAA
		 * @author alejo_fix
		 * @return Array
		 */	
		 
		 public function listadoPreguntas($tipo = false){
		 	$consulta = $this->conexion->prepare('
 			SELECT 
				CCAA_PREGUNTA_OPCION.ID,
				CCAA_PREGUNTA_OPCION.TIPO,
				CCAA_PREGUNTA_OPCION.OPCION,
				CCAA_PREGUNTA_OPCION.ESTADO 
			FROM 
				CCAA_PREGUNTA_OPCION 
			WHERE 
				ESTADO = ?	
			AND 
				TIPO = ?  	
			 ');
			$consulta->bindValue(1, 1);
			$consulta->bindValue(2, $tipo);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		 }
	 	
	}