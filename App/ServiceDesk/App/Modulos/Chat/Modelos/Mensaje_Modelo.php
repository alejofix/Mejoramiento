<?php
	
	class Mensaje_Modelo {
		
		private $conexion = false;
		
		/**
		 * Mensaje_Modelo::__construct()
		 * 
		 * Genera el objeto de conexion para ser
		 * utilizada en todo el modelo
		 * @return void
		 */
		function __construct() {
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		/**
		 * Mensaje_Modelo::guardar()
		 * 
		 * Genera el proceso de guardar el mensaje en la sala
		 * @param integer $IdSala
		 * @param string $mensaje
		 * @param integer $IdUsuario
		 * @return void
		 */
		public function guardar($IdSala = false, $mensaje = false, $IdUsuario = false) {
			$SQL = new NeuralBDGab($this->conexion, 'chat_mensaje');
			$SQL->Sentencia('FECHA', date("Y-m-d H:i:s"));
			$SQL->Sentencia('ID_USUARIO', $this->idUsuario($IdUsuario));
			$SQL->Sentencia('IP', $_SERVER['REMOTE_ADDR']);
			$SQL->Sentencia('MENSAJE', addslashes($mensaje));
			$SQL->Sentencia('TIPO', 1);
			$SQL->Sentencia('ID_SALA', $IdSala);
			$SQL->Insertar();
		}
		
		/**
		 * Mensaje_Modelo::idUsuario()
		 *
		 * Obtener el ID de usuario 
		 * @param string $usuario
		 * @return integer
		 */
		private function idUsuario($usuario = false) {
			$consulta = $this->conexion->prepare('SELECT ID FROM usuarios WHERE USUARIO_RR = ?');
			$consulta->bindValue(1, $usuario);
			$consulta->execute();
			$resultado = $consulta->fetch(PDO::FETCH_ASSOC);
			return $resultado['ID'];
		}
		
		/**
		 * Mensaje_Modelo::mensajes()
		 *
		 * Genera la consulta de los  
		 * @param bool $IdSala
		 * @return
		 */
		public function mensajes($IdSala = false) {
			$consulta = $this->conexion->prepare('
				SELECT 
					chat_mensaje.FECHA, 
				    usuarios.NOMBRE, 
				    usuarios.USUARIO_RR AS USUARIO, 
				    usuarios.EMPRESA, 
				    chat_mensaje.MENSAJE 
				FROM 
					chat_mensaje 
				INNER JOIN 
					usuarios 
				ON 
					usuarios.ID = chat_mensaje.ID_USUARIO
				WHERE 
					chat_mensaje.TIPO = ? 
				AND 
					chat_mensaje.ID_SALA = ?
				AND 
					chat_mensaje.FECHA BETWEEN ? AND ? 
				ORDER BY 
					chat_mensaje.FECHA DESC
			');
			$consulta->bindValue(1, 1);
			$consulta->bindValue(2, $IdSala);
			$consulta->bindValue(3, date("Y-m-d 00:00:00"));
			$consulta->bindValue(4, date("Y-m-d 23:59:59"));
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Mensaje_Modelo::salasMensajes()
		 * 
		 * Genera la lista de mensajes correspondientes
		 * @param array $array
		 * @return array
		 */
		public function salasMensajes($array = false) {
			foreach ($array as $sala):
				$lista['chat_'.$sala] = $this->mensajes($sala);
			endforeach;
			return $lista;
		}
	}