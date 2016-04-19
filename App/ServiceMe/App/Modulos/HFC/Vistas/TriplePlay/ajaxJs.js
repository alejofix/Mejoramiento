	
	/**
	 *	peticionAjax(idForm, idRespuesta, urlDireccion)
	 *
	 *	Genera la peticion ajax y genera el proceso correspondiente
	 *	de respuesta html
	 *	@param idForm: id del formulario
	 *	@param idRespuesta: id donde se cargara la respuesta
	 */
	function peticionAjax(idForm, idRespuesta) {
		$.ajax({
				type : "POST",
				dataType : "html",
				data : $("#" + idForm).serialize(),
				url : "{{ NeuralRutaApp|e }}/HFC/TriplePlay/ajaxGuion",
				cache : false,
				beforeSend : function() {
					$("#" + idRespuesta).html('... Espere un Momento Por Favor ... !!!!');
				},
				success : function(resultado) {
					$("#Respuesta").html(resultado);
					jQuery('#Guion-1').modal('show', {backdrop: 'static'});
				}
			});
	}