
	jQuery(document).ready(function() {
		jQuery("#prioridad").change(function() {
			jQuery("#prioridad option:selected").each(function() {
				var seleccion = jQuery(this).val();
				jQuery.ajax({
					url: "{{ NeuralRutaApp|e }}/Matriz/TriplePlay/HorasCalculo",
					cache : false,
					dataType :  "json",
					data : { prioridad : seleccion },
					type : "POST",
					success : function(resultado) {
						jQuery("#horafin").attr('value', resultado.fecha);
					}
				});
				
			});
		});
	});