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
	
	class Controlador {
		
		
		function __Construct() {
			
		}
		
		/**
		 * Metodo Publico
		 * CargarModelo($RutaModelo = false, $Controlador = false, $AliasModelo = false, $ExtensionPHP = false)
		 * 
		 * Genera la carga correspondiente del modelo del controlador
		 * @access private
		 */
		public function CargarModelo($RutaModelo = false, $Controlador = false, $AliasModelo = false, $ExtensionPHP = false) {
			if(file_exists(implode(DIRECTORY_SEPARATOR, array($RutaModelo, $Controlador.$AliasModelo.$ExtensionPHP))) == true) {
				require implode(DIRECTORY_SEPARATOR, array($RutaModelo, $Controlador.$AliasModelo.$ExtensionPHP));
				$FormatoClase = trim($Controlador.$AliasModelo);
				$this->Modelo = new $FormatoClase;
			}
		}
	}