<?php

	class Autentificacion extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializar(APP);
			if(isset($_SESSION['SESIONEXPERTOS']) == true):
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Inicio'));
			endif;
		}
		
		/**
		 * Autentificacion::Index()
		 * 
		 * genera la validacion de existencia de datos post
		 * @return void
		 */
		public function Index() {
			if(isset($_POST) == true):
				$this->loginCriptoValidacion();
			else:
				echo 'No existen datos post';
			endif;
		}
		
		
		/**
		 * Autentificacion::loginCriptoValidacion()
		 *
		 * genera la validacion si se esta enviando desde el formulario
		 * los datos 
		 * @return void
		 */
		private function loginCriptoValidacion() {
			if(NeuralCriptografia::DeCodificar($_POST['enviar'], array(date("Y-m-d"), APP)) == true):
				$this->loginDatosVacios();
			else:
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index', 'Index', 'Index', array('ERRORLLAVE'))); 
                /* echo 'Llave no funciona'; */
			endif;
		}
		
		/**
		 * Autentificacion::loginDatosVacios()
		 * 
		 * valida que no esten vacios los datos del formulario
		 * @return void
		 */
		private function loginDatosVacios() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->loginValidarExistenciaUsuario();
			else:
                header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index', 'Index', 'Index', array('DATOSVACIOS'))); 
         	    /* echo 'Hay datos vacios en el formulario'; */
			endif;
		}
		
		/**
		 * Autentificacion::loginValidarExistenciaUsuario()
		 * 
		 * valida si el usuario existe en la base de datos
		 * @return void
		 */
		private function loginValidarExistenciaUsuario() {
			$DatosPost = AppFormato::Espacio()->Mayusculas(array('usuario'))->MatrizDatos($_POST);
			$cantidad = $this->Modelo->consultaCantidadUsuario($DatosPost['usuario'], $DatosPost['password']);
			if($cantidad == 1):
				$this->loginValidarEstado();
			else:
              	header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index', 'Index', 'Index', array('DATOSERRONEOS')));
                /*echo 'Usuario y/o Contraseña Incorrectos';*/
			endif;
		}
		
		/**
		 * Autentificacion::loginValidarEstado()
		 * 
		 * valida que el usuario este activo
		 * @return void
		 */
		private function loginValidarEstado() {
			$consulta = $this->Modelo->obtenerDatos();
			if($consulta['ESTADO'] == 1):
				$this->loginGenerarSesion($consulta);
			elseif($consulta['ESTADO'] == 2):
				echo 'Usuario Suspendido o Inactivo';
			else:
                header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Index', 'Index', 'Index', array('CONSULTARADMINISTRADOR')));
				/* echo 'Consultar con el administrador hay inconsistencias con el usuario'; */
			endif;
		}
		
		/**
		 * Autentificacion::loginGenerarSesion()
		 * 
		 * Registra la sesion y la crea redireccionando al inicio
		 * @param array $consulta
		 * @return void
		 */
		private function loginGenerarSesion($consulta = false) {
			unset($consulta['PERMISO'], $consulta['ESTADO']);
			AppSesion::registrar($consulta, $this->OrdenarPermisos($this->Modelo->Permisos()), $this->Modelo->consultaSalasChat());
			header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Inicio'));
			exit();
		}
		
		/**
		 * Autentificacion::OrdenarPermisos()
		 * 
		 * Genera el proceso de organizar los permisos correspondientes
		 * @param array $Array
		 * @return array
		 */
		private function OrdenarPermisos($Array = false) {
			foreach ($Array AS $Valor):
				$Lista[$Valor['NOMBRE']] = array('LECTURA' => $Valor['LECTURA'], 'ESCRITURA' => $Valor['ESCRITURA'], 'ELIMINAR' => $Valor['ELIMINAR'], 'ACTUALIZAR' => $Valor['ACTUALIZAR']);
			endforeach;
			return $Lista;
		}
	}