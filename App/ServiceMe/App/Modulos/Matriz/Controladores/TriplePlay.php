<?php
	
	class TriplePlay extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
			$this->peticion->temporal->crear('sesion', AppSesion::obtenerDatos());
		}
		
		/**
		 * TriplePlay::Index()
		 * 
		 * Muestra el formulario de ingreso del guion
		 * @return string
		 */
		public function Index() {
	 		$plantilla = new NeuralPlantillasTwig(APP);
	 		$plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$plantilla->Parametro('activo', __CLASS__);
			$plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			$plantilla->Parametro('listaAveria', $this->Modelo->listaAveria());
 			$plantilla->Parametro('validacion', $this->IndexValidacionFormulario());
 			$plantilla->Parametro('razonAveria', $this->Modelo->listadoRazonAveria());
 			echo $plantilla->MostrarPlantilla('TriplePlay', 'Index.html');
		}
		
		/**
		 * TriplePlay::IndexValidacionFormulario()
		 * 
		 * Genera la validacion del formulario en la vista
		 * con jquery
		 * @return string
		 */
		private function IndexValidacionFormulario() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, false);
			$Val->Requerido('AVISO', 'Debe Ingresar el Número del Aviso');
			$Val->Numero('AVISO', 'El Aviso debe Ser Numérico');
			$Val->CantMaxCaracteres('AVISO', 10, 'Debe ingresar aviso con 10 Números');
			$Val->Requerido('PRIORIDAD', 'Debe Seleccionar una Opción');
			$Val->Requerido('RAZON', 'Debe Seleccionar una Opción');
			$Val->Requerido('REGIONAL[]', 'Seleccione la Regional a Reportar');
			$Val->Requerido('NODO', 'Debe ingresar el Nodo al que pertenece la Matriz');
			$Val->Requerido('HORAFIN', 'Defina la fecha-hora que termina el Aviso');
			$Val->Requerido('MATRIZ', 'Debe informar el Número de la Matriz');
			$Val->ControlEnvio('peticionAjax("FormularioGuion", "Respuesta");');
			return $Val->Constructor('FormularioGuion');
		}
		
		/**
		 * TriplePlay::ajaxJs()
		 * 
		 * Genera el script correspondiente de javascript
		 * @return string
		 */
		public function ajaxJs($archivo = false) {
			header('Content-Type: application/javascript');
			AppSesion::validar('ESCRITURA');
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('TriplePlay', 'ajaxJs.js')));
		}
		
		
		/**
		 * TriplePlay::Horas & HorasCalculo
		 * 
		 * calcula la hora según prioridad
		 * 
		 */
		public function Horas($archivo = false) {
			header('Content-Type: application/javascript');
			AppSesion::validar('ESCRITURA');
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('TriplePlay', 'Horas.js')));
		}
		
		/**
		 * TriplePlay::HorasCalculo()
		 *
		 * Genera el calculo de horas segun la prioridad 
		 * @return void
		 */
		public function HorasCalculo() {
			if(AppValidar::PeticionAjax() == true):
				$valor = $_POST['prioridad'];
				$array = array('1' => 12, '2' => 12);
				$fecha = new DateTime(date("Y-m-d H:i"));
				$fecha->add(new DateInterval('PT'.$array[$valor].'H'));
				echo json_encode(array('fecha' => $fecha->format('Y-m-d / H:i')));
			else:
				echo json_encode(array('fecha' => '0000-00-00 / 00:00'));
			endif;
		}
		
		/**
		 * TriplePlay::ajaxGuion()
		 * 
		 * valida la peticion ajax si existe
		 * @return void
		 */
		public function ajaxGuion() {
			if($this->peticion->ajax() == true):
				$this->existenciaDatos();
			else:
				//Mostrar error no peticion ajax
			endif;
		}
		
		/**
		 * TriplePlay::existenciaDatos()
		 * 
		 * valida si se envian los datos desde el 
		 * formulario correspondiente
		 * @return void
		 */
		private function existenciaDatos() {
			if($this->peticion->post->existencia('boton') == true):
				$this->cargarGuion();
			else:
				//Debe enviar los datos desde el formulario
			endif;
		}
		
		/**
		 * TriplePlay::cargarGuion()
		 * 
		 * Genera la construccion de la plantilla del guion
		 * @return void
		 */
		private function cargarGuion() {
			$pl = $this->Modelo->consultaPlantilla('MATRIZ', 'TRIPLEPLAY', 'PRIORIDAD '.$this->peticion->post->obtener('PRIORIDAD'));
			
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Datos', $this->peticion->post->obtener());
			$plantilla->Parametro('plantilla', $pl['PLANTILLA']);
			
			$this->peticion->post->crear('GUION', $plantilla->MostrarPlantilla('TriplePlay', 'ajaxProcesoPlantilla.html'));
			$fecha = explode('/', $this->peticion->post->obtener('HORAFIN'));
			$this->peticion->post->reemplazar('HORAFIN', trim($fecha[0]));
			
			$this->procesar();
		}
		
		private function procesar() {
			$resultado = $this->Modelo->MATRIZ($this->peticion->post, $this->peticion->temporal->obtener('sesion')->obtener('Informacion')->obtener('USUARIO_RR'));
			//Ayudas::print_r($resultado);
			//Ayudas::print_r($this->peticion->temporal->obtener());
		}
	}