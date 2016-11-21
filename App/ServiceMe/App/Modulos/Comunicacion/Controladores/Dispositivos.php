<?php
	
	class Dispositivos extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::validar('LECTURA');
		}
		
		public function Index() {
			echo 'Seleccionar Dispositivo';
		}
		
		/**
		 * Dispositivos::Tablet()
		 * 
		 * Genera la lista de avisos
		 * @return raw
		 */
		public function Tablet() {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Titulo', 'Comunicación');
			$plantilla->Parametro('avisos', $this->Modelo->avisos());
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'Tablet.html')));
		}
		
		/**
		 * Dispositivos::AjaxTablet()
		 * 
		 * Genera la validacion de existencia peticion ajax
		 * @return void
		 */
		public function AjaxTablet() {
			if(AppValidar::PeticionAjax() == true):
				$plantilla = new NeuralPlantillasTwig(APP);
				$plantilla->Parametro('avisos', $this->Modelo->avisos());
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'AjaxTablet.html')));
			else:
				echo 'No es posible cargar la información';
			endif;
		}
		
		/**
		 * Dispositivos::Television()
		 * 
		 * Carga pantalla de TV
		 * @return void
		 */
		public function Television() {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Titulo', 'Service-Co');
			$plantilla->Parametro('avisos', array_chunk($this->Modelo->avisos(), 4));
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'Television.html')));
		}
		
		/**
		 * Dispositivos::AjaxTelevision()
		 * 
		 * Genera la carga de la informacion de las paginas de datos
		 * que cambian automaticamente con jquery
		 * @return raw
		 */
		public function AjaxTelevision() {
			$data = $this->Modelo->avisos();
			$pagina = (count($data) >= 1) ? array_chunk($data, 4) : array();
			
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('cantidad', count($data));
			$plantilla->Parametro('paginas', count($pagina));
			$plantilla->Parametro('listado', $pagina);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'AjaxTelevision.html')));
		}
		
		/**
		 * Dispositivos::PC()
		 * 
		 * Carga pantalla Proyeccion PC a TV 
		 * @return void
		 */
		public function PC() {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Titulo', 'Service-Co');
			$plantilla->Parametro('avisos', array_chunk($this->Modelo->avisos(), 8));
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'PC.html')));
		}
		
		/**
		 * Dispositivos::AjaxPC()
		 * 
		 * Genera la carga de la informacion de las paginas de datos
		 * que cambian automaticamente con jquery
		 * @return raw
		 */
		public function AjaxPC() {
			$data = $this->Modelo->avisos();
			$pagina = (count($data) >= 1) ? array_chunk($data, 8) : array();
			
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('cantidad', count($data));
			$plantilla->Parametro('paginas', count($pagina));
			$plantilla->Parametro('listado', $pagina);
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'AjaxPC.html')));
		}
		
		/**
		 * Dispositivos::Movil()
		 * 
		 * Genera la lista de avisos para Celular
		 * @return raw
		 */
		public function Movil() {
			$plantilla = new NeuralPlantillasTwig(APP);
			$plantilla->Parametro('Titulo', 'Comunicación');
			$plantilla->Parametro('avisos', $this->Modelo->avisos());
			echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'Movil.html')));
		}
		
		/**
		 * Dispositivos::AjaxMovil()
		 * 
		 * Genera la validacion de existencia peticion ajax
		 * @return void
		 */
		public function AjaxMovil() {
			if(AppValidar::PeticionAjax() == true):
				$plantilla = new NeuralPlantillasTwig(APP);
				$plantilla->Parametro('avisos', $this->Modelo->avisos());
				echo $plantilla->MostrarPlantilla(implode(DIRECTORY_SEPARATOR, array('Dispositivos', 'AjaxMovil.html')));
			else:
				echo 'No es posible cargar la información';
			endif;
		}
		
		
	}