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
	 
	$PATHRAIZ = str_replace('\\', '/', dirname(__FILE__)) . '/';
	$RutaTemporal_1 = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
	$RutaTemporal_2 = explode('/', substr($PATHRAIZ, 0, -1));
	$RutaTemporal_3 = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));
	for ($i = count($RutaTemporal_2); $i<count($RutaTemporal_1); $i++)
		array_pop ($RutaTemporal_3);
	$UrlAddress = $_SERVER['HTTP_HOST'] . implode('/', $RutaTemporal_3); 
	if ($UrlAddress{strlen($UrlAddress) - 1}== '/')
		define('__NeuralUrlRaiz__', 'http://' . $UrlAddress);
	else
		define('__NeuralUrlRaiz__', 'http://' . $UrlAddress);
	unset($PATHRAIZ, $RutaTemporal_1, $RutaTemporal_2, $RutaTemporal_3, $UrlAddress); 