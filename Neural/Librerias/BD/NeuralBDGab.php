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
	 
	class NeuralBDGab {
		
		/**
		 * Aplicacion predeterminado
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Constructor
		 * 
		 * Asigna el valor correspondiente de la aplicacion y la tabla correspondiente
		 * @param $App: Nombre de la aplicacion o el objeto de conexion a la base de datos
		 * @param $Tabla: Nombre de la tabla donde se realizara el procedimiento
		 */
		function __Construct($App = self::PREDETERMINADO, $Tabla = false) {
			$this->ConexionBD = self::Conexion($App);
			$this->TablaDB = trim($Tabla);
		}
		
		/**
		 * Metodo Publico
		 * Actualizar()
		 * 
		 * Accion para realizar el procedimiento solicitado
		 */
		public function Actualizar() {
			if(isset($this->TablaDB) == true AND isset($this->Sentencias) == true) {
				return $this->ConexionBD->update($this->TablaDB, $this->Sentencias, $this->Condiciones);
			}
			else {
				throw new NeuralException('No Se Puede Actualizar Los Datos, Debe Ingresar la Tabla y las Sentencias Correspondientes');
			}
		}
		
		/**
		 * Metodo Publico
		 * Condicion($Columna = false, $Valor = false)
		 * 
		 * Asigna el Valor de la condicion correspondiente para la accion
		 * @param $Columna: nombre de la columna de la condicion indicada
		 * @param $Valor: valor que recibira la columna correspondiente
		 */
		public function Condicion($Columna = false, $Valor = false) {
			$this->Condiciones[trim($Columna)] = trim($Valor);
		}
		
		/**
		 * Metodo Privado
		 * Conexion($App = self::PREDETERMINADO)
		 * 
		 * Genera el proceso general de la conexion a la base de datos
		 */
		private function Conexion($App = self::PREDETERMINADO) {
			if(is_object($App) == true) {
				return $App;
			}
			else {
				return NeuralConexionDB::DoctrineDBAL($App);
			}
		}
		
		/**
		 * Metodo Publico
		 * Eliminar()
		 * 
		 * Accion para realizar el procedimiento solicitado
		 */
		public function Eliminar() {
			if(isset($this->TablaDB) == true AND isset($this->Condiciones) == true) {
				return $this->ConexionBD->delete($this->TablaDB, $this->Condiciones);
			}
			else {
				throw new NeuralException('No Se Puede Eliminar Los Datos, Debe Ingresar la Tabla y las Condiciones Correspondientes');
			}
		}
		
		/**
		 * Metodo Publico
		 * Insertar()
		 * 
		 * Accion para realizar el procedimiento solicitado
		 */
		public function Insertar() {
			if(isset($this->TablaDB) == true AND isset($this->Sentencias) == true) {
				return $this->ConexionBD->insert($this->TablaDB, $this->Sentencias);
			}
			else {
				throw new NeuralException('No Se Puede Insertar Los Datos, Debe Ingresar la Tabla y las Sentencias Correspondientes');
			}
		}
		
		/**
		 * Metodo Publico
		 * Sentencia($Columna = false, $Valor = false)
		 * 
		 * Asigna el Valor de la sentencia correspondiente para la accion
		 * @param $Columna: nombre de la columna de la sentencia indicada
		 * @param $Valor: valor que recibira la columna correspondiente
		 */
		public function Sentencia($Columna = false, $Valor = false) {
			$this->Sentencias[trim($Columna)] = trim($Valor);
		}
	}