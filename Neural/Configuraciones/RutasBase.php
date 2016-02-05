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
	 
	/**
	 * Ruta de Acceso a Carpeta Root
	 * 
	 * Se utiliza la opcion dirname(dirname(__DIR__)) para tomar la ruta default de instalacion.
	 * Se puede reemplazar por la direccion fisica
	 *  
	 * @access private
	 * @example Windows [C:\www\htdocs]
	 * @example Linux [/opt/lampp/htdocs]
	 * */
	define('__SysNeuralFileRoot__', dirname(dirname(__DIR__)));
	
	/**
	 * Ruta de Acceso a Carpeta Root App
	 * 
	 * Se utiliza la opcion dirname(dirname(__DIR__)) para tomar la ruta default de instalacion.
	 * Se puede reemplazar por la direccion fisica
	 * 
	 * @access private
	 * @example Windows [C:\www\htdocs\App\]
	 * @example Linux [/opt/lampp/htdocs/App/] 
	 * */
	define('__SysNeuralFileRootApp__', implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRoot__, 'App')));
	
	/**
	 * Ruta de Acceso a Carpeta Root Configuracion
	 * 
	 * Se utiliza la opcion dirname(dirname(__DIR__)) para tomar la ruta default de instalacion.
	 * Se puede reemplazar por la direccion fisica
	 * 
	 * @access private
	 * @example Windows [C:\www\htdocs\Configuracion\]
	 * @example Linux [/opt/lampp/htdocs/Configuracion/] 
	 * */
	define('__SysNeuralFileRootConfiguracion__', implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRoot__, 'Configuracion')));
	
	/**
	 * Ruta de Acceso a Carpeta Root Neural
	 * 
	 * Se utiliza la opcion dirname(dirname(__DIR__)) para tomar la ruta default de instalacion.
	 * Se puede reemplazar por la direccion fisica
	 * 
	 * @access private
	 * @example Windows [C:\www\htdocs\Neural\]
	 * @example Linux [/opt/lampp/htdocs/Neural/] 
	 * */
	define('__SysNeuralFileRootLibNeural__', implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRoot__, 'Neural')));
	
	/**
	 * Ruta de Acceso de Separador de Doctrine 2 DBAL
	 * 
	 * Necesario para generar el enrutamiento base de doctrine 2
	 * Se puede reemplazar por la direccion fisica
	 * 
	 * @access private
	 * @example Windows [C:\www\htdocs\Proveedores\]
	 * @example Linux [/opt/lampp/htdocs/Proveedores/]
	 * */
	 define('__SysNeuralFileRootProveedores__', implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRoot__, 'Proveedores')));