<?php
	
	/**
	 * Clase: Index_Modelo
	 */
	class Decodificadores_Modelo extends Modelo {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			$this->ConexionBD = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		/**
		 * Metodo: Lista de Decodificadores
		 */
		public function ConsultaListaDecos() {
		  $Consulta = $this->ConexionBD->prepare('
				SELECT 
					lista_decodificadores.MARCA,
					lista_decodificadores.REFERENCIA
				FROM 
					lista_decodificadores 
				WHERE 
					lista_decodificadores.ESTADO = ?
				ORDER BY MARCA ASC	
			');
			$Consulta->bindValue(1, 1);
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC); 
		   	
		}
	}