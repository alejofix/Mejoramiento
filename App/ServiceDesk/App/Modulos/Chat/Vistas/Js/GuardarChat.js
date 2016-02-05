	var neonGuardarChat = new Object;
	
	jQuery.extend(neonGuardarChat, {
		
		/**
		 *	init()
		 *	
		 *	Genera la ejecucion del proceso solicitado
		 */
		init : function() {
			
		{% if Sesion.Permisos.Chat.ESCRITURA == true %}
			
			jQuery(".enviarForm").click(function(evento) {
				evento.preventDefault();
				var ID = jQuery(this).attr('data-name');
				var mensaje = jQuery("#mensaje_" + ID).val();
				
				if(mensaje != '') {
					neonGuardarChat.guardarMensaje(mensaje, ID);
					jQuery("#FormMensaje_" + ID)[0].reset();
				}
				else {
					jQuery("#alerta_" + ID).removeClass('hidden');
					setTimeout(function() {
						jQuery("#alerta_" + ID).addClass('hidden');
					}, 4000);
				}
			});
			
		{% endif %}
			
			neonGuardarChat.cargarMensajesAutomatico();
			setInterval(function() {
				neonGuardarChat.cargarMensajesAutomatico();
			}, 3000);
			
		},
		
		/**
		 * 	guardarMensaje()
		 *
	 	 *	Genera el proceso de guardar el mensaje
	 	 *	y recuperar el listado del chat
	 	 *	@param string mensaje
	 	 *	@param integer ID
		 */
		guardarMensaje : function(mensaje, ID) {
			var resultado = neonGuardarChat.peticionAjax(mensaje, ID);
			jQuery("#chat_" + ID).html(neonGuardarChat.guardarMensajeIDChat(resultado));
		},
		
		/**
		 * 	guardarMensajeIDChat()
		 *
	 	 *	Genera el proceso de contruccion
	 	 *	del listado del chat
	 	 *	@param array peticion
		 */
		guardarMensajeIDChat: function(peticion) {
			var constructor = [];
			for (i=0; i< peticion.length; i++) {
				constructor.push(neonGuardarChat.liConstructor(peticion[i]["USUARIO"], peticion[i]["NOMBRE"], peticion[i]["MENSAJE"], peticion[i]["FECHA"]));
			}
			return constructor.join("\n");
		},
		
		/**
		 * 	liConstructor()
		 *
	 	 *	genera la plantilla correspondiente
	 	 *	para generar el item de la lista
		 */
		liConstructor : function(usuario, nombre, mensaje, fecha) {
			var constructor = [];
			constructor.push('<li><div class="comment-details">');
			constructor.push('<div class="comment-head">');
			constructor.push('<strong>Usuario:</strong> ' + usuario + ' <strong>Nombre:</strong> ' + nombre);
			constructor.push('</div>');
			constructor.push('<p class="comment-text" style="color: black; font-family:verdana;">' + mensaje + '</p>');
			constructor.push('<div class="comment-footer">');
			constructor.push('<div class="comment-time">' + fecha + '</div>');
			constructor.push('</div>');
			constructor.push('</div>');
			constructor.push('</li>');
			return constructor.join("\n");
		},
		
		/**
		 * 	peticionAjax()
		 *
	 	 *	genera la peticion ajax correspondiente
	 	 *	en la cual guarda el mensaje correspondiente
		 */
		peticionAjax : function(mensaje, ID) {
			var peticion = null;
			jQuery.ajax({
				type: 'POST',
				data: { MENSAJE : mensaje, SALA : ID },
				dataType : "json",
				cache : false,
				async: false,
				url : "{{ NeuralRutaApp|e }}/Chat/Mensaje/Index",
				success : function(resultado) {
					peticion = resultado;
				}
			});
			return peticion;
		},
		
		cargarMensajesAutomatico() {
			jQuery.ajax({
				type: 'POST',
				data: { SALAS : {{ listaSalas|raw }} },
				dataType : "json",
				cache : false,
				async: false,
				url : "{{ NeuralRutaApp|e }}/Chat/Mensaje/salas",
				success : function(resultado) {
					var suma = [];
					for (var nombre in resultado) {
						jQuery("#" + nombre).html(neonGuardarChat.guardarMensajeIDChat(resultado[nombre]));
						var dato = Number(jQuery('[data-info="' + nombre + '"]').text());
						suma.push(dato);
					}
				}
			});
		}
	});
	
	jQuery(document).ready(function() {
		neonGuardarChat.init();
	});