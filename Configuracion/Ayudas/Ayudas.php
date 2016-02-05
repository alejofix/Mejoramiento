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
	 
	abstract class Ayudas {
		
		/**
		 * Ayudas::print_r($Array = false);
		 * 
		 * Metodo que imprime en pantalla el contenido de un array
		 * @param $Array: array de datos para ser impreso
		 */
		public static function print_r($Array = false) {
			if($Array == true) {
				echo '<code style="font-family: verdana; font-size: 11px;"><pre>';
				print_r($Array);
				echo '</pre></code>';
			}
		}
		
		/**
		 * Ayudas::var_dump($Array = false);
		 * 
		 * Metodo que muestra información estructurada sobre una o más 
		 * expresiones incluyendo su tipo y valor
		 * @param $Array: datos que pueden ser variables, objetos, array, etc.
		 */
		public static function var_dump($Array = false) {
			if($Array == true) {
				echo '<code style="font-family: verdana; font-size: 11px;"><pre>';
				var_dump($Array);
				echo '</pre></code>';
			}
		}
		
	}