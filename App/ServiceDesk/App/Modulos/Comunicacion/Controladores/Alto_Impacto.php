<?php
	
	class Alto_Impacto extends Controlador {
		
		/**
		 * Index::__Construct()
		 * 
		 * genera la validacion del permiso asignado para su visualizacion
		 * @return void
		 */
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Index::Index()
		 * 
		 * genera la plantilla inicial
		 * @return void
		 */
		public function Index() {
			AppSesion::validar('ESCRITURA');
		    $Val = new NeuralJQueryFormularioValidacion(true, true, false);  
            $Val->Requerido('aviso', 'Debe Ingresar Número de Aviso');
            $Val->Numero('aviso', 'El Aviso debe Ser Numérico');
            $Val->CantMaxCaracteres('aviso', 10, 'Debe ingresar aviso con 10 Números');
            $Val->Requerido('prioridad', 'Debe seleccionar una Opción');
            
            $Val->Requerido('falla', 'Debe Ingresar un Síntoma');
            $Val->CantMaxCaracteres('falla', 15, ' Síntoma con máx. 15 caracteres');
            $Val->Requerido('regional', 'Debe Ingresar la Afectación');
            $Val->CantMaxCaracteres('regional', 27, 'Afectación  con máx. 27caracteres');
            $Val->Requerido('detalle', 'Debe seleccionar una Opción');
            $Val->ControlEnvio('procesoAjax();');
            
            
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Comunicación');
            $Plantilla->Parametro('Validacion', $Val->Constructor('FormularioAltoImpacto'));
            $Plantilla->Parametro('DetalleLista', $this->Modelo->DetalleLista());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Alto_Impacto', 'Alto_Impacto.html')));
			
		}
		
		/**
		 * Alto_Impacto::procesoAjax()
		 * 
		 * valida si se esta generando un proceso ajax o no
		 * @return void
		 */
		public function procesoAjax() {
			if(AppValidar::PeticionAjax() == true):
				$this->procesoAjaxVacio();
			else:
				exit('Peticion no procesada');
			endif;
		} 
		
		/**
		 * Alto_Impacto::procesoAjaxVacio()
		 * 
		 * valida que el formulario no tenga campos vacios
		 * @return void
		 */
		private function procesoAjaxVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->procesoAjaxProceso();
			else:
				exit('Hay datos vacios en el formulario');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxProceso()
		 * 
		 * genera el proceso de guardado e inicia el resultado json
		 * @return void
		 */
		private function procesoAjaxProceso() {
			$DatosPost = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
			unset($DatosPost['boton']);
			$sesion = AppSesion::obtenerDatos();
			echo json_encode($this->Modelo->guardarAltoImpacto($DatosPost, $sesion['Informacion']['USUARIO_RR']));
		}
		
		/**
		 * Alto_Impacto::Actualizar()
		 * 
		 * Genera el proceso de actualizacion
		 * @param bool $idCod
		 * @return void
		 */
		public function Actualizar($idCod = false) {
			if(is_bool($idCod) == false):
				$this->ActualizarDecodificar(AppHexAsciiHex::HEX_ASCII($idCod));
			else:
				echo 'No se ha pasado el aviso a editar';
			endif;
		}
		
		/**
		 * Alto_Impacto::ActualizarDecodificar()
		 * 
		 * Genera la decodificacion de la informacion correspondiente
		 * @param bool $cod
		 * @return void
		 */
		private function ActualizarDecodificar($cod = false) {
			$id = NeuralCriptografia::DeCodificar($cod, array(date("Y-m-d"), APP));
			if(is_numeric($id) == true):
				$this->ActualizarConsulta($id);
			else:
				echo 'La informacion ingresada no es correcta';
			endif;
		}
		
		/**
		 * Alto_Impacto::ActualizarConsulta()
		 * 
		 * Determina si el registro existe
		 * @param bool $id
		 * @return void
		 */
		private function ActualizarConsulta($id = false) {
			if($this->Modelo->ActualizarConsulta($id) >= 1):
				$this->ActualizarFormulario();
			else:
				echo 'No hay datos registrados de este aviso';
			endif;
		}
		
		/**
		 * Alto_Impacto::ActualizarFormulario()
		 * 
		 * formulario de actualizacion de datos
		 * @return string
		 */
		private function ActualizarFormulario() {
			
			$Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
			$Validacion->Requerido('PRIORIDAD_INPUT', 'Seleccione una Opción');
			$Validacion->Requerido('FALLA_INPUT', 'Es requerido Ingresar el Síntoma');
			$Validacion->CantMaxCaracteres('FALLA_INPUT', 15);
			$Validacion->Requerido('REGIONAL_INPUT', 'debe ingresar la Afectación');
			$Validacion->CantMaxCaracteres('REGIONAL_INPUT', 27);
			$Validacion->Requerido('DETALLE_INPUT', 'Seleccione una Opción');
			$Validacion->ControlEnvio('procesoAjax();');
			
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Validacion', $Validacion->Constructor('FormularioAltoImpacto'));
			$plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $plantilla->Parametro('activo', __CLASS__);
            $plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $plantilla->Parametro('Titulo', 'Comunicación');
			$plantilla->Parametro('datos', $this->Modelo->ActualizarConsultaDatos());
			$plantilla->Parametro('DetalleLista', $this->Modelo->DetalleLista());
			$plantilla->Filtro('codificar' , function($data) {
				$cod = NeuralCriptografia::Codificar($data, array(date("Y-m-d"), APP));
				return AppHexAsciiHex::ASCII_HEX($cod);
			});
			$plantilla->Funcion('nombreDetalle', function($data) {
				return $this->Modelo->consultarNombreDetalle($data);
			});
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Alto_Impacto', 'Actualizar.html')));
		}
		
		/**
		 * Alto_Impacto::procesoAjaxActualizar()
		 * 
		 * @return void
		 */
		public function procesoAjaxActualizar() {
			if(AppValidar::PeticionAjax() == true):
				$this->procesoAjaxActualizarVacio();
			else:
				header("HTTP/1.0 400 Solicitud Incorrecta");
				exit('Petición ajax no procesada');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxActualizarVacio()
		 * 
		 * @return void
		 */
		private function procesoAjaxActualizarVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->procesoAjaxActualizarProceso();
			else:
				header("HTTP/1.0 400 Solicitud Incorrecta");
				exit('Hay datos vacios en el formulario');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxActualizarProceso()
		 * 
		 * @return void
		 */
		private function procesoAjaxActualizarProceso() {
			$DatosPost = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
			$DatosPost['ID_AVISO'] = NeuralCriptografia::DeCodificar(AppHexAsciiHex::HEX_ASCII($DatosPost['PROCESO']), array(date("Y-m-d"), APP));
			unset($DatosPost['boton'], $DatosPost['PROCESO']);
			$sesion = AppSesion::obtenerDatos();
			echo json_encode($this->Modelo->actualizarAltoImpacto($DatosPost, $sesion['Informacion']['USUARIO_RR']));
		}
		
		/**
		 * Alto_Impacto::Listado()
		 * 
		 * Listado de avisos
		 * @return void
		 */
		public function Listado() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Comunicación');
			$Plantilla->Parametro('listado', $this->Modelo->listadoAltoImpacto());
			$Plantilla->Filtro('codificar', function($data) {
				$cod = NeuralCriptografia::Codificar($data, array(date("Y-m-d"), APP));
				return AppHexAsciiHex::ASCII_HEX($cod);
			});
			echo $Plantilla->MostrarPlantilla('Alto_Impacto/Listado.html');
		}
		
		/**
		 * Alto_Impacto::procesoAjaxFinalizar()
		 * 
		 * Genera el proceso de finalizacion
		 * @return void
		 */
		public function procesoAjaxFinalizar() {
			if(AppValidar::PeticionAjax() == true):
				$this->procesoAjaxFinalizarVacio();
			else:
				exit('No es posible procesar la petición');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxFinalizarVacio()
		 *
		 * Validacion de campos vacios 
		 * @return void
		 */
		private function procesoAjaxFinalizarVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->procesoAjaxFinalizarProcesar();
			else:
				exit('El formulario contiene datos vacíos');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxFinalizarProcesar()
		 * 
		 * genera el proceso de finalizacion
		 * @return void
		 */
		private function procesoAjaxFinalizarProcesar() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$sesion = AppSesion::obtenerDatos();
			echo json_encode($this->Modelo->finalizarAltoImpacto($DatosPost['AVISO'], $sesion['Informacion']['USUARIO_RR']));
		}
		
		/**
		 * Alto_Impacto::procesoAjaxEliminar()
		 * 
		 * genera el proceso de eliminar
		 * @return void
		 */
		public function procesoAjaxEliminar() {
			if(AppValidar::PeticionAjax() == true):
				$this->procesoAjaxEliminarVacio();
			else:
				exit('No es posible procesar la petición');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxEliminarVacio()
		 * 
		 * validacion de campos vacios
		 * @return void
		 */
		private function procesoAjaxEliminarVacio() {
			if(AppValidar::Vacio()->MatrizDatos($_POST) == true):
				$this->procesoAjaxEliminarProcesar();
			else:
				exit('El formulario contiene datos vacíos');
			endif;
		}
		
		/**
		 * Alto_Impacto::procesoAjaxEliminarProcesar()
		 * 
		 * Proceso de eliminacion
		 * @return void
		 */
		private function procesoAjaxEliminarProcesar() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$sesion = AppSesion::obtenerDatos();
			echo json_encode($this->Modelo->eliminarAltoImpacto($DatosPost['AVISO'], $sesion['Informacion']['USUARIO_RR']));
		}
		
		/**
		 * Alto_Impacto::Observar()
		 * 
		 * Obervar avisos
		 * @return void
		 * @author alejo
		 */
		public function Observar() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('Titulo', 'Comunicación');
			echo $Plantilla->MostrarPlantilla('Alto_Impacto/Observar.html');
		}
		
		
			

	}