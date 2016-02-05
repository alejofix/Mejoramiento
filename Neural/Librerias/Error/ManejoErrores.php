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
	 
	class NeuralException extends Exception {
		
		/**
		 * Constructor
		 */
		public function __Construct($Mensaje, $Codigo = 0, Exception $Previo = null) {
			parent::__Construct($Mensaje, $Codigo, $Previo);
		}
		
		/**
		 * Metodo Magico
		 * __toString()
		 */
		public function __toString() {
			return self::PlantillaError('Error', $this);
		}
		
		/**
		 * Metodo Publico
		 * PlantillaError($Titulo = 'Error', $Objeto = false)
		 * 
		 * Genera la plantilla correspondiente para mostrar los datos del error
		 */
		public function PlantillaError($Titulo = 'Error', $Objeto = false) {
			$getFile = array_reverse(explode(DIRECTORY_SEPARATOR, $Objeto->getFile()));
			$Twig_Loader_Filesystem = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootLibNeural__, 'Errores', 'Alertas'));
			$Twig_Environment_Array = array('charset' => 'UTF-8');
			self::Autoloader();
			$Cargador = new Twig_Loader_Filesystem($Twig_Loader_Filesystem);
			$Twig = new Twig_Environment($Cargador, $Twig_Environment_Array);
			if(defined('APPNEURALPHPHOST') == true) {
				$Twig->addGlobal('NeuralRutaApp', implode('/', array(__NeuralUrlRaiz__)));
				$Twig->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'WebRoot', 'ErroresWeb')));
			}
			else {
				$Twig->addGlobal('NeuralRutaBase', __NeuralUrlRaiz__);
				$Twig->addGlobal('NeuralRutaWebPublico', implode('/', array(__NeuralUrlRaiz__, 'Web')));
			}
			echo $Twig->render('Base.html', array('Titulo' => $this->getMessage(), 'Informacion' => '<strong>Error</strong>: '.$Objeto->getMessage().'<br /><strong>Linea</strong>: '.$Objeto->getLine().'<br /><strong>Archivo</strong>: '.$getFile[0]));
		}
		
		/**
		 * Metodo Privado
		 * Autoloader()
		 * 
		 * Determina si ya fue incluida la libreria de twig
		 * de lo contrario incluye la libreria correspondiente
		 * @access private
		 */
		private function Autoloader() {
			if(class_exists('Twig_Autoloader') == false) {
				require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'Twig', 'Autoloader.php'));
			}
			Twig_Autoloader::register();
		}
	}