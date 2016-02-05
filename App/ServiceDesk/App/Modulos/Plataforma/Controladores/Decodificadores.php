<?php
	
	class Decodificadores extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Decodificadores::Index()
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
            $Val->Requerido('deco1', 'Debe seleccionar una Opción');
			$Val->ControlEnvio('peticionAjax("FormularioPDecodificadores", "Respuesta", "'.NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Decodificadores', 'ajaxGuion').'");');
			
			$Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Titulo', 'Plataforma');
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
			$Plantilla->Parametro('Validacion', $Val->Constructor('FormularioPDecodificadores'));
			$Plantilla->Parametro('DecodificadoresListado', $this->Modelo->ConsultaListaDecos());
            echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Decodificadores', 'Index.html')));
 
 		}
		
		/**
		 * Decodificadores::ajaxGuion()
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
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Decodificadores'));
				exit();
			endif;
		}
		
		/**
		 * Decodificadores::ajaxExistencia()
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
		 * Decodificadores::ajaxPost()
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
		 * Decodificadores::ajaxFormato()
		 * 
		 * Genera el formato a los datos post
		 * @return void
		 */
		private function ajaxFormato() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$this->ajaxProcesar($DatosPost);
		}
		
		/**
		 * Decodificadores::AjaxProcesar()
		 * 
		 * genera el proceso de validacion y creacion del guion
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesar($array = false) {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Datos', $array);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Decodificadores', 'ajaxProcesar.html')));
		}
        
        
	}