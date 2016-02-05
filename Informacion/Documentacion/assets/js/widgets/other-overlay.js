$(document).ready(function(){

  /* Loader Show */

  $('.loader-show').click(function(){

	var loaderTheme = $(this).attr('data-theme');
	var loaderOpacity = $(this).attr('data-opacity');
	var loaderStyle = $(this).attr('data-style');


	var loader = '<div id="loader-overlay" class="ui-front hide loader ui-widget-overlay ' + loaderTheme + ' opacity-' + loaderOpacity + '"><img src="assets/images/loader-' + loaderStyle + '.gif" alt="" /></div>';


	$('#loader-overlay').remove();
    $('body').append(loader);
    $('#loader-overlay').fadeIn('fast');

	  //DEMO

	  var wait = window.setTimeout( function(){
	    $('#loader-overlay').fadeOut('fast')},
	    1500
	  );

  });

	/* Refresh Box */

	$('.refresh-button').click(function(ev){

		$('.glyph-icon', this).addClass('icon-spin display-block');

	    ev.preventDefault();

	    var refreshParent = $(this).parent().parent();

		var loaderTheme = $(this).attr('data-theme');
		var loaderOpacity = $(this).attr('data-opacity');
		var loaderStyle = $(this).attr('data-style');


		var loader = '<div id="refresh-overlay" class="ui-front hide loader ui-widget-overlay ' + loaderTheme + ' opacity-' + loaderOpacity + '"><img src="assets/images/loader-' + loaderStyle + '.gif" alt="" /></div>';


		$('#refresh-overlay').remove();
	    $(refreshParent).append(loader);
	    $('#refresh-overlay').fadeIn('fast');

		//DEMO

	    var wait = window.setTimeout( function(){
	    	$('.glyph-icon').removeClass('icon-spin display-block');
	      $('#refresh-overlay').fadeOut('fast')},
	      1000
	    );

	});

});