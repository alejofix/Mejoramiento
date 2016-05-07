<?php

	class Perfil_Modelo extends Modelo {
		
		private $conexion = false;
		
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		
		/**
		 * Información Usuarios   
		 * @return array
		 * @author alejo_fix
		 */
		public function informacionUsuarios () {
			$consulta = $this->conexion->prepare('
				SELECT 
					ID, PASSWORD, NOMBRE, USUARIO_RR, CORREO, EMPRESA, CARGO, ESTADO, PERMISO, CEDULA 
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