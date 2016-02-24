<?php
	
	use \Neural\WorkSpace;
	
	class AppSesion {
		
		private static $Sesion = 'SESIONEXPERTOS';
		private static $Llave = '49e3a66a4d675fb397a04cc406071458';
		private static $Contenedor = false;
		private static $Limite = 32000;
		
		public static function registrar($Informacion = false, $Permisos = false, $chat = false) {
			NeuralSesiones::AsignarSession(self::$Sesion, array(
				'Informacion' => $Informacion,
				'Chat' => $chat,
				'Permisos' => $Permisos,
				'Sesion' => array(
					'Llave' => implode('_', array(self::$Llave, $Informacion['USUARIO_RR'], date("Y-m-d"))),
					'Fecha' => date("Y-m-d H:i:s"),
					'Inicio' => strtotime(date("Y-m-d H:i:s"))
				)
			));
		}
		
		public static function validar($Permiso = false) {
			if(isset($_SESSION[self::$Sesion]) == false):
				NeuralSesiones::Inicializar(APP);
			endif;
			if(isset($_SESSION[self::$Sesion]) == true) :
				self::$Contenedor = NeuralSesiones::ObtenerSession(self::$Sesion);
				if(self::$Contenedor['Sesion']['Llave'] == implode('_', array(self::$Llave, self::$Contenedor['Informacion']['USUARIO_RR'], date("Y-m-d")))) :
					$Resultado = strtotime(date("Y-m-d H:i:s")) - self::$Contenedor['Sesion']['Inicio'];
					if($Resultado<= self::$Limite) :
						$ModReWrite = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
						$Modulo = (isset($ModReWrite[1]) == true) ? $ModReWrite[1] : 'Index';
						
						if(array_key_exists($Modulo, self::$Contenedor['Permisos']) == true):
							if(array_key_exists($Permiso, self::$Contenedor['Permisos'][$Modulo]) == true):
								if(self::$Contenedor['Permisos'][$Modulo][$Permiso] == false):
									exit('NO TIENE PERMISO PARA VER ESTE MODULO');
								endif;
							else:
								exit('NO TIENE PERMISO PARA VER ESTE MODULO ESTA INGRESANDO DE FORMA ILEGAL');
							endif;
						else:
							exit('NO TIENE PERMISO PARA VER ESTE MODULO');
						endif;
						
					else:
						header("Location: ".NeuralRutasApp::RutaUrlApp('LogOut', 'Error', array('TIEMPOLIMITE')));
						exit();
					endif;
				else:
					header("Location: ".NeuralRutasApp::RutaUrlApp('LogOut', 'Error', array('NOPERMISOS')));
					exit();
				endif;
			else:
				header("Location: ".NeuralRutasApp::RutaUrlApp('LogOut'));
				exit();
			endif;
		}
		
		public static function obtenerDatos() {
			return (is_array(self::$Contenedor) == true) ? self::$Contenedor : 'Error No Hay Datos';
		}
	}