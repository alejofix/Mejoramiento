<?php
	
	class Dispositivos_Modelo {
		
		private $conexion = false;
		
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
		 * Dispositivos_Modelo::avisos()
		 * 
		 * Consulta los avisos activos
		 * @return array
		 */
		public function avisos() {
			$consulta = $this->conexion->prepare('
				SELECT 
					comunicacion_avisos.AVISO, 
					comunicacion_avisos.REGIONAL, 
					comunicacion_avisos.FALLA, 
					comunicacion_avisos.DETALLE, 
					comunicacion_avisos.PRIORIDAD, 
					COMUNICACION_AVISO_DETALLE.NOMBRE AS DETALLEFALLA
				FROM 
					comunicacion_avisos 
				INNER JOIN 
					COMUNICACION_AVISO_DETALLE 
				ON 
					COMUNICACION_AVISO_DETALLE.ID = comunicacion_avisos.DETALLE 
				WHERE 
					comunicacion_avisos.ESTADO = ? 
				ORDER BY 
				comunicacion_avisos.PRIORIDAD ASC, comunicacion_avisos.AVISO ASC
			');
			$consulta->bindValue(1, 1);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
			
		}
	}