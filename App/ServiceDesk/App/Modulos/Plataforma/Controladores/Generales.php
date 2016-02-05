<?php
	
	class Generales extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Generales::Index()
		 * 
		 * genera la plantilla del formulario de construccion del formulario
		 * @return string
		 */
		public function Index() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, false);
			$Val->CantMaxCaracteres('aviso', 40, '40 caracteres permitidos ');
            $Val->Requerido('aviso', 'Debe Ingresar Ref. Aviso');
            $Val->Requerido('sintoma', 'Debe Ingresar Síntoma del Aviso');
            
			$Val->ControlEnvio('peticionAjax("FormularioPGenerales", "Respuesta", "'.NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Generales', 'ajaxGuion').'");');
			
                       
			$Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
		    $Plantilla->Parametro('Titulo', 'Plataforma');
            $Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$Plantilla->Parametro('Validacion', $Val->Constructor('FormularioPGenerales'));
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Generales', 'Index.html')));
		}
		
		/**
		 * Generales::ajaxGuion()
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
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Generales'));
				exit();
			endif;
		}
		
		/**
		 * Generales::ajaxExistencia()
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
		 * Generales::ajaxPost()
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
		 * Generales::ajaxFormato()
		 * 
		 * Genera el formato a los datos post
		 * @return void
		 */
		private function ajaxFormato() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$this->ajaxProcesar($DatosPost);
		}
		
		/**
		 * Generales::AjaxProcesar()
		 * 
		 * genera el proceso de validacion y creacion del guion
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesar($array = false) {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Datos', $array);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Generales', 'ajaxProcesar.html')));
		}
	}