<?php
	
	class Int_Tel extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Int_Tel::Index()
		 * 
		 * Muestra el formulario de ingreso del guion
		 * @return string
		 */
		public function Index() {
	 		$plantilla = new NeuralPlantillasTwig(APP);
	 		$plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$plantilla->Parametro('activo', __CLASS__);
			$plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
	 		$plantilla->Parametro('prioridades', $this->Modelo->listadoPrioridades());
	 		$plantilla->Parametro('ubicaciones', $this->Modelo->listadoUbicacion());
			$plantilla->Parametro('listaAveria', $this->Modelo->listaAveria());
 			$plantilla->Parametro('validacion', $this->IndexValidacionFormulario());
 			$plantilla->Parametro('selectDependiente', $this->IndexSelectDependiente());
 			$plantilla->Parametro('razonAveria', $this->Modelo->listadoRazonAveria());
 			echo $plantilla->MostrarPlantilla('Int_Tel', 'Index.html');
		}
		
		/**
		 * Int_Tel::IndexValidacionFormulario()
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
			$Val->Requerido('UBICACION', 'Debe Seleccionar una Opción');
			$Val->Requerido('AVERIA', 'Debe Seleccionar una Opción');
			$Val->Requerido('RAZON', 'Debe Seleccionar una Opción');
			$Val->Requerido('REGIONAL[]', 'Seleccione la Regional a Reportar');
			$Val->Requerido('NODO', 'Debe ingresar los nodos reportados desde su excel');
			$Val->Requerido('HORAFIN', 'Defina la fecha-hora que termina el Aviso');
			$Val->ControlEnvio('peticionAjax("FormularioGuion", "Respuesta");');
			return $Val->Constructor('FormularioGuion');
		}
		
		/**
		 * Int_Tel::IndexSelectDependiente()
		 * 
		 * genera la carga del select dependiente de 
		 * la ubicacion correspondiente
		 * @return string
		 */
		private function IndexSelectDependiente() {
			return NeuralJQueryScript::IdPrincipal('ubicacion')
				->IdSecundario('cargaAjaxUbicacion')
				->URL(NeuralRutasApp::RutaUrlAppModulo('HFC', 'Int_Tel', 'ubicacionAjax'))
				->Puntero('UBICACION')
				->SelectDependientePost();
		}
		
		/**
		 * Int_Tel::ajaxJs()
		 * 
		 * Genera el script correspondiente de javascript
		 * @return string
		 */
		public function ajaxJs($archivo = false) {
			header('Content-Type: application/javascript');
			AppSesion::validar('ESCRITURA');
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Int_Tel', 'ajaxJs.js')));
		}
		
		
		/**
		 * Int_Tel::Horas & HorasCalculo
		 * 
		 * calcula la hora según prioridad
		 * 
		 */
		public function Horas($archivo = false) {
			header('Content-Type: application/javascript');
			AppSesion::validar('ESCRITURA');
			$plantilla = new NeuralPlantillasTwig(APP);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Int_Tel', 'Horas.js')));
		}
		public function HorasCalculo() {
			if(AppValidar::PeticionAjax() == true):
				$valor = $_POST['prioridad'];
				$array = array('1' => 5, '2' => 8, '3' => 12, '4' => 17);
				$fecha = new DateTime(date("Y-m-d H:i"));
				$fecha->add(new DateInterval('PT'.$array[$valor].'H'));
				echo json_encode(array('fecha' => $fecha->format('Y-m-d / H:i')));
			else:
				echo json_encode(array('fecha' => '0000-00-00 / 00:00'));
			endif;
		}
		
		public function HorasCalculoRuido() {
			if(AppValidar::PeticionAjax() == true):
				$valor = $_POST['averia'];
				$array = array('Des_Ruido' => 98, 'Des_Potencia' => 98, 'Des_Niveles' => 98);
				$fecha = new DateTime(date("Y-m-d H:i"));
				$fecha->add(new DateInterval('PT'.$array[$valor].'H'));
				echo json_encode(array('fecha' => $fecha->format('Y-m-d H:i')));
			else:
				echo json_encode(array('fecha' => '0000-00-00 00:00'));
			endif;
		}

		/**
		 * Int_Tel::ubicacionAjax()
		 * 
		 * Carga la plantilla del campo ubicacion
		 * @return string
		 */
		public function ubicacionAjax() {
			if(AppValidar::PeticionAjax() == true):
				$this->ubicacionAjaxExistencia();
			else:
				exit('No es posible procesar la petición ajax');
			endif;
		}
		
		/**
		 * Int_Tel::ubicacionAjaxExistencia()
		 * 
		 * Valida existencia de datos post
		 * @return string
		 */
		private function ubicacionAjaxExistencia() {
			if(isset($_POST['UBICACION']) == true):
				$this->ubicacionAjaxMostrar();
			else:
				exit('No es posible procesar la petición del formulario');
			endif;
		}
		
		/**
		 * Int_Tel::ubicacionAjaxMostrar()
		 *
		 * Muestra plantilla correspondiente 
		 * @return string
		 */
		private function ubicacionAjaxMostrar() {
			$archivo = ($_POST['UBICACION'] == 1 OR $_POST['UBICACION'] == 2 OR $_POST['UBICACION'] == 3) ? 'Nodos.html' : $this->ubicacionAjaxSeleccion();
			$plantilla = new NeuralPlantillasTwig(APP);
			
			if($_POST['UBICACION'] == 4):
				$plantilla->Parametro('regionales', $this->Modelo->listadoRegional());
			endif;
			
			echo $plantilla->MostrarPlantilla('Int_Tel', 'ubicacionAjaxMostrar', $archivo);
		}
		
		/**
		 * Int_Tel::ubicacionAjaxSeleccion()
		 *
		 * Valida si existe valor de regional 
		 * @return string
		 */
		private function ubicacionAjaxSeleccion() {
			return ($_POST['UBICACION'] == 4) ? 'Regional.html' : 'Nacional.html';
		}
		
		/**
		 * Int_Tel::ajaxGuion()
		 * 
		 * Genera el proceso de generar el guion
		 * @return void
		 */
		public function ajaxGuion() {
			AppSesion::validar('ESCRITURA');
			if(AppValidar::PeticionAjax() == true):
				$this->ajaxGuionExistencia();
			else:
				exit('No es posible procesar la petición');
			endif;
		}
		
		/**
		 * Int_Tel::ajaxGuionExistencia()
		 *
		 * Se valida si se envian datos post 
		 * @return void
		 */
		private function ajaxGuionExistencia() {
			if(isset($_POST) == true):
				$this->ajaxGuionCampoVacio();
			else:
				exit('Mensaje de error no hay datos post');
			endif;
		}
		
		/**
		 * Int_Tel::ajaxGuionCampoVacio()
		 * 
		 * Valida si existen datos vacios en el servidor
		 * @return void
		 */
		private function ajaxGuionCampoVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->ajaxGuionFormato();
			else:
				exit('El formulario tiene campos vacios');
			endif;
		}
		
		/**
		 * Int_Tel::ajaxGuionFormato()
		 * 
		 * Genera el formato de los datos
		 * @return void
		 */
		private function ajaxGuionFormato() {
			$DatosPost = AppFormato::Espacio(array('AVISO', 'PRIORIDAD', 'UBICACION', 'INTERMITENCIA', 'AFECTACION', 'AVERIA', 'TIPO', 'RAZON'))->MatrizDatos($_POST);
			$DatosPost['INTERMITENCIA'] = (array_key_exists('INTERMITENCIA', $DatosPost) == true) ? $DatosPost['INTERMITENCIA'] : 'NO';
			$this->ajaxGuionProcesar($DatosPost);
		}
		
		/**
		 * Int_Tel::ajaxGuionProcesar()
		 * 
		 * Se genera guion correspondiente
		 * @param array $array
		 * @return string
		 */
		private function ajaxGuionProcesar($array = false) {
			$sesion = AppSesion::obtenerDatos();
			switch($array['UBICACION']):
				case 1: $this->ajaxProcesoNodo($array, $sesion['Informacion']['USUARIO_RR']);
					break;
				case 2: $this->ajaxProcesoNodo($array, $sesion['Informacion']['USUARIO_RR']);
					break;
				case 4: $this->ajaxProcesoRegional($array, $sesion['Informacion']['USUARIO_RR']);	
			endswitch;
			
		}
		
		/**
		 * Int_Tel::ajaxProcesoRegional()
		 * 
		 * genera el proceso de generar y guardar los datos 
		 * de regional
		 * 
		 * @param array $array
		 * @param strig $usuario
		 * @return string
		 */
		private function ajaxProcesoRegional ($array = false, $usuario = false) {
			$guion = $this->ajaxProcesoPlantilla($array);
			$id = $this->Modelo->HFC($array, $usuario, $guion);
			if ($id['ID'] >= 1) {
				$this->Modelo->guardarRegionales($array['REGIONAL'], $id['ID']);
				$this->ajaxPlantillaBase($guion);
			}
			else {
				exit('No es posible generar el Guión contacte con el administrador del sistema');
			}
		}
		
		
		/**
		 * Int_Tel::ajaxProcesoNodo()
		 * 
		 * Genera el proceso de guardar la informacion y mostrar
		 * la plantilla correspondiente
		 * 
		 * @param array $array
		 * @param string $usuario
		 * @return void
		 */
		private function ajaxProcesoNodo($array = false, $usuario = false) {
			$nodos = explode("\n", trim($array['NODO']));
			if(count($nodos) >= 1):
				$guion = $this->ajaxProcesoPlantilla($array);
				unset($array['boton'], $array['NODO']);
				$id = $this->Modelo->HFC($array, $usuario, $guion);
				if($id['ID'] >= 1):
					$this->Modelo->guardarNodos($nodos, $id['ID']);
					$this->ajaxPlantillaBase($guion);
				else:
					exit('No fue posible guardar el guion correspondiente, validar con el administrador del sistema');
				endif;
			else:
				exit('Debe agregar por lo menos un nodo');
			endif;
		}
		
		/**
		 * Int_Tel::ajaxProcesoPlantilla()
		 * 
		 * Genera el guion correspondiente consultando desde la base de
		 * datos
		 * 
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesoPlantilla($array = false) {
			$plantilla = $this->Modelo->consultaPlantilla('HFC', 'INTYTEL', 'PRIORIDAD '.$array['PRIORIDAD']);
			$guion = new NeuralPlantillasTwig(APP);
			$guion->Parametro('Datos', $array);
			$guion->Parametro('plantilla', stripcslashes(html_entity_decode($plantilla['PLANTILLA'])));
			$guion->Parametro('ubicacion', $this->Modelo->consultaUbicacion($array['UBICACION']));
			return $guion->MostrarPlantilla('Int_Tel', 'ajaxProcesoPlantilla.html');
		}
		
		/**
		 * Int_Tel::ajaxPlantillaBase()
		 * 
		 * Plantilla base que se muestra el guion y la opcion
		 * de copiar el guion 
		 * 
		 * @param string $guion
		 * @return string
		 */
		private function ajaxPlantillaBase($guion = false, $ubicacion = false) {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('guion', $guion);
			echo $plantilla->MostrarPlantilla('Int_Tel', 'ajaxPlantillaBase.html');
		}
	}