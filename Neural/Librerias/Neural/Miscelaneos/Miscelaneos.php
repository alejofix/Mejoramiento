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

	namespace Neural\WorkSpace;
	
	class Miscelaneos {
		
		public static function LeerModReWrite() {
			$Url = (isset($_GET['Url'])) ? trim($_GET['Url']) : 'Predeterminado';
			if($Url != null) {
				$Url = strip_tags(rtrim($Url, '/'));
				$Array = explode('/', $Url);
				return (empty($Array[0]) == true) ? array('Predeterminado') : $Array;
			}
		}
	}