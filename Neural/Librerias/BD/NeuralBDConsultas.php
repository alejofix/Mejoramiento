<?php
	
	/**
	 * NeuralPHP Framework
	 * Marco de trabajo para aplicaciones web.
	 * 
	 * @author Zyos (Carlos Parra) <Neural.Framework@gmail.com>
	 * @copyright 2006-2014 NeuralPHP Framework
	 * @license GNU General Public License as published by the Free Software Foundation; either version 2 of the License. 
	 * @license Incluida licencia carpeta de Informacion 
	 * @see http://neuralphp.url.ph/
	 * @version 3.0
	 * 
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 */
	
	class NeuralBDConsultas {
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoColumna;
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoTabla;
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoInnerJoin;
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoCondiciones;
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoAgrupar;
		
		/**
		 * Contenedor Basico de Herencia
		 * @access private
		 */
		private $ListadoOrdenar;
		
		/**
		 * Constante de Aplicacion por Defecto
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Constante de Ascendente
		 */
		const ASC = 'ASC';
		
		/**
		 * Constante de Descendente
		 */
		const DESC = 'DESC';
		
		/**
		 * Constructor
		 * 
		 * Genera la asignacion de la aplicacion para la conexion a la Base de Datos
		 * @param $App: aplicacion configurada en el archivo de config Base de datos
		 */
		function __Construct($App = self::PREDETERMINADO) {
			$this->APP = $App;
		}
		
		/**
		 * Metodo Magico
		 * 
		 * Para la Ejecucion como string el Objeto
		 */
		function __toString() {
			self::Ejecutar();
		}
		
		/**
		 * Metodo Publico
		 * Tabla($Tabla = false)
		 * 
		 * Asignacion de la Tabla de la base de datos
		 * @param $Tabla: Nombre de la Tabla
		 */
		public function Tabla($Tabla = false) {
			if(is_array($Tabla) == true) {
				$this->ListadoTabla = implode(', ', $Tabla);
			}
			else {
				$this->ListadoTabla = trim($Tabla);
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * Columnas($Columnas = false)
		 * 
		 * Asignacion de las columnas de la consulta
		 * @param $Columnas: se indica las columnas a mostrar
		 * se puede manejar pasando como string columnas separadas 
		 * por comas o como una matriz
		 * @example Columnas('Columna1, Columna2')
		 * @example Columnas(array('Columna1', 'Columna2'))
		 */
		public function Columnas($Columnas = false) {
			if(is_array($Columnas) == true) {
				$this->ListadoColumna = implode(', ', $Columnas);
			}
			else {
				$this->ListadoColumna = trim($Columnas);
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * InnerJoin($Tabla2 = false, $ColumnaTabla1 = false, $ColumnaTabla2 = false)
		 * 
		 * Genera una sentencia Inner Join
		 * @param $Tabla2: Nombre de la Tabla Secundaria
		 * @param $ColumnaTabla1: Columna de la Tabla Primaria
		 * @param $ColumnaTabla2: Columna de la Tabla Secundaria
		 */
		public function InnerJoin($Tabla2 = false, $ColumnaTabla1 = false, $ColumnaTabla2 = false) {
			if($Tabla2 == true AND $ColumnaTabla1 == true AND $ColumnaTabla2 == true) {
				$this->ListadoInnerJoin[] = implode(' ', array('INNER JOIN', $Tabla2, 'ON', $ColumnaTabla1, '=', $ColumnaTabla2));
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * Condicion($Condicion = false)
		 * 
		 * Espacio designado para indicar las condiciones de la consulta
		 * @param $Condicion: Condicion de la consulta
		 */
		public function Condicion($Condicion = false) {
			if($Condicion == true) {
				$this->ListadoCondiciones[] = trim($Condicion);
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * Agrupar($Columna = false)
		 * 
		 * Asigna las columnas que se agruparan
		 * @param $Columna: columna o columnas por las cuales se agruparan
		 * @example Agrupar('Columna1, Columna2')
		 * @example Agrupar(array('Columna1', 'Columna2'))
		 */
		public function Agrupar($Columna = false) {
			if($Columna == true) {
				if(is_array($Columna) == true) {
					$this->ListadoAgrupar = implode(', ', $Columna);
				}
				else {
					$this->ListadoAgrupar = trim($Columna);
				}
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * Ordenar($Columna = false, $Tipo = self::ASC)
		 * 
		 * Ordena la consulta por las columnas correspondiente
		 * @param $Columna: Nombre de la columna a ordenar
		 * @param $Tipo: tipo de ordenamiento ASC o DESC
		 */
		public function Ordenar($Columna = false, $Tipo = self::ASC) {
			if($Columna == true AND $Tipo == true) {
				$this->ListadoOrdenar[] = implode(' ', array($Columna, strtoupper($Tipo)));
			}
			return $this;
		}
		
		/**
		 * Metodo Publico
		 * Ejecutar($Cantidad = true, $Query = true)
		 * 
		 * Ejecuta el query correspondiente
		 * @param $Cantidad: valor true o false para regresar el valor de la consulta propuesta
		 * @param $Query: regresa el listado de resultados de la consulta
		 */
		public function Ejecutar($Cantidad = true, $Query = true) {
			$QuerySQL = self::ConstructorQuery_Final();
			$Conexion = self::ConexionDB();
			$Consulta = $Conexion->prepare($QuerySQL);
			$Consulta->execute();
			if($Cantidad == true AND $Query == true) {
				return array_merge(array('Cantidad' => $Consulta->rowCount()), $Consulta->fetchAll(PDO::FETCH_ASSOC));
			}
			elseif($Cantidad == true) {
				return array('Cantidad' => $Consulta->rowCount());
			}
			elseif($Query == true) {
				return $Consulta->fetchAll(PDO::FETCH_ASSOC);
			}
			else {
				return array('No Hay Solicitud Activa para Procesar');
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_Final()
		 * 
		 * Organiza y construye el query SQL
		 * @access private
		 */
		private function ConstructorQuery_Final() {
			return implode(' ', array(self::ConstructorQuery_Columnas(), self::ConstructorQuery_From(), self::ConstructorQuery_InnerJoin(), self::ConstructorQuery_Condiciones(), self::ConstructorQuery_Agrupar(), self::ConstructorQuery_Ordenar()));
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_Columnas()
		 * 
		 * Construye el segmento de las columnas
		 * @access private
		 */
		private function ConstructorQuery_Columnas() {
			if(isset($this->ListadoColumna) == true) {
				return implode(' ', array('SELECT', $this->ListadoColumna));;
			}
			else {
				return implode(' ', array('SELECT', '*'));
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_From()
		 * 
		 * Construye el segmento del from
		 * @access private
		 */
		private function ConstructorQuery_From() {
			if(isset($this->ListadoTabla) == true) {
				return implode(' ', array('FROM', $this->ListadoTabla));
			}
			else {
				return implode(' ', array('FROM', Self::PREDETERMINADO));
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_InnerJoin()
		 * 
		 * Construye el segmento de los INNER JOIN
		 * @access private
		 */
		private function ConstructorQuery_InnerJoin() {
			if(isset($this->ListadoInnerJoin) == true) {
				return implode(' ', $this->ListadoInnerJoin);
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_Condiciones()
		 * 
		 * Construye el segmento de las condiciones
		 * @access private
		 */
		private function ConstructorQuery_Condiciones() {
			if(isset($this->ListadoCondiciones) == true) {
				return implode(' ', array('WHERE', implode(' AND ', $this->ListadoCondiciones)));
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_Agrupar()
		 * 
		 * Construye el segmento de la agrupacion
		 * @access private
		 */
		private function ConstructorQuery_Agrupar() {
			if(isset($this->ListadoAgrupar) == true) {
				return implode(' ', array('GROUP BY', $this->ListadoAgrupar));
			}
		}
		
		/**
		 * Metodo Privado
		 * ConstructorQuery_Ordenar
		 * 
		 * Construye el segmento del ordenamiento
		 * @access private
		 */
		private function ConstructorQuery_Ordenar() {
			if(isset($this->ListadoOrdenar) == true) {
				return implode(' ', array('ORDER BY', implode(', ', $this->ListadoOrdenar)));
			}
		}
		
		/**
		 * Metodo Privado
		 * ConexionDB()
		 * 
		 * Valida si se recibe una conexion o se debe llamar la conexion correspondiente
		 * @access private
		 */
		private function ConexionDB() {
			if(is_object($this->APP) == true) {
				return $this->APP;
			}
			else {
				return NeuralConexionDB::DoctrineDBAL($this->APP);
			}
		}
	}