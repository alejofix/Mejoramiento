<?php
	
	class TriplePlay_Modelo extends Modelo {
		
		private $conexion = false;
		
		/**
		 * TriplePlay_Modelo::__Construct()
		 * 
		 * Genera la varible con la conexion necesaria
		 * @return void
		 */
		function __Construct() {
			parent::__Construct();
			$this->conexion = NeuralConexionDB::DoctrineDBAL(APPBD);
		}
		
		/**  
		 * TriplePlay_Modelo 	listadoRazonAveria
		 * genera el listado de las razones averia 
		 * define campo GUION de los guiones
		 * tabla GUIONES_RAZON_AVERIA 
		 * @return array
		 * @author alejo_fix
		 */
		
		
		public function listadoRazonAveria () {
			$consulta = $this->conexion->prepare('
			SELECT 
				ID, NOMBRE 
			FROM 
				GUIONES_RAZON_AVERIA 
			WHERE 
				ESTADO = ? 
			ORDER BY 
				ID DESC
			');
			$consulta->bindValue(1, 1, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		
		/**
		 * TriplePlay_Modelo::MATRIZ()
		 * 
		 * Genera el proceso de guardar el guion 3play
		 * 
		 * @param array $array
		 * @param string $usuario
		 * @param string $guion
		 * @return integer
		 */
		public function MATRIZ($peticion, $usuario = false, $guion = false) {
			$info = array('TIPO', 'AFECTACION', 'AVISO', 'UBICACION', 'PRIORIDAD', 'MATRIZ', 'DETALLE', 'REFERENCIA', 'INTERMITENCIA', 'GUION', 'AVERIA', 'RAZON');
			
			$SQL = $this->conexion->prepare('CALL GUIONES_REGISTRO_MATRIZ_INSERTAR(
			:FECHA, :USUARIO, :TIPO, :AFECTACION, :AVISO, :UBICACION, :PRIORIDAD, 
			:MATRIZ, :DETALLE, :REFERENCIA, :INTERMITENCIA, :GUION, :AVERIA, :RAZON, @OUTPUT)');
			$SQL->bindValue(':FECHA', date("Y-m-d H:i:s"));
			$SQL->bindValue(':USUARIO', $usuario);
			/*$SQL->bindValue(':TIPO', $array['TIPO']);
			$SQL->bindValue(':AFECTACION', $array['AFECTACION']);
			$SQL->bindValue(':AVISO', $array['AVISO']);
			$SQL->bindValue(':UBICACION', $array['UBICACION']);
			$SQL->bindValue(':PRIORIDAD', $array['PRIORIDAD']);
			$SQL->bindValue(':MATRIZ', $array['MATRIZ']);
			$SQL->bindValue(':DETALLE', $array['DETALLE']);
			$SQL->bindValue(':REFERENCIA', $array['REFERENCIA']);
			$SQL->bindValue(':INTERMITENCIA', $array['INTERMITENCIA']);
			$SQL->bindValue(':GUION', strip_tags(str_replace(array("\n", "\t", "\r", '--', '.'), '', trim($guion))));
			$SQL->bindValue(':AVERIA', $array['AVERIA']);
			$SQL->bindValue(':RAZON', $array['RAZON']);
			*/
			
			foreach ($info AS $parametro):
				if($parametro == 'GUION'):
						$SQL->bindValue(':'.$parametro, strip_tags(str_replace(array("\n", "\t", "\r", '--', '.'), '', trim($peticion->obtener($parametro)))));
				else:
					$SQL->bindValue(':'.$parametro, mb_strtoupper($peticion->obtener($parametro)));
				endif;
			endforeach;
			
			$SQL->execute();
			$consulta = $this->conexion->prepare('SELECT @OUTPUT AS ID');
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * TriplePlay_Modelo::consultaPlantilla()
		 * 
		 * Retorna la plantilla de guion correspondiente
		 * 
		 * @param string $grupo
		 * @param string $sub
		 * @param string $nombre
		 * @return array
		 */
		public function consultaPlantilla($grupo = false, $sub = false, $nombre = false) {
			$consulta = $this->conexion->prepare('SELECT PLANTILLA FROM GUIONES_PLANTILLA WHERE GRUPO = ? AND SUB = ? AND NOMBRE = ? AND ESTADO = ?');
			$consulta->bindValue(1, $grupo);
			$consulta->bindValue(2, $sub);
			$consulta->bindValue(3, $nombre);
			$consulta->bindValue(4, 1);
			$consulta->execute();
			return $consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * TriplePlay_Modelo::guardarNodos()
		 * 
		 * Genera el proceso de guardar los nodos
		 * correspondientes
		 * 
		 * @param array $array
		 * @param integer $registro
		 * @return void
		 */
		public function guardarNodos($array = false, $registro = false) {
			foreach ($array AS $nodo):
				$id = $this->guardarNodosExistencia(trim(mb_strtoupper($nodo)));
				$this->conexion->insert('GUIONES_REGISTRO_UBICACION_NODO', array('REGISTRO' => $registro, 'NODO' => $id));
			endforeach;
		}
		
		/**
		 * TriplePlay_Modelo::guardarNodosExistencia()
		 * 
		 * Genera la consulta dl nodo para obtener el id
		 * en dado caso que no exista
		 * 
		 * @param string $nodo
		 * @return integer
		 */
		private function guardarNodosExistencia($nodo = false) {
			$consulta = $this->conexion->prepare('SELECT COUNT(ID) CANTIDAD, ID FROM RADIOGRAFIA_NODOS WHERE NODO = ?');
			$consulta->bindValue(1, $nodo);
			$consulta->execute();
			$info = $consulta->fetch(PDO::FETCH_ASSOC);
			
			if($info['CANTIDAD'] == 1):
				return $info['ID'];
			else:
				$this->conexion->insert('RADIOGRAFIA_NODOS', array('NODO' => $nodo, 'CMTS' => 'PENDIENTE', 'NOMBRE_NODO' => 'PENDIENTE', 'COMUNIDAD' => 'PENDIENTE', 'NOMBRE_COMUNIDAD' => 'PENDIENTE', 'DEPARTAMENTO' => 'PENDIENTE', 'DANE' => '0', 'ESTATUS' => 'PENDIENTE', 'RED' => 'PENDIENTE', 'DIVISION' => 'PENDIENTE', 'HHPP' => '0', 'HOGARES' => '0', 'SERVICIOS' => '0', 'TIPOLOGIA' => 'PENDIENTE', 'ID_DIVISION' => '0', 'AREA' => 'PENDIENTE', 'ID_ZONA' => '0', 'ZONA' => 'PENDIENTE', 'ID_DISTRITO' => 'PENDIENTE', 'DISTRITO' => 'PENDIENTE', 'ID_GESTION' => 'PENDIENTE', 'CODIGO_ALIADO' => 'PENDIENTE', 'ALIADO' => 'PENDIENTE', 'TIPOLOGIA_RED' => 'PENDIENTE', 'ESTADO_NODO' => 'PENDIENTE'));
				$id = $this->conexion->lastInsertId();
				$this->conexion->insert('RADIOGRAFIA_NODOS_PENDIENTE', array('NODO' => $id, 'ESTADO' => 3));
				return $id;
			endif;
		}
		
		/**
		 * TriplePlay_Modelo::consultaUbicacion()
		 * 
		 * Genera la consulta de la ubicacion
		 * @param integer $ubicacion
		 * @return array
		 */
		public function consultaUbicacion($ubicacion = false) {
			$consultas = $this->conexion->prepare('SELECT NOMBRE FROM GUIONES_REGISTRO_UBICACION WHERE ID = ?');
			$consultas->bindValue(1, $ubicacion, PDO::PARAM_INT);
			$consultas->execute();
			return $consultas->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * TriplePlay_Modelo::listaAveria()
		 * 
		 * genera la lista de averias de los tipos de afectacion
		 * @return $averia
		 * @author alejo
		 */
		public function listaAveria() {
			$Consulta = $this->conexion->prepare('
				SELECT 
					GUIONES_TIPO_AFECTACION.NOMBRE,
					GUIONES_TIPO_AFECTACION.GUION,
					GUIONES_TIPO_AFECTACION.SERVICIO					
				FROM 
					GUIONES_TIPO_AFECTACION 
				WHERE 
					GUIONES_TIPO_AFECTACION.ESTADO = ?
			'
			);
			$Consulta->bindValue(1, 1);
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC); 
		}
		
	}