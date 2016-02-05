<?php
	
	class Historial_Modelo {
		
		private $conexion = false;
		
		function __construct() {
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		public function consulta($array = false, $cantidad = false) {
			return array(
				'Salas' => $this->cantidadSala($array, $cantidad),
				'Cantidad' => $this->sumaCantidad($array, $cantidad)
			);
		}
		
		/**
		 * Historial_Modelo::cantidadSala()
		 * 
		 * Genera la cantidad 
		 * @param bool $array
		 * @param bool $cantidad
		 * @return array
		 */
		private function cantidadSala($array = false, $cantidad = false) {
			$puntero = $this->punteros($cantidad);
			$consulta = $this->conexion->prepare("
				SELECT 
					chat_mensaje.ID_SALA AS SALA, 
					COUNT(chat_mensaje.ID) AS CANTIDAD
				FROM 
					chat_mensaje 
				WHERE 
					chat_mensaje.ID_SALA IN({$puntero}) 
				AND 
					chat_mensaje.FECHA BETWEEN ? AND ? 
				GROUP BY 
					chat_mensaje.ID_SALA
			");
			
			$incremento = 1;
			foreach ($array AS $valor):
				$consulta->bindValue($incremento, $valor);
				$incremento++;
			endforeach;
			
			$consulta->bindValue($incremento++, date("Y-m-d 00:00:00"));
			$consulta->bindValue($incremento, date("Y-m-d 23:59:59"));
			
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Historial_Modelo::sumaCantidad()
		 *
		 * Genera la consulta y obtiene la cantidad 
		 * @param array $array
		 * @param integer $cantidad
		 * @return integer
		 */
		private function sumaCantidad($array = false, $cantidad = false) {
			$puntero = $this->punteros($cantidad);
			$consulta = $this->conexion->prepare("
				SELECT 
					COUNT(chat_mensaje.ID) AS CANTIDAD
				FROM 
					chat_mensaje 
				WHERE 
					chat_mensaje.ID_SALA IN({$puntero}) 
				AND 
					chat_mensaje.FECHA BETWEEN ? AND ?
			");
			
			$incremento = 1;
			foreach ($array AS $valor):
				$consulta->bindValue($incremento, $valor);
				$incremento++;
			endforeach;
			
			$consulta->bindValue($incremento++, date("Y-m-d 00:00:00"));
			$consulta->bindValue($incremento, date("Y-m-d 23:59:59"));
			
			$consulta->execute();
			$data = $consulta->fetch(PDO::FETCH_ASSOC);
			return $data['CANTIDAD'];
		}
		
		/**
		 * Historial_Modelo::punteros()
		 * 
		 * Genera el puntero para el prepare del proceso de query
		 * @param integer $cantidad
		 * @return string ?, ?, ?....
		 */
		private function punteros($cantidad = false) {
			for ($i=1; $i<=$cantidad; $i++):
				$lista[] = '?';
			endfor;
			return implode(', ', $lista);
		}
	}