 $(document).ready(function() {

    $('.choose-bg').click(function(){

        var boxedBg = $(this).attr('boxed-bg');

        $('body').css('background',boxedBg);

        var setBoxedBg = $.cookie('set-boxed-bg', boxedBg);

    });

    $('.change-layout-theme a').click(function(){

            var layoutTheme = $(this).attr('layout-theme');

            $('#loading').slideDown({
                complete: function(){

                    if ( layoutTheme != '' ) {

                        $("#layout-theme").attr("href","assets/themes/minified/fides/color-schemes/" + layoutTheme + ".min.css");

                        var setLayoutThemeCookie = $.cookie('set-layout-theme', layoutTheme);

                    }

                }
            });

            $('#loading').delay(1500).slideUp();

    });

 	themefromCookie();
 	bgFromCookie();

 });

 function themefromCookie(){

 	var layoutThemefromCookie = $.cookie('set-layout-theme');


 	if ( layoutThemefromCookie != null ) {

 		$("#layout-theme").attr("href","assets/themes/minified/fides/color-schemes/" + layoutThemefromCookie + ".min.css");

 	}

 };


 function bgFromCookie(){

 	var bgFromCookie = $.cookie('set-boxed-bg');

 	if ($('body').hasClass('boxed-layout')) {

 		$('body').css('background',bgFromCookie);

 	};

 };

 $(function() {

    $('.change-theme-btn').click(function(){

        $('.theme-customizer').animate({'right':'0'});

    });

	$('.theme-customizer .theme-wrapper').click(function(){

		$(this).parent().animate({'right':'-350'});

	});

});