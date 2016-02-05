
	jQuery(document).ready(function() {
		jQuery("#prioridad").change(function() {
			jQuery("#prioridad option:selected").each(function() {
				var seleccion = jQuery(this).val();
				jQuery.ajax({
					url: "{{ NeuralRutaApp|e }}/HFC/Int_Tel/HorasCalculo",
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
	
	
	
	jQuery(document).ready(function() {
		jQuery("#averia").change(function() {
			jQuery("#averia option:selected").each(function() {
				var seleccion = jQuery(this).val();
				jQuery.ajax({
					url: "{{ NeuralRutaApp|e }}/HFC/Int_Tel/HorasCalculoRuido",
					cache : false,
					dataType :  "json",
					data : { averia : seleccion },
					type : "POST",
					success : function(resultado) {
						jQuery("#horafin").attr('value', resultado.fecha);
					}
				});
				
			});
		});
	});