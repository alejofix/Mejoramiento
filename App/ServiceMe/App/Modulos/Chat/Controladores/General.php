<?php
	
	class General extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index() {
			$fecha = date("H:i:s");
			$data = <<<EOT
			<li>
    			<span class="user">Art Ramadani</span>
    			<p>Are you here?</p>
    			<span class="time">{$fecha}</span>
    		</li>
    	
    		<li class="opponent">
    			<span class="user">Catherine J. Watkins</span>
    			<p>This message is pre-queued.</p>
    			<span class="time">{$fecha}</span>
    		</li>
    	
    		<li class="opponent">
    			<span class="user">Catherine J. Watkins</span>
    			<p>Whohoo!</p>
    			<span class="time">{$fecha}</span>
    		</li>
    	
    		<li class="opponent unread">
    			<span class="user">Catherine J. Watkins</span>
    			<p>Do you like it?</p>
    			<span class="time">{$fecha}</span>
    		</li>
EOT;
			echo $data;
		}
	}