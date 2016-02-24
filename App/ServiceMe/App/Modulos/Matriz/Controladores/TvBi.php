<?php
	
	class TvBi extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * TvBi::Index()
		 * 
		 * genera la plantilla del formulario de construccion del formulario
		 * @return string
		 */
		public function Index() {
			$Val = new NeuralJQueryFormularioValidacion(true, true, false);
			$Val->Requerido('AVISO', 'Debe Ingresar el Número del Aviso');
			$Val->Numero('AVISO', 'El Aviso debe Ser Numérico');
			$Val->CantMaxCaracteres('AVISO', 10, 'Debe ingresar aviso con 10 Números');
			$Val->Requerido('PRIORIDAD', 'Debe Seleccionar una Opción');
			$Val->Requerido('AVERIA', 'Debe Seleccionar una Opción');
			$Val->Requerido('MATRIZ', 'Indique Número de Matriz');
			$Val->Numero('MATRIZ', 'El dato es Numérico');
            $Val->CantMaxCaracteres('MATRIZ', 6, 'Máximo 6 caracteres numéricos');
            $Val->ControlEnvio('peticionAjax("FormularioTvBi", "Respuesta", "'.NeuralRutasApp::RutaUrlAppModulo('Matriz', 'TvBi', 'ajaxGuion').'");');
			            
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('activo', __CLASS__);
			$Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Cta. Matriz');
            $Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$Plantilla->Parametro('Validacion', $Val->Constructor('FormularioTvBi'));
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('TvBi', 'Index.html')));
		}
		
		/**
		 * TvBi::ajaxGuion()
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
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Matriz', 'TvBi'));
				exit();
			endif;
		}
		
		/**
		 * TvBi::ajaxExistencia()
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
		 * TvBi::ajaxPost()
		 * 
		 * Genera la validacion si hay algun dato vacio
		 * @return void
		 */

    		private function ajaxPost() {
    			if(AppValidar::Vacio(array('matriz', 'aviso', 'prioridad'))->MatrizDatos($_POST) == true):
    				$this->ajaxFormato();
    			else:
    				exit('El formulario tiene campos vacios');
    			endif;
    		}
		
		/**
		 * TvBi::ajaxFormato()
		 * 
		 * Genera el formato a los datos post
		 * @return void
		 */
		private function ajaxFormato() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$this->ajaxProcesar($DatosPost);
		}
		
		/**
		 * TvBi::AjaxProcesar()
		 * 
		 * genera el proceso de validacion y creacion del guion
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesar($array = false) {
			$guion = new NeuralPlantillasTwig(APP);
			$guion->Parametro('Datos', $array);
			$GuionCreado = $guion->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('TvBi', 'Prioridad', $array['PRIORIDAD'].'.html')));
						
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('guion', $GuionCreado);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('TvBi', 'ajaxProcesar2.html')));
			// Ayudas::print_r($array);
		}
	}