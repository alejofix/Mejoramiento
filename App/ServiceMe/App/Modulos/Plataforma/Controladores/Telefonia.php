<?php
	
	class Telefonia extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Telefonia::Index()
		 * 
		 * genera la plantilla del formulario de construccion del formulario
		 * @return string
		 */
		public function Index() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, false);
			$Val->CantMaxCaracteres('aviso', 20, '20 caracteres permitidos ');
            $Val->Requerido('aviso', 'Debe Ingresar # Aviso');
            $Val->Numero('aviso', 'El Aviso debe Ser Numérico');
			$Val->CantMaxCaracteres('aviso', 10, 'Debe ingresar aviso con 10 Números');
            $Val->Requerido('sintoma', 'Debe Ingresar Síntoma del Aviso');
            $Val->Requerido('marcacion', 'Debe Ingresar la primera Marcación');
            $Val->Requerido('CM1', 'Debe seleccionar una Opción');
            $Val->Requerido('SS1', 'Debe seleccionar una Opción');
			$Val->ControlEnvio('peticionAjax("FormularioPTelefonia", "Respuesta", "'.NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Telefonia', 'ajaxGuion').'");');
			
			$Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Plataforma');
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$Plantilla->Parametro('Validacion', $Val->Constructor('FormularioPTelefonia'));
			$Plantilla->Parametro('SoftSwichListado', $this->Modelo->ConsultaListaSS());
            $Plantilla->Parametro('CablemodemListado', $this->Modelo->ConsultaListaCMs());
        	echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Telefonia', 'Index.html')));
		//	Ayudas::print_r(AppSesion::obtenerDatos());
		}
		
		/**
		 * Telefonia::ajaxGuion()
		 * 
		 * genera el proceso de la construccion del guion
		 * se genera la validacion de escritura para poder guardar
		 * @return void
		 */
		public function ajaxGuion() {
			AppSesion::validar('ESCRITURA');
			
			if(AppValidar::PeticionAjax() == true):
				$this->ajaxExistencia();
			else:
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Telefonia'));
				exit();
			endif;
		}
		
		/**
		 * Telefonia::ajaxExistencia()
		 * 
		 * Genera la validacion del envio de los datos post
		 * @return void
		 */
		private function ajaxExistencia() {
			if(isset($_POST) == true):
				$this->ajaxPost();
			else:
				exit('Mensaje de error no hay datos post');
			endif;
		}
		
		/**
		 * Telefonia::ajaxPost()
		 * 
		 * Genera la validacion si hay algun dato vacio
		 * @return void
		 */
		private function ajaxPost() {
			if(AppValidar::Vacio(array('aviso', 'sintoma'))->MatrizDatos($_POST) == true):
				$this->ajaxFormato();
			else:
				exit('El formulario tiene campos vacios');
			endif;
		}
		
		/**
		 * Telefonia::ajaxFormato()
		 * 
		 * Genera el formato a los datos post
		 * @return void
		 */
		private function ajaxFormato() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$this->ajaxProcesar($DatosPost);
		}
		
		/**
		 * Telefonia::AjaxProcesar()
		 * 
		 * genera el proceso de validacion y creacion del guion
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesar($array = false) {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Datos', $array);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Telefonia', 'ajaxProcesar.html')));
		}
        
        
	}