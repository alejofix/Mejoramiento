$(document).ready(function(){

    /* Box switch toggle */

  $('.switch-button').click(function(ev){

    ev.preventDefault();

    var switchParent = $(this).attr('switch-parent');
    var switchTarget = $(this).attr('switch-target');

    $(switchParent).slideToggle();
    $(switchTarget).slideToggle();

  });

    /* Button Toggle */

  $('.button-toggle').click(function(ev){

    ev.preventDefault();
    $(this).next('.toggle-content').slideToggle();

  });

    /* Button Toggle */

  $('.button-toggle-open').click(function(ev){

    $('.glyph-icon', this).toggleClass('icon-caret-right');

    ev.preventDefault();
    $(this).next('.toggle-content-open').slideToggle();

  });

    /* Content Box Show/Hide Buttons */

  $('.button-toggle').hover(function(){

    $(".content-box-header a.btn", this).fadeIn('fast');

  },function(){

    $(".content-box-header a.btn", this).fadeOut('normal');

  });


    /* Content Box Toggle */

    $('.box-toggle .content-box-header .toggle-button').click(function(ev) {

      ev.preventDefault();

      $(".icon-toggle", this).toggleClass("icon-chevron-down").toggleClass("icon-chevron-up");

      if ( $(this).parents(".content-box:first").hasClass('content-box-closed') ) {

        $(this).parents(".content-box:first").removeClass('content-box-closed').find(".content-box-wrapper").slideDown('fast');

      } else {

        $(this).parents(".content-box:first").addClass('content-box-closed').find(".content-box-wrapper").slideUp('fast');
        
      }

    });

    /* Content Box Remove */

    $('.remove-button').click(function(ev){

        ev.preventDefault();

        var animationEFFECT = $(this).attr('data-animation');

        var animationTARGET = $(this).parents(".content-box:first");

        $(animationTARGET).addClass('animated');
        $(animationTARGET).addClass(animationEFFECT);

        var wait = window.setTimeout( function(){
          $(animationTARGET).slideUp()},
          500
        );

        /* Demo show removed content box */

        var wait2 = window.setTimeout( function(){
          $(animationTARGET).removeClass(animationEFFECT).fadeIn()},
          2500
        );

    });

  /* Close Info Boxes */

  $(function() {

    $(".infobox-close").click(function(ev){

      ev.preventDefault();

      $(this).parent().fadeOut();

    });


  });

});