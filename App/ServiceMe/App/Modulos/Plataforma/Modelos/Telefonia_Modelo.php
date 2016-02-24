<?php
	
	/**
	 * Clase: Index_Modelo
	 */
	class Telefonia_Modelo extends Modelo {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			$this->ConexionBD = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		/**
		 * Metodo: Lista de CableModems
		 */
		public function ConsultaListaCMs() {
		  $Consulta = $this->ConexionBD->prepare('
				SELECT 
					lista_cablemodem.MARCA,
					lista_cablemodem.REFERENCIA,
					lista_cablemodem.FIRMWARE
				FROM 
					lista_cablemodem 
				WHERE 
					lista_cablemodem.ESTADO = ?
			');
			$Consulta->bindValue(1, 1);
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC); 
		   	
		}
        		/**
		 * Metodo: Lista de SoftSwich
		 */
		public function ConsultaListaSS() {
		  $Consulta = $this->ConexionBD->prepare('
				SELECT 
					lista_softswich.MARCA,
					lista_softswich.REFERENCIA
				FROM 
					lista_softswich 
				WHERE 
					lista_softswich.ESTADO = ?
			');
			$Consulta->bindValue(1, 1);
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC); 
		   	
		}
        
        
	}