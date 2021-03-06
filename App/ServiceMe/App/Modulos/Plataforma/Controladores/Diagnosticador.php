<?php
	
    class Diagnosticador extends Controlador  
    {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		/**
		 * Diagnosticador::Index()
		 * 
		 * genera la plantilla inicial
		 * @return void
		 */
		public function Index() 
        {
            $Val = new NeuralJQueryFormularioValidacion(true, true, false);
            $Val->Requerido('aviso', 'Debe Ingresar # Aviso');
            $Val->Requerido('sintoma', 'Debe Ingresar Síntoma del Aviso');
            $Val->Requerido('soporte', 'Debe ingresar Proceso de Soporte');
            $Val->Requerido('marcacion', 'Debe Ingresar la primera Marcación');
            $Val->CantMaxCaracteres('aviso', 20, '20 caracteres permitidos ');
            $Val->CantMaxCaracteres('sintoma', 510, '500 caracteres permitidos ');
            $Val->CantMaxCaracteres('soporte', 910, '900 caracteres permitidos ');
            $Val->CantMaxCaracteres('marcacion', 7, 'Marcación Errada');
            
            $Val->ControlEnvio('peticionAjax("FormularioPDiagnosticador", "Respuesta", "'.NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Diagnosticador', 'ajaxGuion').'");');
            
            
			$Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
            $Plantilla->Parametro('Validacion', $Val->Constructor('FormularioPDiagnosticador'));
			$Plantilla->Parametro('Sesion', AppSesion::obtenerDatos());
            $Plantilla->Parametro('Titulo', 'Bienvenido');
            $Plantilla->Parametro('activo', __CLASS__);
            $Plantilla->Parametro('URL', \Neural\WorkSpace\Miscelaneos::LeerModReWrite());
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Diagnosticador', 'Index.html')));
		}
        
        
        /**
		 * Diagnosticador::ajaxGuion()
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
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Plataforma', 'Diagnosticador'));
				exit();
			endif;
		}
        
        /**
		 * Diagnosticador::ajaxExistencia()
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
		 * Diagnosticador::ajaxPost()
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
		 * Diagnosticador::ajaxFormato()
		 * 
		 * Genera el formato a los datos post
		 * @return void
		 */
		private function ajaxFormato() {
			$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
			$this->ajaxProcesar($DatosPost);
		}
		
		/**
		 * Diagnosticador::AjaxProcesar()
		 * 
		 * genera el proceso de validacion y creacion del guion
		 * @param array $array
		 * @return string
		 */
		private function ajaxProcesar($array = false) {
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Datos', $array);
			echo $Plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Diagnosticador', 'ajaxProcesar.html')));
		}
        
        

	}