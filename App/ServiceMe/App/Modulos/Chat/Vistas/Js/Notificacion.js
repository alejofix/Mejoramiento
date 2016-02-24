	var neonNotificador = new Object;
	
	jQuery.extend(neonNotificador, {
		
		init: function() {
			if('sessionStorage' in window && window['sessionStorage'] !== null) {
				neonNotificador.procesar();
				neonNotificador.contadorSuperior();
				setInterval(function() {
					neonNotificador.procesar();
					neonNotificador.contadorSuperior();
				}, 3000);
			}
			
			jQuery(".Class_Notifica_sala").click(function() {
				var sala = jQuery(this).attr('data-sala');
				jQuery("#Notificacion_Sala_" + sala).addClass('is-hidden');
				sessionStorage.removeItem('Mostrar_' + sala);
				sessionStorage.setItem('Mostrar_' + sala, '0');
			});
		},
		
		contadorSuperior : function() {
			var data = /Mostrar_/;
			var suma = 0;
			for (var llave in sessionStorage) {
				if (data.test(llave) == true) {
					suma += Number(sessionStorage.getItem(llave));
				}
			}
				
			if(suma >= 1) {
				jQuery("#cantidadChat1").text(suma);
				jQuery("#cantidadChat2").text(suma);
                jQuery("#cantidadChat3").text(suma);
				jQuery("#cantidadChat1").removeClass('is-hidden');
				jQuery("#cantidadChat2").removeClass('is-hidden');
                jQuery("#cantidadChat3").removeClass('is-hidden');
			}
			else {
				jQuery("#cantidadChat1").addClass('is-hidden');
				jQuery("#cantidadChat2").addClass('is-hidden');
                jQuery("#cantidadChat3").addClass('is-hidden');
				jQuery("#cantidadChat1").text(suma);
				jQuery("#cantidadChat2").text(suma);
                jQuery("#cantidadChat3").text(suma);
			}
			
		},
		
		procesar : function() {
			var peticion = neonNotificador.peticionAjax();
			var usuario = sessionStorage.getItem('usuario');
			if(usuario == null) {
				sessionStorage.clear();
				sessionStorage.setItem('usuario', '{{ Sesion.Informacion.USUARIO_RR|e }}');
				neonNotificador.gestionSalas(peticion['Salas']);
			}
			else {
				if(usuario == '{{ Sesion.Informacion.USUARIO_RR|e }}') {
					neonNotificador.gestionSalas(peticion['Salas']);
				}
				else {
					sessionStorage.clear();
					sessionStorage.setItem('usuario', '{{ Sesion.Informacion.USUARIO_RR|e }}');
					neonNotificador.gestionSalas(peticion['Salas']);
				}
			}
		},
		
		gestionSalas : function(salas) {
			for (i=0; i<salas.length; i++) {
				neonNotificador.gestionSalaSeleccion(salas[i]['SALA'], salas[i]['CANTIDAD']);
			}
		},
		
		gestionSalaSeleccion : function(sala, cantidad) {
			var salaSeleccion = sessionStorage.getItem('Sala_' + sala);
			if(salaSeleccion == null) {
				sessionStorage.setItem('Sala_' + sala, cantidad);
				sessionStorage.setItem('Mostrar_' + sala, cantidad);
				jQuery("#Notificacion_Sala_" + sala).text(cantidad);
				jQuery("#Notificacion_Sala_" + sala).removeClass('is-hidden');
			}
			else {
				neonNotificador.gestionSalaExistente(sala, cantidad);
			}
		},
		
		gestionSalaExistente : function(sala, cantidad) {
			var salaSeleccion = sessionStorage.getItem('Sala_' + sala);
			var mostrarSeleccion = sessionStorage.getItem('Mostrar_' + sala);
			var total = Number(cantidad) - Number(salaSeleccion);
			if(mostrarSeleccion >= 1) {
				var totalMostrar = Number(total) + Number(mostrarSeleccion);
				sessionStorage.removeItem('Sala_' + sala);
				sessionStorage.setItem('Sala_' + sala, cantidad);
				sessionStorage.removeItem('Mostrar_' + sala);
				sessionStorage.setItem('Mostrar_' + sala, totalMostrar);
				jQuery("#Notificacion_Sala_" + sala).text(totalMostrar);
				jQuery("#Notificacion_Sala_" + sala).removeClass('is-hidden');
			}
			else {
				sessionStorage.removeItem('Sala_' + sala);
				sessionStorage.setItem('Sala_' + sala, cantidad);
				sessionStorage.removeItem('Mostrar_' + total);
				sessionStorage.setItem('Mostrar_' + sala, total);
				jQuery("#Notificacion_Sala_" + sala).text(total);
			}
		},
		
		/**
		 *	peticionAjax()
		 *
		 *	Genera la peticion para determinar la cantidad
		 *	de mensajes
		 */
		peticionAjax : function() {
			var info = '';
			jQuery.ajax({
				type: 'POST',
				dataType : "json",
				cache : false,
				async: false,
				url : '{{ NeuralRutaApp|e }}/Chat/Historial',
				success : function(resultado) {
					info = resultado;
				}
			});
			return info;
		}
	});
	
	jQuery(document).ready(function() {
		neonNotificador.init();
	});