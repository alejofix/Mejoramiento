<?php
	
	class Alto_Impacto_Modelo extends Modelo {
		
		private $conexion = false;
		private $contenido = false;
		
		/**
		 * Dispositivos_Modelo::__construct()
		 * 
		 * Genera el proceso de la conexion a la base de datos para todo el modelo
		 * @return void
		 */
		function __construct() {
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		/**
		 * Alto_Impacto_Modelo::guardarAltoImpacto()
		 * 
		 * genera el proceso de guardar un nuevo aviso de alto impacto
		 * @param bool $array
		 * @param bool $usuario
		 * @return array
		 */
		public function guardarAltoImpacto($array = false, $usuario = false) {
			$consulta = $this->conexion->prepare('CALL COMUNICACION_AVISOS_INSERTAR(:FECHA, :USUARIO, :ESTADO, :AVISO, :REGIONAL, :FALLA, :DETALLE, :PRIORIDAD, @ID);');
			foreach ($array AS $columna => $valor):
				$consulta->bindValue(':'.mb_strtoupper($columna), $valor);
			endforeach;
			$consulta->bindValue(':FECHA', date("Y-m-d H:i:s"));
			$consulta->bindValue(':ESTADO', 1, PDO::PARAM_INT);
			$consulta->bindValue(':USUARIO', $usuario, PDO::PARAM_STR);
			$consulta->execute();
			
			$consulta = $this->conexion->prepare('SELECT @ID AS STATUS');
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Alto_Impacto_Modelo::actualizarAltoImpacto()
		 * 
		 * Genera el proceso de eliminacion de aviso
		 * Genera el proceso de actualizacion de aviso
		 * @param bool $array
		 * @param bool $usuario
		 * @return array
		 */
		public function actualizarAltoImpacto($array = false, $usuario = false) {
			$consulta = $this->conexion->prepare('CALL COMUNICACION_AVISOS_EDITAR(
				:ID_AVISO,
				:USUARIO_INPUT,
				:REGIONAL_INPUT,
				:FALLA_INPUT,
				:DETALLE_INPUT, 
				:PRIORIDAD_INPUT,
				@EDICION
			);');
			
			foreach ($array AS $columna => $valor):
				$consulta->bindValue(':'.$columna, $valor);
			endforeach;
			
			$consulta->bindValue(':USUARIO_INPUT', $usuario, PDO::PARAM_STR);
			$consulta->execute();
			
			$consulta = $this->conexion->prepare('SELECT @EDICION AS STATUS');
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Alto_Impacto_Modelo::finalizarAltoImpacto()
		 * 
		 * genera la finalizacion de aviso
		 * @param bool $array
		 * @param bool $usuario
		 * @return array
		 */
		public function finalizarAltoImpacto($id = false, $usuario = false) {
			$consulta = $this->conexion->prepare('CALL COMUNICACION_AVISOS_FINALIZAR(
				:AVISO, 
				:ESTADO, 
				:USUARIO,
				@FIN
			);');
			$consulta->bindValue(':AVISO', $id);
			$consulta->bindValue(':ESTADO', 4);
			$consulta->bindValue(':USUARIO', $usuario);
			$consulta->execute();
			
			$consulta = $this->conexion->prepare('SELECT @FIN AS STATUS');
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Alto_Impacto_Modelo::eliminarAltoImpacto()
		 * 
		 * @param bool $id
		 * @param bool $usuario
		 * @return
		 */
		public function eliminarAltoImpacto($id = false, $usuario = false) {
			$consulta = $this->conexion->prepare('CALL COMUNICACION_AVISOS_ELIMINAR(:ID, :USUARIO, @ELIMINAR);');
			$consulta->bindValue(':ID', $id, PDO::PARAM_INT);
			$consulta->bindValue(':USUARIO', $usuario, PDO::PARAM_STR);
			$consulta->execute();
			
			$consulta = $this->conexion->prepare('SELECT @ELIMINAR AS STATUS');
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Alto_Impacto_Modelo::listadoAltoImpacto()
		 * 
		 * lISTADO DE AVISOS
		 * @return array
		 */
		public function listadoAltoImpacto() {
			$consulta = $this->conexion->prepare('SELECT ID, FECHA, AVISO, REGIONAL, FALLA, PRIORIDAD FROM comunicacion_avisos WHERE ESTADO = ? ORDER BY PRIORIDAD ASC, FECHA ASC');
			$consulta->bindValue(1, 1, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Alto_Impacto_Modelo::ActualizarConsulta()
		 * 
		 * Genera la consulta del id para editar
		 * @param integer $id
		 * @return integer
		 */
		public function ActualizarConsulta($id = false) {
			$consulta = $this->conexion->prepare('SELECT ID, FECHA, AVISO, REGIONAL, FALLA, DETALLE, PRIORIDAD FROM comunicacion_avisos WHERE ID = ?');
			$consulta->bindValue(1, $id, PDO::PARAM_INT);
			$consulta->execute();
			$this->contenido = $consulta->fetch(PDO::FETCH_ASSOC);
			return $consulta->rowCount();
		}
		
		/**
		 * Alto_Impacto_Modelo::ActualizarConsultaDatos()
		 * 
		 * Retorna el contenido de la consulta
		 * @return array
		 */
		public function ActualizarConsultaDatos() {
			return $this->contenido;
		}
		
		/**
		 * Alto_Impacto_Modelo::DetalleLista()
		 * 
		 * Genera el listado de detalles
		 * @return array
		 */
		public function DetalleLista(){
			$consulta = $this->conexion->prepare('SELECT ID, NOMBRE FROM COMUNICACION_AVISO_DETALLE WHERE ESTADO = ? ORDER BY NOMBRE ASC');
			$consulta->bindValue(1, 1, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function consultarNombreDetalle($id = false) {
			$consulta = $this->conexion->prepare('SELECT NOMBRE FROM COMUNICACION_AVISO_DETALLE WHERE ID = ?');
			$consulta->bindValue(1, $id, PDO::PARAM_INT);
			$consulta->execute();
			$data = $consulta->fetch(PDO::FETCH_ASSOC);
			return $data['NOMBRE'];
		}
	}