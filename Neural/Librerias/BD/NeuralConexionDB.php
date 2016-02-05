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
	 
	use \Neural\WorkSpace;
	
	class NeuralConexionDB {
		
		/**
		 * Tipo de Estado
		 */
		const DESARROLLO = 'Desarrollo';
		
		/**
		 * Tipo de Estado
		 */
		const DOCTRINE = 'Doctrine';
		
		/**
		 * IP por Defecto
		 */
		const IP = '::1';
		
		/**
		 * Tipo de Estado
		 */
		const PDO = 'PDO';
		
		/**
		 * Tipo de Estado
		 */
		const PREDETERMINADO = 'Predeterminado';
		
		/**
		 * Tipo de Estado
		 */
		const PRODUCCION = 'Produccion';
		
		/**
		 * Metodo Privado
		 * ArchivoConfigAccesos()
		 * 
		 * Retorna en archivo de configuracion de accesos del sistema
		 * @access private
		 */
		private static function ArchivoConfigAccesos() {
			if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))) == true) {
				$Array = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'Acceso.json'))), true);
				if($Array == true) {
					return $Array;
				}
				else {
					throw new NeuralException('El Formato del Archivo de Configuración de Accesos No Es Correcto');
				}
			}
			else {
				throw new NeuralException('El Archivo de Configuración de Accesos No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * ArchivoConfigDB($Tipo = self::DESARROLLO)
		 * 
		 * Retorna en archivo de configuracion de Bases de Datos del sistema
		 * @access private
		 * @param $Tipo
		 */
		private static function ArchivoConfigDB($Tipo = self::DESARROLLO) {
			
			if($Tipo == self::DESARROLLO) {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'BaseDatos_Desarrollo.json'))) == true) {
					$Array = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'BaseDatos_Desarrollo.json'))), true);
					if($Array == true) {
						return $Array;
					}
					else {
						throw new NeuralException('El Formato del Archivo de Configuración de Bases de Datos No Es Correcto');
					}
				}
				else {
					throw new NeuralException('El Archivo de Configuración de Base de Datos No Existe');
				}
			}
			elseif($Tipo == self::PRODUCCION) {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'BaseDatos_Produccion.json'))) == true) {
					$Array = json_decode(file_get_contents(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootConfiguracion__, 'App', 'BaseDatos_Produccion.json'))), true);
					if($Array == true) {
						return $Array;
					}
					else {
						throw new NeuralException('El Formato del Archivo de Configuración de Bases de Datos No Es Correcto');
					}
				}
				else {
					throw new NeuralException('El Archivo de Configuración de Base de Datos No Existe');
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * DoctrineDBAL($DBConfig = self::PREDETERMINADO)
		 * 
		 * Retorna la Conexion DBAL generada por Doctrine 2
		 * @param $DBConfig: Nombre de la aplicacion configurada en el archivo de configuracion de BD
		 */
		public static function DoctrineDBAL($DBConfig = self::PREDETERMINADO) {
			self::Doctrine_Liberia();
			$Parametros = self::ParametrosDB(self::ValidarApp(), $DBConfig, self::DOCTRINE);
			return \Doctrine\DBAL\DriverManager::getConnection($Parametros);
		}
		
		/**
		 * Metodo Privado
		 * Doctrine_Liberia()
		 * 
		 * Valida si fue llamada la libreria de Doctrine de lo contrario la carga al Sistema
		 * @access private
		 */
		private static function Doctrine_Liberia() {
			$Archivo = implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'Doctrine', 'Common', 'ClassLoader.php'));
			if(is_bool(array_search($Archivo, get_included_files())) == true) {
				if(file_exists($Archivo) == true) {
					require $Archivo;
					$ClassLoader = new \Doctrine\Common\ClassLoader('Doctrine');
 					$ClassLoader->register();
				}
				else {
					throw new NeuralException('La Libreria de Doctrine No Existe en la Carpeta de Proveedores');
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * NotORM($DBConfig = self::PREDETERMINADO)
		 * 
		 * Retorna la Conexion PDO con NotORM y valida si se incluye la libreria de NotORM
		 * @param $DBConfig: Nombre de la aplicacion configurada en el archivo de configuracion de BD
		 */
		public static function NotORM($DBConfig = self::PREDETERMINADO) {
			self::NotORM_Libreria();
			$Conexion = new NotORM(self::PDO_OrganizarParametros(self::ValidarApp(), $DBConfig));
			return $Conexion;
		}
		
		/**
		 * Metodo Privado
		 * NotORM_Libreria()
		 * 
		 * Valida si fun cargada las librerias de NotORM sino fue cargada es incluida
		 * @access private
		 */
		private static function NotORM_Libreria() {
			if(class_exists('NotORM_Abstract') == false) {
				if(file_exists(implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'NotORM', 'NotORM.php'))) == true) {
					require implode(DIRECTORY_SEPARATOR, array(__SysNeuralFileRootProveedores__, 'NotORM', 'NotORM.php'));
				}
				else {
					throw new NeuralException('La Libreria de NotORM No Existe en la Carpeta de Proveedores');
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * PDO($DBConfig = self::PREDETERMINADO)
		 * 
		 * Retorna la conexion nativa de PHP de PDO
		 * @param $DBConfig: Nombre de la aplicacion configurada en el archivo de configuracion de BD
		 */
		public static function PDO($DBConfig = self::PREDETERMINADO) {
			return self::PDO_OrganizarParametros(self::ValidarApp(), $DBConfig);
		}
		
		/**
		 * Metodo Privado
		 * PDO_OrganizarParametros($App = self::PREDETERMINADO, $DBConfig = self::PREDETERMINADO)
		 * 
		 * Organiza los parametros para generar la conexion de PDO y retorna el objeto
		 * @access private
		 */
		private static function PDO_OrganizarParametros($App = self::PREDETERMINADO, $DBConfig = self::PREDETERMINADO) {
			$Parametros = self::ParametrosDB($App, $DBConfig, self::PDO);
			if(is_bool($Parametros['parametros']) == true) {
				return $Conexion = new PDO($Parametros['dns'], $Parametros['user'], $Parametros['password']);
			}
			elseif(is_array($Parametros['parametros']) == true) {
				return $Conexion = new PDO($Parametros['dns'], $Parametros['user'], $Parametros['password'], $Parametros['parametros']);
			}
		}
		
		/**
		 * Metodo Privado
		 * ParametrosDB($App = self::PREDETERMINADO, $DBConfig = self::PREDETERMINADO, $Clase = self::PDO)
		 * 
		 * Regresa los parametros de la BD y determina la clase de conexion
		 * @access private
		 */
		private static function ParametrosDB($App = self::PREDETERMINADO, $DBConfig = self::PREDETERMINADO, $Clase = self::PDO) {
			if(self::ValidarBD_Desarrollo($App, $_SERVER['REMOTE_ADDR']) == true) {
				return self::ParametrosDBSeleccion($DBConfig, self::DESARROLLO, $Clase);
			}
			else {
				return self::ParametrosDBSeleccion($DBConfig, self::PRODUCCION, $Clase);
			}
		}
		
		/**
		 * Metodo Privado
		 * ParametrosDBSeleccion($DBConfig = self::PREDETERMINADO, $Tipo = self::DESARROLLO, $Aplicacion = self::PDO)
		 * 
		 * Retorna los parametros del archivo de configuracion de la BD
		 * @access private
		 */
		private static function ParametrosDBSeleccion($DBConfig = self::PREDETERMINADO, $Tipo = self::DESARROLLO, $Aplicacion = self::PDO) {
			$Parametros = self::ArchivoConfigDB($Tipo);
			if(array_key_exists($DBConfig, $Parametros) == true) {
				if(array_key_exists($Aplicacion, $Parametros[$DBConfig]) == true) {
						return $Parametros[$DBConfig][$Aplicacion];
				}
				else {
					throw new NeuralException('La Configuración de la Aplicación No Existe, El Tipo de Configuración '.$Aplicacion.' No Existe');
				}
			}
			else {
				throw new NeuralException('La Aplicación de Base de Datos No Existe');
			}
		}
		
		/**
		 * Metodo Privado
		 * ValidarApp()
		 * 
		 * Retorna la aplicacion actual leyendo el ModReWrite de Apache
		 * @access private
		 */
		private static function ValidarApp() {
			$Matriz = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
			if(defined('APPNEURALPHPHOST') == true) {
				$Aplicacion = APPNEURALPHPHOST;
			}
			else {
				$Aplicacion = (isset($Matriz[0]) == true) ? $Matriz[0] : self::PREDETERMINADO;
			}
			unset($Matriz);
			return $Aplicacion;
		}
		
		/**
		 * Metodo Privado
		 * ValidarBD_Desarrollo($App = self::PREDETERMINADO, $IP = self::IP)
		 * 
		 * Determina si la ip actual debe trabajar en Modo desarrollo o produccion
		 * @access private
		 */
		private static function ValidarBD_Desarrollo($App = self::PREDETERMINADO, $IP = self::IP) {
			$Parametros = self::ArchivoConfigAccesos();
			if($Parametros[$App]['Entorno_Desarrollo']['Localhost']['Activo'] == true) {
				if(array_key_exists($IP, array_flip($Parametros[$App]['Entorno_Desarrollo']['Localhost']['ip'])) == true) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
	}